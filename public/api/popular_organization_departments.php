<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/PopularOrganizationDepartment.php';

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
            'department_type' => $_GET['department_type'] ?? null,
            'function_category' => $_GET['function_category'] ?? null,
            'operational_status' => $_GET['operational_status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Get departments with simple error handling
        try {
            $departments = PopularOrganizationDepartment::all();
            if (!$departments) {
                $departments = [];
            }
        } catch (Exception $e) {
            error_log("Error loading departments: " . $e->getMessage());
            $departments = [];
        }

        // Apply basic filtering only if we have departments
        if (!empty($filters) && !empty($departments)) {
            $departments = array_filter($departments, function($dept) use ($filters) {
                try {
                    if (isset($filters['department_type']) && $dept->department_type !== $filters['department_type']) {
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
                    error_log("Error filtering department: " . $e->getMessage());
                    return false;
                }
            });
        }

        // Get basic statistics
        $statistics = [
            'total' => count($departments),
            'active' => count(array_filter($departments, function($d) {
                return $d->operational_status === 'Active';
            })),
            'functional' => count(array_filter($departments, function($d) {
                return $d->department_type === 'Functional';
            })),
            'totalEmployees' => array_sum(array_map(function($d) {
                return $d->employee_count ?? 0;
            }, $departments)),
            'avgBudget' => 0
        ];

        // Apply pagination
        $total = count($departments);
        $departments = array_slice($departments, $offset, $limit);

        // Enhance data with basic information - with better error handling
        foreach ($departments as &$department) {
            try {
                // Add parent department name
                if ($department->parent_department_id) {
                    try {
                        $parent = PopularOrganizationDepartment::find($department->parent_department_id);
                        $department->parent_department_name = $parent ? $parent->name : 'Unknown Parent';
                    } catch (Exception $e) {
                        $department->parent_department_name = 'Parent ' . $department->parent_department_id;
                    }
                }
            } catch (Exception $e) {
                error_log("Error enhancing department data for ID " . ($department->id ?? 'unknown') . ": " . $e->getMessage());
                // Continue with other departments
            }
        }

        echo json_encode([
            'success' => true,
            'departments' => array_map(function($d) { return $d->toArray(); }, $departments),
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
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
        return;
    }

    // Enhance with related data
    enhanceDepartmentData($department);

    echo json_encode([
        'success' => true,
        'department' => $department->toArray()
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

    $department = new PopularOrganizationDepartment();
    $department->fill($data);

    // Validate the department
    $errors = $department->validate();
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
        $allDepartments = PopularOrganizationDepartment::all();
        foreach ($allDepartments as $existingDept) {
            if (strtolower($existingDept->name) === strtolower($department->name)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Department name already exists'
                ]);
                return;
            }
        }
    } catch (Exception $e) {
        // Log error but continue - duplicate check is not critical for basic functionality
        error_log("Error checking for duplicate department names: " . $e->getMessage());
    }

    if ($department->beforeSave() && $department->save()) {
        enhanceDepartmentData($department);
        echo json_encode([
            'success' => true,
            'message' => 'Department created successfully',
            'department' => $department->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create department'
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
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

    $department->fill($data);

    // Validate the department
    $errors = $department->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($department->beforeSave() && $department->save()) {
        enhanceDepartmentData($department);
        echo json_encode([
            'success' => true,
            'message' => 'Department updated successfully',
            'department' => $department->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update department'
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
        return;
    }

    // Check if department has sub-departments
    $subDepartments = $department->getSubDepartments();
    if (!empty($subDepartments)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Cannot delete department with sub-departments. Please reassign or delete sub-departments first.'
        ]);
        return;
    }

    if ($department->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Department deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete department'
        ]);
    }
}

function handleTree() {
    try {
        // Simplified tree building
        $allDepartments = PopularOrganizationDepartment::all();

        $tree = [];
        $departmentMap = [];

        // Create a map for quick lookup
        foreach ($allDepartments as $dept) {
            $departmentMap[$dept->id] = $dept;
            $dept->children = [];
        }

        // Build the tree structure
        foreach ($allDepartments as $dept) {
            if ($dept->parent_department_id && isset($departmentMap[$dept->parent_department_id])) {
                $departmentMap[$dept->parent_department_id]->children[] = $dept;
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
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
        return;
    }

    $hierarchy = $department->getDepartmentHierarchy();

    echo json_encode([
        'success' => true,
        'hierarchy' => array_map(function($d) { return $d->toArray(); }, $hierarchy)
    ]);
}

function handleStatistics() {
    $statistics = getDepartmentStatistics();

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
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
        return;
    }

    if ($department->activate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Department activated successfully',
            'department' => $department->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to activate department'
        ]);
    }
}

function handleDeactivate() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
        return;
    }

    if ($department->deactivate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Department deactivated successfully',
            'department' => $department->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to deactivate department'
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
            'message' => 'Department ID is required'
        ]);
        return;
    }

    $department = PopularOrganizationDepartment::find($id);

    if (!$department) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
        return;
    }

    if ($department->dissolve($date)) {
        echo json_encode([
            'success' => true,
            'message' => 'Department dissolved successfully',
            'department' => $department->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to dissolve department'
        ]);
    }
}

function enhanceDepartmentData(&$department) {
    // Add parent department name
    if ($department->parent_department_id) {
        $parent = PopularOrganizationDepartment::find($department->parent_department_id);
        $department->parent_department_name = $parent ? $parent->name : 'Unknown Parent';
    }

    // Add building name if location is set
    if ($department->location_building_id) {
        $department->building_name = 'Building ' . $department->location_building_id;
    }

    // Add employee names (these would require Employee entity)
    if ($department->head_employee_id) {
        $department->head_employee_name = 'Employee ' . $department->head_employee_id;
    }

    if ($department->manager_employee_id) {
        $department->manager_employee_name = 'Employee ' . $department->manager_employee_id;
    }
}

function enhanceTreeData(&$tree) {
    foreach ($tree as &$department) {
        enhanceDepartmentData($department);
        if (isset($department->children)) {
            enhanceTreeData($department->children);
        }
    }
}

function getDepartmentStatistics() {
    try {
        // Use simplified statistics calculation
        $departments = PopularOrganizationDepartment::all();

        $stats = [
            'total' => count($departments),
            'active' => 0,
            'inactive' => 0,
            'functional' => 0,
            'divisional' => 0,
            'totalEmployees' => 0,
            'totalBudget' => 0,
            'avgBudget' => 0
        ];

        foreach ($departments as $dept) {
            // Count by operational status
            if ($dept->operational_status === 'Active') {
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }

            // Count by department type
            if ($dept->department_type === 'Functional') {
                $stats['functional']++;
            } elseif ($dept->department_type === 'Divisional') {
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