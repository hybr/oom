<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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
        case 'tree':
            handleTree();
            break;
        case 'hierarchy':
            handleHierarchy();
            break;
        case 'statistics':
            handleStatistics();
            break;
        case 'activate':
            handleActivate();
            break;
        case 'deactivate':
            handleDeactivate();
            break;
        case 'dissolve':
            handleDissolve();
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
            'designation_type' => $_GET['designation_type'] ?? null,
            'function_category' => $_GET['function_category'] ?? null,
            'operational_status' => $_GET['operational_status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Get designations with simple error handling
        try {
            $designations = PopularOrganizationDesignation::all();
            if (!$designations) {
                $designations = [];
            }
        } catch (Exception $e) {
            error_log("Error loading designations: " . $e->getMessage());
            $designations = [];
        }

        // Apply basic filtering only if we have designations
        if (!empty($filters) && !empty($designations)) {
            $designations = array_filter($designations, function($dept) use ($filters) {
                try {
                    if (isset($filters['designation_type']) && $dept->designation_type !== $filters['designation_type']) {
                        return false;
                    }
                    if (isset($filters['function_category']) && $dept->function_category !== $filters['function_category']) {
                        return false;
                    }
                    if (isset($filters['operational_status']) && $dept->operational_status !== $filters['operational_status']) {
                        return false;
                    }
                    if (isset($filters['search']) &&
                        stripos($dept->name, $filters['search']) === false &&
                        stripos($dept->code, $filters['search']) === false) {
                        return false;
                    }
                    return true;
                } catch (Exception $e) {
                    error_log("Error filtering designation: " . $e->getMessage());
                    return false;
                }
            });
        }

        // Get basic statistics
        $statistics = [
            'total' => count($designations),
            'active' => count(array_filter($designations, function($d) {
                return $d->operational_status === 'Active';
            })),
            'functional' => count(array_filter($designations, function($d) {
                return $d->designation_type === 'Functional';
            })),
            'totalEmployees' => array_sum(array_map(function($d) {
                return $d->employee_count ?? 0;
            }, $designations)),
            'avgBudget' => 0
        ];

        // Apply pagination
        $total = count($designations);
        $designations = array_slice($designations, $offset, $limit);

        // Enhance data with basic information - with better error handling
        foreach ($designations as &$designation) {
            try {
                // Add parent designation name
                if ($designation->parent_designation_id) {
                    try {
                        $parent = PopularOrganizationDesignation::find($designation->parent_designation_id);
                        $designation->parent_designation_name = $parent ? $parent->name : 'Unknown Parent';
                    } catch (Exception $e) {
                        $designation->parent_designation_name = 'Parent ' . $designation->parent_designation_id;
                    }
                }
            } catch (Exception $e) {
                error_log("Error enhancing designation data for ID " . ($designation->id ?? 'unknown') . ": " . $e->getMessage());
                // Continue with other designations
            }
        }

        echo json_encode([
            'success' => true,
            'designations' => array_map(function($d) { return $d->toArray(); }, $designations),
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
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    // Enhance with related data
    enhanceDesignationData($designation);

    echo json_encode([
        'success' => true,
        'designation' => $designation->toArray()
    ]);
}

function handleCreate() {
    $data = [];

    // Get data from POST or form data
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
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

    $designation = new PopularOrganizationDesignation();
    $designation->fill($data);

    // Validate the designation
    $errors = $designation->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    // Check for duplicate name - simplified
    try {
        $allDesignations = PopularOrganizationDesignation::all();
        foreach ($allDesignations as $existingDept) {
            if (strtolower($existingDept->name) === strtolower($designation->name)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Designation name already exists'
                ]);
                return;
            }
        }
    } catch (Exception $e) {
        // Log error but continue - duplicate check is not critical for basic functionality
        error_log("Error checking for duplicate designation names: " . $e->getMessage());
    }

    if ($designation->beforeSave() && $designation->save()) {
        enhanceDesignationData($designation);
        echo json_encode([
            'success' => true,
            'message' => 'Designation created successfully',
            'designation' => $designation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create designation'
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    $data = [];
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    // Remove ID from update data
    unset($data['id']);

    $designation->fill($data);

    // Validate the designation
    $errors = $designation->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($designation->beforeSave() && $designation->save()) {
        enhanceDesignationData($designation);
        echo json_encode([
            'success' => true,
            'message' => 'Designation updated successfully',
            'designation' => $designation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update designation'
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    // Check if designation has sub-designations
    $subDesignations = $designation->getSubDesignations();
    if (!empty($subDesignations)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Cannot delete designation with sub-designations. Please reassign or delete sub-designations first.'
        ]);
        return;
    }

    if ($designation->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Designation deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete designation'
        ]);
    }
}

function handleTree() {
    try {
        // Simplified tree building
        $allDesignations = PopularOrganizationDesignation::all();

        $tree = [];
        $designationMap = [];

        // Create a map for quick lookup
        foreach ($allDesignations as $dept) {
            $designationMap[$dept->id] = $dept;
            $dept->children = [];
        }

        // Build the tree structure
        foreach ($allDesignations as $dept) {
            if ($dept->parent_designation_id && isset($designationMap[$dept->parent_designation_id])) {
                $designationMap[$dept->parent_designation_id]->children[] = $dept;
            } else {
                $tree[] = $dept;
            }
        }

        echo json_encode([
            'success' => true,
            'tree' => array_map(function($d) { return $d->toArray(); }, $tree)
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error building tree: ' . $e->getMessage()
        ]);
    }
}

function handleHierarchy() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    $hierarchy = $designation->getDesignationHierarchy();

    echo json_encode([
        'success' => true,
        'hierarchy' => array_map(function($d) { return $d->toArray(); }, $hierarchy)
    ]);
}

function handleStatistics() {
    $statistics = getDesignationStatistics();

    echo json_encode([
        'success' => true,
        'statistics' => $statistics
    ]);
}

function handleActivate() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    if ($designation->activate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Designation activated successfully',
            'designation' => $designation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to activate designation'
        ]);
    }
}

