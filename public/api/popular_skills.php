<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/PopularSkills.php';

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
            'skill_type' => $_GET['skill_type'] ?? null,
            'function_category' => $_GET['function_category'] ?? null,
            'operational_status' => $_GET['operational_status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Get skills with simple error handling
        try {
            $skills = PopularSkills::all();
            if (!$skills) {
                $skills = [];
            }
        } catch (Exception $e) {
            error_log("Error loading skills: " . $e->getMessage());
            $skills = [];
        }

        // Apply basic filtering only if we have skills
        if (!empty($filters) && !empty($skills)) {
            $skills = array_filter($skills, function($dept) use ($filters) {
                try {
                    if (isset($filters['skill_type']) && $dept->skill_type !== $filters['skill_type']) {
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
                    error_log("Error filtering skill: " . $e->getMessage());
                    return false;
                }
            });
        }

        // Get basic statistics
        $statistics = [
            'total' => count($skills),
            'active' => count(array_filter($skills, function($d) {
                return $d->operational_status === 'Active';
            })),
            'functional' => count(array_filter($skills, function($d) {
                return $d->skill_type === 'Functional';
            })),
            'totalEmployees' => array_sum(array_map(function($d) {
                return $d->employee_count ?? 0;
            }, $skills)),
            'avgBudget' => 0
        ];

        // Apply pagination
        $total = count($skills);
        $skills = array_slice($skills, $offset, $limit);

        // Enhance data with basic information - with better error handling
        foreach ($skills as &$skill) {
            try {
                // Add parent skill name
                if ($skill->parent_skill_id) {
                    try {
                        $parent = PopularSkills::find($skill->parent_skill_id);
                        $skill->parent_skill_name = $parent ? $parent->name : 'Unknown Parent';
                    } catch (Exception $e) {
                        $skill->parent_skill_name = 'Parent ' . $skill->parent_skill_id;
                    }
                }
            } catch (Exception $e) {
                error_log("Error enhancing skill data for ID " . ($skill->id ?? 'unknown') . ": " . $e->getMessage());
                // Continue with other skills
            }
        }

        echo json_encode([
            'success' => true,
            'skills' => array_map(function($d) { return $d->toArray(); }, $skills),
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
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
        ]);
        return;
    }

    // Enhance with related data
    enhanceSkillData($skill);

    echo json_encode([
        'success' => true,
        'skill' => $skill->toArray()
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

    $skill = new PopularSkills();
    $skill->fill($data);

    // Validate the skill
    $errors = $skill->validate();
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
        $allSkills = PopularSkills::all();
        foreach ($allSkills as $existingDept) {
            if (strtolower($existingDept->name) === strtolower($skill->name)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Skill name already exists'
                ]);
                return;
            }
        }
    } catch (Exception $e) {
        // Log error but continue - duplicate check is not critical for basic functionality
        error_log("Error checking for duplicate skill names: " . $e->getMessage());
    }

    if ($skill->beforeSave() && $skill->save()) {
        enhanceSkillData($skill);
        echo json_encode([
            'success' => true,
            'message' => 'Skill created successfully',
            'skill' => $skill->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create skill'
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
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

    $skill->fill($data);

    // Validate the skill
    $errors = $skill->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        return;
    }

    if ($skill->beforeSave() && $skill->save()) {
        enhanceSkillData($skill);
        echo json_encode([
            'success' => true,
            'message' => 'Skill updated successfully',
            'skill' => $skill->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update skill'
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
        ]);
        return;
    }

    // Check if skill has sub-skills
    $subSkills = $skill->getSubSkills();
    if (!empty($subSkills)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Cannot delete skill with sub-skills. Please reassign or delete sub-skills first.'
        ]);
        return;
    }

    if ($skill->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Skill deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete skill'
        ]);
    }
}

function handleTree() {
    try {
        // Simplified tree building
        $allSkills = PopularSkills::all();

        $tree = [];
        $skillMap = [];

        // Create a map for quick lookup
        foreach ($allSkills as $dept) {
            $skillMap[$dept->id] = $dept;
            $dept->children = [];
        }

        // Build the tree structure
        foreach ($allSkills as $dept) {
            if ($dept->parent_skill_id && isset($skillMap[$dept->parent_skill_id])) {
                $skillMap[$dept->parent_skill_id]->children[] = $dept;
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
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
        ]);
        return;
    }

    $hierarchy = $skill->getSkillHierarchy();

    echo json_encode([
        'success' => true,
        'hierarchy' => array_map(function($d) { return $d->toArray(); }, $hierarchy)
    ]);
}

function handleStatistics() {
    $statistics = getSkillStatistics();

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
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
        ]);
        return;
    }

    if ($skill->activate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Skill activated successfully',
            'skill' => $skill->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to activate skill'
        ]);
    }
}

function handleDeactivate() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
        ]);
        return;
    }

    if ($skill->deactivate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Skill deactivated successfully',
            'skill' => $skill->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to deactivate skill'
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
            'message' => 'Skill ID is required'
        ]);
        return;
    }

    $skill = PopularSkills::find($id);

    if (!$skill) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Skill not found'
        ]);
        return;
    }

    if ($skill->dissolve($date)) {
        echo json_encode([
            'success' => true,
            'message' => 'Skill dissolved successfully',
            'skill' => $skill->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to dissolve skill'
        ]);
    }
}

function enhanceSkillData(&$skill) {
    // Add parent skill name
    if ($skill->parent_skill_id) {
        $parent = PopularSkills::find($skill->parent_skill_id);
        $skill->parent_skill_name = $parent ? $parent->name : 'Unknown Parent';
    }

    // Add building name if location is set
    if ($skill->location_building_id) {
        $skill->building_name = 'Building ' . $skill->location_building_id;
    }

    // Add employee names (these would require Employee entity)
    if ($skill->head_employee_id) {
        $skill->head_employee_name = 'Employee ' . $skill->head_employee_id;
    }

    if ($skill->manager_employee_id) {
        $skill->manager_employee_name = 'Employee ' . $skill->manager_employee_id;
    }
}

function enhanceTreeData(&$tree) {
    foreach ($tree as &$skill) {
        enhanceSkillData($skill);
        if (isset($skill->children)) {
            enhanceTreeData($skill->children);
        }
    }
}

function getSkillStatistics() {
    try {
        // Use simplified statistics calculation
        $skills = PopularSkills::all();

        $stats = [
            'total' => count($skills),
            'active' => 0,
            'inactive' => 0,
            'functional' => 0,
            'divisional' => 0,
            'totalEmployees' => 0,
            'totalBudget' => 0,
            'avgBudget' => 0
        ];

        foreach ($skills as $dept) {
            // Count by operational status
            if ($dept->operational_status === 'Active') {
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }

            // Count by skill type
            if ($dept->skill_type === 'Functional') {
                $stats['functional']++;
            } elseif ($dept->skill_type === 'Divisional') {
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