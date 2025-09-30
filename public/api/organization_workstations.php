<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/OrganizationWorkstation.php';
require_once '../../entities/OrganizationBuilding.php';

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
        case 'assign':
            handleAssign();
            break;
        case 'unassign':
            handleUnassign();
            break;
        case 'maintenance':
            handleMaintenance();
            break;
        case 'statistics':
            handleStatistics();
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
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(100, intval($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;

    $filters = [
        'building_id' => $_GET['building_id'] ?? null,
        'floor_number' => $_GET['floor_number'] ?? null,
        'workstation_type' => $_GET['workstation_type'] ?? null,
        'occupancy_status' => $_GET['occupancy_status'] ?? null,
        'zone_area' => $_GET['zone_area'] ?? null,
        'search' => $_GET['search'] ?? null
    ];

    // Remove empty filters
    $filters = array_filter($filters, function($value) {
        return $value !== null && $value !== '';
    });

    $workstations = OrganizationWorkstation::searchWorkstations($filters);

    // Get statistics
    $statistics = getWorkstationStatistics($filters['building_id'] ?? null);

    // Apply pagination
    $total = count($workstations);
    $workstations = array_slice($workstations, $offset, $limit);

    // Enhance data with building names
    foreach ($workstations as &$workstation) {
        if ($workstation->building_id) {
            $building = OrganizationBuilding::find($workstation->building_id);
            $workstation->building_name = $building ? $building->name : 'Unknown Building';
        }
    }

    echo json_encode([
        'success' => true,
        'workstations' => array_map(function($w) { return $w->toArray(); }, $workstations),
        'statistics' => $statistics,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => ceil($total / $limit),
            'totalItems' => $total,
            'itemsPerPage' => $limit
        ]
    ]);
}

function handleGet() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation ID is required'
        ]);
        return;
    }

    $workstation = OrganizationWorkstation::find($id);

    if (!$workstation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation not found'
        ]);
        return;
    }

    // Enhance with building name
    if ($workstation->building_id) {
        $building = OrganizationBuilding::find($workstation->building_id);
        $workstation->building_name = $building ? $building->name : 'Unknown Building';
    }

    echo json_encode([
        'success' => true,
        'workstation' => $workstation->toArray()
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

    $workstation = new OrganizationWorkstation();
    $workstation->fill($data);

    // Validate the workstation
    $errors = $workstation->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    // Check for duplicate seat code
    $existing = OrganizationWorkstation::where('seat_code', '=', $workstation->seat_code);
    if (!empty($existing)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Seat code already exists'
        ]);
        return;
    }

    if ($workstation->beforeSave() && $workstation->save()) {
        echo json_encode([
            'success' => true,
            'message' => 'Workstation created successfully',
            'workstation' => $workstation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create workstation'
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation ID is required'
        ]);
        return;
    }

    $workstation = OrganizationWorkstation::find($id);

    if (!$workstation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation not found'
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

    $workstation->fill($data);

    // Validate the workstation
    $errors = $workstation->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($workstation->beforeSave() && $workstation->save()) {
        echo json_encode([
            'success' => true,
            'message' => 'Workstation updated successfully',
            'workstation' => $workstation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update workstation'
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation ID is required'
        ]);
        return;
    }

    $workstation = OrganizationWorkstation::find($id);

    if (!$workstation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation not found'
        ]);
        return;
    }

    if ($workstation->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Workstation deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete workstation'
        ]);
    }
}

function handleAssign() {
    $id = $_POST['id'] ?? null;
    $employeeId = $_POST['employee_id'] ?? null;

    if (!$id || !$employeeId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation ID and Employee ID are required'
        ]);
        return;
    }

    $workstation = OrganizationWorkstation::find($id);

    if (!$workstation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation not found'
        ]);
        return;
    }

    if ($workstation->assignToEmployee($employeeId)) {
        echo json_encode([
            'success' => true,
            'message' => 'Employee assigned to workstation successfully',
            'workstation' => $workstation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to assign employee to workstation'
        ]);
    }
}

function handleUnassign() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation ID is required'
        ]);
        return;
    }

    $workstation = OrganizationWorkstation::find($id);

    if (!$workstation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation not found'
        ]);
        return;
    }

    if ($workstation->unassignFromEmployee()) {
        echo json_encode([
            'success' => true,
            'message' => 'Employee unassigned from workstation successfully',
            'workstation' => $workstation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to unassign employee from workstation'
        ]);
    }
}

function handleMaintenance() {
    $id = $_POST['id'] ?? null;
    $mode = $_POST['mode'] ?? 'start'; // start or complete

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation ID is required'
        ]);
        return;
    }

    $workstation = OrganizationWorkstation::find($id);

    if (!$workstation) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Workstation not found'
        ]);
        return;
    }

    $success = false;
    $message = '';

    if ($mode === 'start') {
        $success = $workstation->setMaintenanceMode();
        $message = $success ? 'Workstation set to maintenance mode' : 'Failed to set maintenance mode';
    } else if ($mode === 'complete') {
        $success = $workstation->completeMaintenanceMode();
        $message = $success ? 'Maintenance completed successfully' : 'Failed to complete maintenance';
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid maintenance mode'
        ]);
        return;
    }

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => $message,
            'workstation' => $workstation->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }
}

function handleStatistics() {
    $buildingId = $_GET['building_id'] ?? null;
    $statistics = getWorkstationStatistics($buildingId);

    echo json_encode([
        'success' => true,
        'statistics' => $statistics
    ]);
}

function getWorkstationStatistics($buildingId = null) {
    $occupancyReport = OrganizationWorkstation::getOccupancyReport($buildingId);

    $stats = [
        'total' => 0,
        'available' => 0,
        'occupied' => 0,
        'reserved' => 0,
        'maintenance' => 0
    ];

    foreach ($occupancyReport as $row) {
        $status = strtolower(str_replace(' ', '_', $row['occupancy_status']));
        switch ($status) {
            case 'available':
                $stats['available'] = $row['count'];
                break;
            case 'occupied':
                $stats['occupied'] = $row['count'];
                break;
            case 'reserved':
                $stats['reserved'] = $row['count'];
                break;
            case 'under_maintenance':
                $stats['maintenance'] = $row['count'];
                break;
        }
        $stats['total'] += $row['count'];
    }

    return $stats;
}
?>