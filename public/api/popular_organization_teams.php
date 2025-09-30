<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/PopularOrganizationTeam.php';
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
        case 'assign_lead':
            handleAssignLead();
            break;
        case 'assign_scrum_master':
            handleAssignScrumMaster();
            break;
        case 'update_size':
            handleUpdateSize();
            break;
        case 'update_sprint':
            handleUpdateSprint();
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
            'department_id' => $_GET['department_id'] ?? null,
            'team_type' => $_GET['team_type'] ?? null,
            'function_category' => $_GET['function_category'] ?? null,
            'operational_status' => $_GET['operational_status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Get teams with simple error handling
        try {
            if (!empty($filters)) {
                $teams = PopularOrganizationTeam::searchTeams($filters);
            } else {
                $teams = PopularOrganizationTeam::all();
            }
            if (!$teams) {
                $teams = [];
            }
        } catch (Exception $e) {
            error_log("Error loading teams: " . $e->getMessage());
            $teams = [];
        }

        // Get basic statistics
        $statistics = [
            'total' => count($teams),
            'active' => count(array_filter($teams, function($t) {
                return $t->operational_status === 'Active';
            })),
            'functional' => count(array_filter($teams, function($t) {
                return $t->team_type === 'Functional';
            })),
            'totalMembers' => array_sum(array_map(function($t) {
                return $t->current_size ?? 0;
            }, $teams)),
            'avgTeamSize' => count($teams) > 0 ? array_sum(array_map(function($t) {
                return $t->current_size ?? 0;
            }, $teams)) / count($teams) : 0
        ];

        // Apply pagination
        $total = count($teams);
        $teams = array_slice($teams, $offset, $limit);

        // Enhance data with related information
        foreach ($teams as &$team) {
            try {
                // Add department name
                if ($team->popular_organization_department_id) {
                    try {
                        $department = PopularOrganizationDepartment::find($team->popular_organization_department_id);
                        $team->department_name = $department ? $department->name : 'Unknown Department';
                    } catch (Exception $e) {
                        $team->department_name = 'Department ' . $team->popular_organization_department_id;
                    }
                }

                // Add parent team name
                if ($team->parent_team_id) {
                    try {
                        $parent = PopularOrganizationTeam::find($team->parent_team_id);
                        $team->parent_team_name = $parent ? $parent->name : 'Unknown Parent';
                    } catch (Exception $e) {
                        $team->parent_team_name = 'Parent ' . $team->parent_team_id;
                    }
                }
            } catch (Exception $e) {
                error_log("Error enhancing team data for ID " . ($team->id ?? 'unknown') . ": " . $e->getMessage());
                // Continue with other teams
            }
        }

        echo json_encode([
            'success' => true,
            'teams' => array_map(function($t) { return $t->toArray(); }, $teams),
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
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    // Enhance with related data
    enhanceTeamData($team);

    echo json_encode([
        'success' => true,
        'team' => $team->toArray()
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

    $team = new PopularOrganizationTeam();
    $team->fill($data);

    // Validate the team
    $errors = $team->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    // Check for duplicate name within department
    try {
        $allTeams = PopularOrganizationTeam::all();
        foreach ($allTeams as $existingTeam) {
            if ($existingTeam->popular_organization_department_id == $team->popular_organization_department_id &&
                strtolower($existingTeam->name) === strtolower($team->name)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team name already exists in this department'
                ]);
                return;
            }
        }
    } catch (Exception $e) {
        error_log("Error checking for duplicate team names: " . $e->getMessage());
    }

    if ($team->beforeSave() && $team->save()) {
        enhanceTeamData($team);
        echo json_encode([
            'success' => true,
            'message' => 'Team created successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create team'
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
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

    $team->fill($data);

    // Validate the team
    $errors = $team->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($team->beforeSave() && $team->save()) {
        enhanceTeamData($team);
        echo json_encode([
            'success' => true,
            'message' => 'Team updated successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update team'
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    // Check if team has sub-teams
    $subTeams = $team->getSubTeams();
    if (!empty($subTeams)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Cannot delete team with sub-teams. Please reassign or delete sub-teams first.'
        ]);
        return;
    }

    if ($team->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Team deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete team'
        ]);
    }
}

function handleTree() {
    try {
        $departmentId = $_GET['department_id'] ?? null;

        // Simplified tree building
        $allTeams = $departmentId ?
            PopularOrganizationTeam::getTeamsByDepartment($departmentId) :
            PopularOrganizationTeam::all();

        $tree = [];
        $teamMap = [];

        // Create a map for quick lookup
        foreach ($allTeams as $team) {
            $teamMap[$team->id] = $team;
            $team->children = [];
        }

        // Build the tree structure
        foreach ($allTeams as $team) {
            if ($team->parent_team_id && isset($teamMap[$team->parent_team_id])) {
                $teamMap[$team->parent_team_id]->children[] = $team;
            } else {
                $tree[] = $team;
            }
        }

        echo json_encode([
            'success' => true,
            'tree' => array_map(function($t) { return $t->toArray(); }, $tree)
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
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    $hierarchy = $team->getTeamHierarchy();

    echo json_encode([
        'success' => true,
        'hierarchy' => array_map(function($t) { return $t->toArray(); }, $hierarchy)
    ]);
}

function handleStatistics() {
    $departmentId = $_GET['department_id'] ?? null;
    $statistics = getTeamStatistics($departmentId);

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
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->activate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Team activated successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to activate team'
        ]);
    }
}

function handleDeactivate() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->deactivate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Team deactivated successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to deactivate team'
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
            'message' => 'Team ID is required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->dissolve($date)) {
        echo json_encode([
            'success' => true,
            'message' => 'Team dissolved successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to dissolve team'
        ]);
    }
}

function handleAssignLead() {
    $id = $_POST['id'] ?? null;
    $employeeId = $_POST['employee_id'] ?? null;

    if (!$id || !$employeeId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID and Employee ID are required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->assignTeamLead($employeeId)) {
        echo json_encode([
            'success' => true,
            'message' => 'Team lead assigned successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to assign team lead'
        ]);
    }
}

function handleAssignScrumMaster() {
    $id = $_POST['id'] ?? null;
    $employeeId = $_POST['employee_id'] ?? null;

    if (!$id || !$employeeId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID and Employee ID are required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->assignScrumMaster($employeeId)) {
        echo json_encode([
            'success' => true,
            'message' => 'Scrum master assigned successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to assign scrum master'
        ]);
    }
}

function handleUpdateSize() {
    $id = $_POST['id'] ?? null;
    $size = $_POST['size'] ?? null;

    if (!$id || $size === null) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID and size are required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->setTeamSize($size)) {
        echo json_encode([
            'success' => true,
            'message' => 'Team size updated successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update team size'
        ]);
    }
}

function handleUpdateSprint() {
    $id = $_POST['id'] ?? null;
    $duration = $_POST['duration'] ?? null;

    if (!$id || !$duration) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Team ID and sprint duration are required'
        ]);
        return;
    }

    $team = PopularOrganizationTeam::find($id);

    if (!$team) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Team not found'
        ]);
        return;
    }

    if ($team->setSprintDuration($duration)) {
        echo json_encode([
            'success' => true,
            'message' => 'Sprint duration updated successfully',
            'team' => $team->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update sprint duration'
        ]);
    }
}

