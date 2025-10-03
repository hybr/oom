<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/OrganizationPosition.php';
require_once '../../entities/Organization.php';
require_once '../../entities/PopularOrganizationDepartment.php';
require_once '../../entities/PopularOrganizationTeam.php';
require_once '../../entities/PopularOrganizationDesignation.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? 'list';

    switch ($action) {
        case 'list':
            handleList();
            break;
        case 'get':
            handleGet();
            break;
        case 'create':
            handleCreate();
            break;
        case 'update':
            handleUpdate();
            break;
        case 'delete':
            handleDelete();
            break;
        case 'statistics':
            handleStatistics();
            break;
        case 'approve':
            handleApprove();
            break;
        case 'post':
            handlePost();
            break;
        case 'markAsFilled':
            handleMarkAsFilled();
            break;
        case 'close':
            handleClose();
            break;
        default:
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                handleCreate();
            } else {
                handleList();
            }
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

function handleList() {
    try {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = max(1, min(100, intval($_GET['limit'] ?? 10)));
        $offset = ($page - 1) * $limit;

        $filters = [
            'organization_id' => $_GET['organization_id'] ?? null,
            'department_id' => $_GET['department_id'] ?? null,
            'team_id' => $_GET['team_id'] ?? null,
            'position_status' => $_GET['position_status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Get positions
        try {
            $positions = OrganizationPosition::all();
            if (!$positions) {
                $positions = [];
            }
        } catch (Exception $e) {
            error_log("Error loading positions: " . $e->getMessage());
            $positions = [];
        }

        // Apply filtering
        if (!empty($filters) && !empty($positions)) {
            $positions = array_filter($positions, function($pos) use ($filters) {
                try {
                    if (isset($filters['organization_id']) && $pos->organization_id != $filters['organization_id']) {
                        return false;
                    }
                    if (isset($filters['department_id']) && $pos->popular_organization_department_id != $filters['department_id']) {
                        return false;
                    }
                    if (isset($filters['team_id']) && $pos->popular_organization_team_id != $filters['team_id']) {
                        return false;
                    }
                    if (isset($filters['position_status']) && $pos->position_status !== $filters['position_status']) {
                        return false;
                    }
                    if (isset($filters['search']) &&
                        stripos($pos->position_title, $filters['search']) === false &&
                        stripos($pos->position_code, $filters['search']) === false) {
                        return false;
                    }
                    return true;
                } catch (Exception $e) {
                    error_log("Error filtering position: " . $e->getMessage());
                    return false;
                }
            });
        }

        // Get statistics
        $statistics = [
            'total' => count($positions),
            'active' => count(array_filter($positions, function($p) {
                return $p->is_active == 1;
            })),
            'vacant' => count(array_filter($positions, function($p) {
                return $p->is_vacant == 1;
            })),
            'critical' => count(array_filter($positions, function($p) {
                return $p->is_critical == 1;
            })),
        ];

        // Apply pagination
        $total = count($positions);
        $positions = array_slice($positions, $offset, $limit);

        // Enhance data with related information
        foreach ($positions as &$position) {
            try {
                $position->organization_name = $position->getOrganizationName();
                $position->department_name = $position->getDepartmentName();
                $position->team_name = $position->getTeamName();
                $position->designation_name = $position->getDesignationName();
            } catch (Exception $e) {
                error_log("Error enhancing position data for ID " . ($position->id ?? 'unknown') . ": " . $e->getMessage());
            }
        }

        echo json_encode([
            'success' => true,
            'positions' => array_map(function($p) { return $p->toArray(); }, $positions),
            'statistics' => $statistics,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => ceil($total / $limit),
                'totalItems' => $total,
                'itemsPerPage' => $limit
            ]
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error in handleList: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}

function handleGet() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    // Enhance with related data
    try {
        $position->organization_name = $position->getOrganizationName();
        $position->department_name = $position->getDepartmentName();
        $position->team_name = $position->getTeamName();
        $position->designation_name = $position->getDesignationName();
    } catch (Exception $e) {
        error_log("Error enhancing position data: " . $e->getMessage());
    }

    echo json_encode([
        'success' => true,
        'position' => $position->toArray()
    ]);
}

function handleCreate() {
    $data = [];

    // Get data from POST or form data
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    if (empty($data)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'No data provided'
        ]);
        return;
    }

    $position = new OrganizationPosition();

    // Handle checkboxes
    $data['is_critical'] = isset($data['is_critical']) ? 1 : 0;
    $data['is_leadership_position'] = isset($data['is_leadership_position']) ? 1 : 0;
    $data['remote_work_eligible'] = isset($data['remote_work_eligible']) ? 1 : 0;
    $data['is_active'] = isset($data['is_active']) ? 1 : 0;

    $position->fill($data);

    // Validate the position
    $errors = $position->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($position->beforeSave() && $position->save()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position created successfully',
            'position' => $position->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create position'
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    $data = [];
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    // Handle checkboxes
    $data['is_critical'] = isset($data['is_critical']) ? 1 : 0;
    $data['is_leadership_position'] = isset($data['is_leadership_position']) ? 1 : 0;
    $data['remote_work_eligible'] = isset($data['remote_work_eligible']) ? 1 : 0;
    $data['is_active'] = isset($data['is_active']) ? 1 : 0;

    $position->fill($data);

    // Validate the position
    $errors = $position->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($position->beforeSave() && $position->save()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position updated successfully',
            'position' => $position->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update position'
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    if ($position->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete position'
        ]);
    }
}

function handleStatistics() {
    try {
        $positions = OrganizationPosition::all();

        $statistics = [
            'total' => count($positions),
            'active' => count(array_filter($positions, function($p) {
                return $p->is_active == 1;
            })),
            'vacant' => count(array_filter($positions, function($p) {
                return $p->is_vacant == 1;
            })),
            'critical' => count(array_filter($positions, function($p) {
                return $p->is_critical == 1;
            })),
            'leadership' => count(array_filter($positions, function($p) {
                return $p->is_leadership_position == 1;
            })),
            'remote_eligible' => count(array_filter($positions, function($p) {
                return $p->remote_work_eligible == 1;
            })),
            'total_vacancies' => array_sum(array_map(function($p) {
                return $p->vacancy_count ?? 0;
            }, $positions)),
        ];

        echo json_encode([
            'success' => true,
            'statistics' => $statistics
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error getting statistics: ' . $e->getMessage()
        ]);
    }
}

function handleApprove() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    if ($position->approve()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position approved successfully',
            'position' => $position->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to approve position'
        ]);
    }
}

function handlePost() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    if ($position->post()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position posted successfully',
            'position' => $position->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to post position'
        ]);
    }
}

function handleMarkAsFilled() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    if ($position->markAsFilled()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position marked as filled successfully',
            'position' => $position->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to mark position as filled'
        ]);
    }
}

function handleClose() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Position ID is required'
        ]);
        return;
    }

    $position = OrganizationPosition::find($id);

    if (!$position) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Position not found'
        ]);
        return;
    }

    if ($position->close()) {
        echo json_encode([
            'success' => true,
            'message' => 'Position closed successfully',
            'position' => $position->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to close position'
        ]);
    }
}
?>
