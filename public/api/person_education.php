<?php
require_once '../../entities/PersonEducation.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    $personEducation = new PersonEducation();

    switch ($method) {
        case 'GET':
            if ($action === 'list' || empty($action)) {
                $person_id = $_GET['person_id'] ?? null;
                $level = $_GET['level'] ?? null;
                $page = $_GET['page'] ?? 1;
                $limit = $_GET['limit'] ?? 10;

                if ($person_id) {
                    $result = PersonEducation::listEducationByPerson($person_id);
                } elseif ($level) {
                    $result = PersonEducation::listEducationByLevel($person_id, $level);
                } else {
                    $result = PersonEducation::all();
                }

                // Calculate pagination
                $total = count($result);
                $offset = ($page - 1) * $limit;
                $paginatedResult = array_slice($result, $offset, $limit);

                echo json_encode([
                    'success' => true,
                    'educations' => $paginatedResult,
                    'statistics' => [
                        'total' => $total,
                        'verified' => count(array_filter($result, fn($e) => $e['is_verified'] == 1)),
                        'pending' => count(array_filter($result, fn($e) => $e['status'] == 'pending')),
                        'completed' => count(array_filter($result, fn($e) => $e['status'] == 'completed'))
                    ],
                    'pagination' => [
                        'current_page' => (int)$page,
                        'total_pages' => ceil($total / $limit),
                        'total_items' => $total
                    ]
                ]);
            } elseif ($action === 'get') {
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    throw new Exception('ID is required');
                }

                $result = $personEducation->getEducation($id);
                echo json_encode(['success' => true, 'data' => $result]);
            } elseif ($action === 'highest') {
                $person_id = $_GET['person_id'] ?? null;
                if (!$person_id) {
                    throw new Exception('Person ID is required');
                }

                $result = PersonEducation::getHighestEducation($person_id);
                echo json_encode(['success' => true, 'data' => $result]);
            } elseif ($action === 'gpa') {
                $person_id = $_GET['person_id'] ?? null;
                if (!$person_id) {
                    throw new Exception('Person ID is required');
                }

                $result = PersonEducation::calculateGPA($person_id);
                echo json_encode(['success' => true, 'gpa' => $result]);
            } elseif ($action === 'statistics') {
                $stats = $personEducation->getStatistics();
                echo json_encode(['success' => true, 'data' => $stats]);
            } else {
                throw new Exception('Invalid action');
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                $result = $personEducation->addEducation(
                    $data['person_id'],
                    $data['institution_id'] ?? null,
                    $data['level'],
                    $data['major_subjects'] ?? [],
                    $data['score_type'] ?? null,
                    $data['score_value'] ?? null,
                    $data['year_of_completion'] ?? null,
                    $data['duration'] ?? null,
                    $data['remarks'] ?? null
                );

                // Update additional fields if provided
                if ($result) {
                    $updates = [];
                    if (isset($data['degree_title'])) $updates['degree_title'] = $data['degree_title'];
                    if (isset($data['specialization'])) $updates['specialization'] = $data['specialization'];
                    if (isset($data['minor_subjects'])) $updates['minor_subjects'] = json_encode($data['minor_subjects']);
                    if (isset($data['thesis_title'])) $updates['thesis_title'] = $data['thesis_title'];
                    if (isset($data['research_area'])) $updates['research_area'] = $data['research_area'];
                    if (isset($data['honors'])) $updates['honors'] = $data['honors'];
                    if (isset($data['achievements'])) $updates['achievements'] = json_encode($data['achievements']);
                    if (isset($data['extracurricular'])) $updates['extracurricular'] = json_encode($data['extracurricular']);
                    if (isset($data['is_verified'])) $updates['is_verified'] = $data['is_verified'] ? 1 : 0;
                    if (isset($data['is_distinction'])) $updates['is_distinction'] = $data['is_distinction'] ? 1 : 0;
                    if (isset($data['is_scholarship'])) $updates['is_scholarship'] = $data['is_scholarship'] ? 1 : 0;
                    if (isset($data['is_online'])) $updates['is_online'] = $data['is_online'] ? 1 : 0;
                    if (isset($data['is_current'])) $updates['is_current'] = $data['is_current'] ? 1 : 0;
                    if (isset($data['status'])) $updates['status'] = $data['status'];

                    if (!empty($updates)) {
                        $personEducation->updateEducation($result, $updates);
                    }
                }

                echo json_encode(['success' => true, 'id' => $result]);
            } elseif ($action === 'add_subject') {
                $id = $data['education_id'] ?? null;
                $subject_id = $data['subject_id'] ?? null;

                if (!$id || !$subject_id) {
                    throw new Exception('Education ID and Subject ID are required');
                }

                $edu = new PersonEducation();
                $edu->education_id = $id;
                $result = $edu->addSubjectToEducation($subject_id);

                echo json_encode(['success' => $result]);
            } elseif ($action === 'remove_subject') {
                $id = $data['education_id'] ?? null;
                $subject_id = $data['subject_id'] ?? null;

                if (!$id || !$subject_id) {
                    throw new Exception('Education ID and Subject ID are required');
                }

                $edu = new PersonEducation();
                $edu->education_id = $id;
                $result = $edu->removeSubjectFromEducation($subject_id);

                echo json_encode(['success' => $result]);
            } else {
                throw new Exception('Invalid action');
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['education_id'] ?? null;

            if (!$id) {
                throw new Exception('Education ID is required');
            }

            $updates = [];
            if (isset($data['person_id'])) $updates['person_id'] = $data['person_id'];
            if (isset($data['institution_id'])) $updates['institution_id'] = $data['institution_id'];
            if (isset($data['level'])) $updates['level'] = $data['level'];
            if (isset($data['degree_title'])) $updates['degree_title'] = $data['degree_title'];
            if (isset($data['specialization'])) $updates['specialization'] = $data['specialization'];
            if (isset($data['major_subjects'])) $updates['major_subjects'] = json_encode($data['major_subjects']);
            if (isset($data['minor_subjects'])) $updates['minor_subjects'] = json_encode($data['minor_subjects']);
            if (isset($data['score_type'])) $updates['score_type'] = $data['score_type'];
            if (isset($data['score_value'])) $updates['score_value'] = $data['score_value'];
            if (isset($data['year_of_completion'])) $updates['year_of_completion'] = $data['year_of_completion'];
            if (isset($data['duration'])) $updates['duration'] = $data['duration'];
            if (isset($data['thesis_title'])) $updates['thesis_title'] = $data['thesis_title'];
            if (isset($data['research_area'])) $updates['research_area'] = $data['research_area'];
            if (isset($data['honors'])) $updates['honors'] = $data['honors'];
            if (isset($data['achievements'])) $updates['achievements'] = json_encode($data['achievements']);
            if (isset($data['extracurricular'])) $updates['extracurricular'] = json_encode($data['extracurricular']);
            if (isset($data['remarks'])) $updates['remarks'] = $data['remarks'];
            if (isset($data['is_verified'])) $updates['is_verified'] = $data['is_verified'] ? 1 : 0;
            if (isset($data['is_distinction'])) $updates['is_distinction'] = $data['is_distinction'] ? 1 : 0;
            if (isset($data['is_scholarship'])) $updates['is_scholarship'] = $data['is_scholarship'] ? 1 : 0;
            if (isset($data['is_online'])) $updates['is_online'] = $data['is_online'] ? 1 : 0;
            if (isset($data['is_current'])) $updates['is_current'] = $data['is_current'] ? 1 : 0;
            if (isset($data['status'])) $updates['status'] = $data['status'];

            $result = $personEducation->updateEducation($id, $updates);
            echo json_encode(['success' => $result]);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('ID is required');
            }

            $result = $personEducation->removeEducation($id);
            echo json_encode(['success' => $result]);
            break;

        default:
            throw new Exception('Method not allowed');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
