<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/PopularEducationSubject.php';

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
        case 'search':
            handleSearch();
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
            'category' => $_GET['category'] ?? null,
            'level' => $_GET['level'] ?? null,
            'status' => $_GET['status'] ?? null,
            'is_core' => $_GET['is_core'] ?? null,
            'is_elective' => $_GET['is_elective'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Get subjects with error handling
        try {
            $subjects = PopularEducationSubject::all();
            if (!$subjects) {
                $subjects = [];
            }
        } catch (Exception $e) {
            error_log("Error loading subjects: " . $e->getMessage());
            $subjects = [];
        }

        // Apply filtering
        if (!empty($filters) && !empty($subjects)) {
            $subjects = array_filter($subjects, function($subject) use ($filters) {
                try {
                    if (isset($filters['category']) && $subject->category !== $filters['category']) {
                        return false;
                    }
                    if (isset($filters['level']) && $subject->level !== $filters['level']) {
                        return false;
                    }
                    if (isset($filters['status']) && $subject->status !== $filters['status']) {
                        return false;
                    }
                    if (isset($filters['is_core']) && $subject->is_core != $filters['is_core']) {
                        return false;
                    }
                    if (isset($filters['is_elective']) && $subject->is_elective != $filters['is_elective']) {
                        return false;
                    }
                    if (isset($filters['search'])) {
                        $search = strtolower($filters['search']);
                        return stripos($subject->name, $search) !== false ||
                               stripos($subject->code, $search) !== false ||
                               stripos($subject->description ?? '', $search) !== false;
                    }
                    return true;
                } catch (Exception $e) {
                    error_log("Error filtering subject: " . $e->getMessage());
                    return false;
                }
            });
        }

        // Get statistics
        $statistics = [
            'total' => count($subjects),
            'active' => count(array_filter($subjects, function($s) {
                return $s->status === 'active';
            })),
            'core' => count(array_filter($subjects, function($s) {
                return $s->is_core == 1;
            })),
            'elective' => count(array_filter($subjects, function($s) {
                return $s->is_elective == 1;
            })),
            'totalEnrollments' => array_sum(array_map(function($s) {
                return $s->enrollment_count ?? 0;
            }, $subjects))
        ];

        // Apply pagination
        $total = count($subjects);
        $subjects = array_slice($subjects, $offset, $limit);

        echo json_encode([
            'success' => true,
            'subjects' => array_map(function($s) { return $s->toArray(); }, $subjects),
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
            'message' => 'Subject ID is required'
        ]);
        return;
    }

    $subject = PopularEducationSubject::find($id);

    if (!$subject) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Subject not found'
        ]);
        return;
    }

    echo json_encode([
        'success' => true,
        'subject' => $subject->toArray()
    ]);
}

function handleCreate() {
    $data = [];

    // Get data from POST or JSON
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

    // Validate required fields
    if (empty($data['name']) || empty($data['code']) || empty($data['category']) || empty($data['level'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Name, code, category, and level are required'
        ]);
        return;
    }

    // Check for duplicate code
    $existing = PopularEducationSubject::findByCode($data['code']);
    if ($existing) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Subject code already exists'
        ]);
        return;
    }

    try {
        $subject = new PopularEducationSubject();

        // Use the addSubject method
        $prerequisites = isset($data['prerequisites']) ? json_decode($data['prerequisites'], true) : [];
        $success = $subject->addSubject(
            $data['name'],
            $data['code'],
            $data['description'] ?? '',
            $data['category'],
            $data['level'],
            $data['credits'] ?? null,
            $prerequisites
        );

        // Set additional fields
        if (isset($data['duration_hours'])) $subject->duration_hours = intval($data['duration_hours']);
        if (isset($data['difficulty_level'])) $subject->difficulty_level = $data['difficulty_level'];
        if (isset($data['language'])) $subject->language = $data['language'];
        if (isset($data['practical_component'])) $subject->practical_component = intval($data['practical_component']);
        if (isset($data['theory_component'])) $subject->theory_component = intval($data['theory_component']);
        if (isset($data['syllabus_url'])) $subject->syllabus_url = $data['syllabus_url'];
        if (isset($data['industry_relevance'])) $subject->industry_relevance = $data['industry_relevance'];

        // Set flags
        if (isset($data['is_core'])) $subject->is_core = intval($data['is_core']);
        if (isset($data['is_elective'])) $subject->is_elective = intval($data['is_elective']);
        if (isset($data['is_mandatory'])) $subject->is_mandatory = intval($data['is_mandatory']);
        if (isset($data['lab_required'])) $subject->lab_required = intval($data['lab_required']);
        if (isset($data['certification_available'])) $subject->certification_available = intval($data['certification_available']);
        if (isset($data['is_featured'])) $subject->is_featured = intval($data['is_featured']);
        if (isset($data['is_active'])) $subject->is_active = intval($data['is_active']);

        if ($success && $subject->save()) {
            echo json_encode([
                'success' => true,
                'message' => 'Subject created successfully',
                'subject' => $subject->toArray()
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create subject'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error creating subject: ' . $e->getMessage()
        ]);
    }
}