function enhanceTeamData(&$team) {
    // Add department name
    if ($team->popular_organization_department_id) {
        $department = PopularOrganizationDepartment::find($team->popular_organization_department_id);
        $team->department_name = $department ? $department->name : 'Unknown Department';
    }

    // Add parent team name
    if ($team->parent_team_id) {
        $parent = PopularOrganizationTeam::find($team->parent_team_id);
        $team->parent_team_name = $parent ? $parent->name : 'Unknown Parent';
    }

    // Add building name if location is set
    if ($team->location_building_id) {
        $team->building_name = 'Building ' . $team->location_building_id;
    }

    // Add employee names (these would require Employee entity)
    if ($team->team_lead_id) {
        $team->team_lead_name = 'Employee ' . $team->team_lead_id;
    }

    if ($team->scrum_master_id) {
        $team->scrum_master_name = 'Employee ' . $team->scrum_master_id;
    }

    if ($team->product_owner_id) {
        $team->product_owner_name = 'Employee ' . $team->product_owner_id;
    }

    if ($team->tech_lead_id) {
        $team->tech_lead_name = 'Employee ' . $team->tech_lead_id;
    }
}

function getTeamStatistics($departmentId = null) {
    try {
        // Use simplified statistics calculation
        $teams = $departmentId ?
            PopularOrganizationTeam::getTeamsByDepartment($departmentId) :
            PopularOrganizationTeam::all();

        $stats = [
            'total' => count($teams),
            'active' => 0,
            'inactive' => 0,
            'functional' => 0,
            'cross_functional' => 0,
            'scrum_teams' => 0,
            'totalMembers' => 0,
            'totalBudget' => 0,
            'avgTeamSize' => 0,
            'avgBudget' => 0
        ];

        foreach ($teams as $team) {
            // Count by operational status
            if ($team->operational_status === 'Active') {
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }

            // Count by team type
            if ($team->team_type === 'Functional') {
                $stats['functional']++;
            } elseif ($team->team_type === 'Cross-functional') {
                $stats['cross_functional']++;
            } elseif ($team->team_type === 'Scrum') {
                $stats['scrum_teams']++;
            }

            // Sum members and budget
            $stats['totalMembers'] += $team->current_size ?? 0;
            $stats['totalBudget'] += $team->annual_budget ?? 0;
        }

        // Calculate averages
        if ($stats['total'] > 0) {
            $stats['avgTeamSize'] = $stats['totalMembers'] / $stats['total'];
            $stats['avgBudget'] = $stats['totalBudget'] / $stats['total'];
        }

        return $stats;
    } catch (Exception $e) {
        error_log("Error calculating team statistics: " . $e->getMessage());
        return [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
            'functional' => 0,
            'cross_functional' => 0,
            'scrum_teams' => 0,
            'totalMembers' => 0,
            'totalBudget' => 0,
            'avgTeamSize' => 0,
            'avgBudget' => 0
        ];
    }
}
?>