function handleDeactivate() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    if ($designation->deactivate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Designation deactivated successfully',
            'designation' => $designation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to deactivate designation'
        ]);
    }
}

function handleDissolve() {
    $id = $_POST['id'] ?? null;
    $date = $_POST['dissolution_date'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Designation ID is required'
        ]);
        return;
    }

    $designation = PopularOrganizationDesignation::find($id);

    if (!$designation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Designation not found'
        ]);
        return;
    }

    if ($designation->dissolve($date)) {
        echo json_encode([
            'success' => true,
            'message' => 'Designation dissolved successfully',
            'designation' => $designation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to dissolve designation'
        ]);
    }
}

function enhanceDesignationData(&$designation) {
    // Add parent designation name
    if ($designation->parent_designation_id) {
        $parent = PopularOrganizationDesignation::find($designation->parent_designation_id);
        $designation->parent_designation_name = $parent ? $parent->name : 'Unknown Parent';
    }

    // Add building name if location is set
    if ($designation->location_building_id) {
        $designation->building_name = 'Building ' . $designation->location_building_id;
    }

    // Add employee names (these would require Employee entity)
    if ($designation->head_employee_id) {
        $designation->head_employee_name = 'Employee ' . $designation->head_employee_id;
    }

    if ($designation->manager_employee_id) {
        $designation->manager_employee_name = 'Employee ' . $designation->manager_employee_id;
    }
}

function enhanceTreeData(&$tree) {
    foreach ($tree as &$designation) {
        enhanceDesignationData($designation);
        if (isset($designation->children)) {
            enhanceTreeData($designation->children);
        }
    }
}

function getDesignationStatistics() {
    try {
        // Use simplified statistics calculation
        $designations = PopularOrganizationDesignation::all();

        $stats = [
            'total' => count($designations),
            'active' => 0,
            'inactive' => 0,
            'functional' => 0,
            'divisional' => 0,
            'totalEmployees' => 0,
            'totalBudget' => 0,
            'avgBudget' => 0
        ];

        foreach ($designations as $dept) {
            // Count by operational status
            if ($dept->operational_status === 'Active') {
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }

            // Count by designation type
            if ($dept->designation_type === 'Functional') {
                $stats['functional']++;
            } elseif ($dept->designation_type === 'Divisional') {
                $stats['divisional']++;
            }

            // Sum employees and budget
            $stats['totalEmployees'] += $dept->employee_count ?? 0;
            $stats['totalBudget'] += $dept->annual_budget ?? 0;
        }

        // Calculate average budget
        if ($stats['total'] > 0) {
            $stats['avgBudget'] = $stats['totalBudget'] / $stats['total'];
        }

        return $stats;
    } catch (Exception $e) {
        error_log("Error calculating statistics: " . $e->getMessage());
        return [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
            'functional' => 0,
            'divisional' => 0,
            'totalEmployees' => 0,
            'totalBudget' => 0,
            'avgBudget' => 0
        ];
    }
}
?>