function handleUpdate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Subject ID is required'
        ]);
        return;
    }

    $subject = PopularEducationSubject::find($id);

    if (!$subject) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Subject not found'
        ]);
        return;
    }

    $data = [];
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }

    // Remove ID from update data
    unset($data['id']);

    try {
        // Update basic fields
        if (isset($data['name'])) $subject->name = $data['name'];
        if (isset($data['code'])) {
            // Check for duplicate code
            $existing = PopularEducationSubject::findByCode($data['code']);
            if ($existing && $existing->id != $subject->id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Subject code already exists'
                ]);
                return;
            }
            $subject->code = $data['code'];
        }
        if (isset($data['description'])) $subject->description = $data['description'];
        if (isset($data['category'])) $subject->category = $data['category'];
        if (isset($data['level'])) $subject->level = $data['level'];
        if (isset($data['credits'])) $subject->credits = intval($data['credits']);
        if (isset($data['duration_hours'])) $subject->duration_hours = intval($data['duration_hours']);
        if (isset($data['difficulty_level'])) $subject->difficulty_level = $data['difficulty_level'];
        if (isset($data['language'])) $subject->language = $data['language'];
        if (isset($data['practical_component'])) $subject->practical_component = intval($data['practical_component']);
        if (isset($data['theory_component'])) $subject->theory_component = intval($data['theory_component']);
        if (isset($data['syllabus_url'])) $subject->syllabus_url = $data['syllabus_url'];
        if (isset($data['industry_relevance'])) $subject->industry_relevance = $data['industry_relevance'];

        // Update flags
        if (isset($data['is_core'])) $subject->is_core = intval($data['is_core']);
        if (isset($data['is_elective'])) $subject->is_elective = intval($data['is_elective']);
        if (isset($data['is_mandatory'])) $subject->is_mandatory = intval($data['is_mandatory']);
        if (isset($data['lab_required'])) $subject->lab_required = intval($data['lab_required']);
        if (isset($data['certification_available'])) $subject->certification_available = intval($data['certification_available']);
        if (isset($data['is_featured'])) $subject->is_featured = intval($data['is_featured']);
        if (isset($data['is_active'])) $subject->is_active = intval($data['is_active']);

        // Update prerequisites if provided
        if (isset($data['prerequisites'])) {
            $prerequisites = is_array($data['prerequisites']) ? $data['prerequisites'] : json_decode($data['prerequisites'], true);
            $subject->setPrerequisites($prerequisites);
        }

        $subject->updated_at = date('Y-m-d H:i:s');

        if ($subject->save()) {
            echo json_encode([
                'success' => true,
                'message' => 'Subject updated successfully',
                'subject' => $subject->toArray()
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update subject'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error updating subject: ' . $e->getMessage()
        ]);
    }
}

function handleDelete() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Subject ID is required'
        ]);
        return;
    }

    $subject = PopularEducationSubject::find($id);

    if (!$subject) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Subject not found'
        ]);
        return;
    }

    if ($subject->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Subject deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete subject'
        ]);
    }
}

function handleSearch() {
    $keyword = $_GET['keyword'] ?? '';

    if (empty($keyword)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Search keyword is required'
        ]);
        return;
    }

    try {
        $subjects = PopularEducationSubject::searchSubject($keyword);

        echo json_encode([
            'success' => true,
            'subjects' => array_map(function($s) { return $s->toArray(); }, $subjects),
            'count' => count($subjects)
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error searching subjects: ' . $e->getMessage()
        ]);
    }
}

function handleStatistics() {
    try {
        $subjects = PopularEducationSubject::all();

        $stats = [
            'total' => count($subjects),
            'active' => count(PopularEducationSubject::findActive()),
            'core' => count(PopularEducationSubject::findCore()),
            'elective' => count(PopularEducationSubject::findElective()),
            'mandatory' => count(PopularEducationSubject::findMandatory()),
            'featured' => count(PopularEducationSubject::findFeatured()),
            'byCategory' => [],
            'byLevel' => [],
            'totalEnrollments' => 0,
            'averagePopularity' => 0
        ];

        // Count by category and level
        foreach ($subjects as $subject) {
            // Category
            if (!isset($stats['byCategory'][$subject->category])) {
                $stats['byCategory'][$subject->category] = 0;
            }
            $stats['byCategory'][$subject->category]++;

            // Level
            if (!isset($stats['byLevel'][$subject->level])) {
                $stats['byLevel'][$subject->level] = 0;
            }
            $stats['byLevel'][$subject->level]++;

            // Enrollments
            $stats['totalEnrollments'] += ($subject->enrollment_count ?? 0);
        }

        // Calculate average popularity
        if (count($subjects) > 0) {
            $totalPopularity = array_sum(array_map(function($s) {
                return intval($s->popularity_score ?? 0);
            }, $subjects));
            $stats['averagePopularity'] = round($totalPopularity / count($subjects), 2);
        }

        echo json_encode([
            'success' => true,
            'statistics' => $stats
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error getting statistics: ' . $e->getMessage()
        ]);
    }
}

function handleActivate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Subject ID is required'
        ]);
        return;
    }

    $subject = PopularEducationSubject::find($id);

    if (!$subject) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Subject not found'
        ]);
        return;
    }

    if ($subject->activate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Subject activated successfully',
            'subject' => $subject->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to activate subject'
        ]);
    }
}

function handleDeactivate() {
    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Subject ID is required'
        ]);
        return;
    }

    $subject = PopularEducationSubject::find($id);

    if (!$subject) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Subject not found'
        ]);
        return;
    }

    if ($subject->deactivate()) {
        echo json_encode([
            'success' => true,
            'message' => 'Subject deactivated successfully',
            'subject' => $subject->toArray()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to deactivate subject'
        ]);
    }
}
