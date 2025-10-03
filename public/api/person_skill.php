<?php
require_once '../../entities/PersonSkill.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    $personSkill = new PersonSkill();

    switch ($method) {
        case 'GET':
            if ($action === 'list' || empty($action)) {
                $person_id = $_GET['person_id'] ?? null;
                $proficiency_level = $_GET['proficiency_level'] ?? null;
                $is_certified = $_GET['is_certified'] ?? null;
                $page = $_GET['page'] ?? 1;
                $limit = $_GET['limit'] ?? 10;

                if ($person_id && $proficiency_level) {
                    $result = PersonSkill::listSkillsByProficiency($person_id, $proficiency_level);
                } elseif ($person_id) {
                    $result = PersonSkill::listSkillsByPerson($person_id);
                } else {
                    $result = PersonSkill::all();
                }

                // Apply additional filters
                if ($is_certified !== null) {
                    $result = array_filter($result, fn($s) => $s['is_certified'] == $is_certified);
                }

                // Calculate pagination
                $total = count($result);
                $offset = ($page - 1) * $limit;
                $paginatedResult = array_slice($result, $offset, $limit);

                echo json_encode([
                    'success' => true,
                    'skills' => $paginatedResult,
                    'statistics' => [
                        'total' => $total,
                        'certified_skills' => count(array_filter($result, fn($s) => $s['is_certified'] == 1)),
                        'expert_skills' => count(array_filter($result, fn($s) => in_array($s['proficiency_level'], ['Expert', 'Master']))),
                        'avg_proficiency' => $total > 0 ? array_sum(array_column($result, 'proficiency_percentage')) / $total : 0
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

                $result = $personSkill->getSkill($id);
                echo json_encode(['success' => true, 'skill' => $result]);
            } elseif ($action === 'certified') {
                $person_id = $_GET['person_id'] ?? null;
                if (!$person_id) {
                    throw new Exception('Person ID is required');
                }

                $result = PersonSkill::listCertifiedSkills($person_id);
                echo json_encode(['success' => true, 'skills' => $result]);
            } elseif ($action === 'primary') {
                $person_id = $_GET['person_id'] ?? null;
                if (!$person_id) {
                    throw new Exception('Person ID is required');
                }

                $result = PersonSkill::listPrimarySkills($person_id);
                echo json_encode(['success' => true, 'skills' => $result]);
            } elseif ($action === 'statistics') {
                $person_id = $_GET['person_id'] ?? null;
                if (!$person_id) {
                    throw new Exception('Person ID is required');
                }

                $result = PersonSkill::getSkillStatistics($person_id);
                echo json_encode(['success' => true, 'statistics' => $result]);
            } elseif ($action === 'expiring_certs') {
                $person_id = $_GET['person_id'] ?? null;
                $days = $_GET['days'] ?? 90;

                if (!$person_id) {
                    throw new Exception('Person ID is required');
                }

                $result = PersonSkill::getExpiringCertifications($person_id, $days);
                echo json_encode(['success' => true, 'certifications' => $result]);
            } else {
                throw new Exception('Invalid action');
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                if (empty($data['skill_id'])) {
                    throw new Exception('Skill ID is required');
                }

                $result = $personSkill->addSkill(
                    $data['person_id'],
                    $data['skill_id'],
                    $data['proficiency_level'] ?? 'Beginner',
                    $data['years_of_experience'] ?? 0,
                    $data['months_of_experience'] ?? 0,
                    $data['notes'] ?? null
                );

                // Update additional fields if provided
                if ($result) {
                    $personSkill->skill_id = $result;
                    $updates = [];
                    if (isset($data['skill_category'])) $updates['skill_category'] = $data['skill_category'];
                    if (isset($data['skill_type'])) $updates['skill_type'] = $data['skill_type'];
                    if (isset($data['last_used_date'])) $updates['last_used_date'] = $data['last_used_date'];
                    if (isset($data['frequency_of_use'])) $updates['frequency_of_use'] = $data['frequency_of_use'];
                    if (isset($data['context_of_use'])) $updates['context_of_use'] = $data['context_of_use'];
                    if (isset($data['self_rating'])) $updates['self_rating'] = $data['self_rating'];
                    if (isset($data['projects_count'])) $updates['projects_count'] = $data['projects_count'];
                    if (isset($data['training_hours'])) $updates['training_hours'] = $data['training_hours'];
                    if (isset($data['practice_hours'])) $updates['practice_hours'] = $data['practice_hours'];
                    if (isset($data['portfolio_url'])) $updates['portfolio_url'] = $data['portfolio_url'];
                    if (isset($data['github_repos'])) $updates['github_repos'] = $data['github_repos'];
                    if (isset($data['is_primary_skill'])) $updates['is_primary_skill'] = $data['is_primary_skill'] ? 1 : 0;
                    if (isset($data['is_core_skill'])) $updates['is_core_skill'] = $data['is_core_skill'] ? 1 : 0;
                    if (isset($data['willing_to_mentor'])) $updates['willing_to_mentor'] = $data['willing_to_mentor'] ? 1 : 0;
                    if (isset($data['is_active'])) $updates['is_active'] = $data['is_active'] ? 1 : 0;
                    if (isset($data['status'])) $updates['status'] = $data['status'];

                    // Certification fields
                    if (!empty($data['certification_name'])) {
                        $updates['certification_name'] = $data['certification_name'];
                        $updates['certification_provider'] = $data['certification_provider'] ?? null;
                        $updates['certification_number'] = $data['certification_number'] ?? null;
                        $updates['certification_date'] = $data['certification_date'] ?? null;
                        $updates['certification_expiry'] = $data['certification_expiry'] ?? null;
                        $updates['certification_url'] = $data['certification_url'] ?? null;
                        $updates['is_certified'] = 1;
                    }

                    if (!empty($updates)) {
                        $personSkill->updateSkill($result, $updates);
                    }
                }

                echo json_encode(['success' => true, 'id' => $result]);
            } elseif ($action === 'add_rating') {
                $id = $data['skill_id'] ?? null;
                $rating_type = $data['rating_type'] ?? null;
                $rating_value = $data['rating_value'] ?? null;

                if (!$id || !$rating_type || !$rating_value) {
                    throw new Exception('Skill ID, rating type, and rating value are required');
                }

                $skill = new PersonSkill();
                $skill->skill_id = $id;
                $result = $skill->addRating($rating_type, $rating_value, $data['rated_by'] ?? null);

                echo json_encode(['success' => $result]);
            } elseif ($action === 'add_endorsement') {
                $id = $data['skill_id'] ?? null;

                if (!$id) {
                    throw new Exception('Skill ID is required');
                }

                $skill = new PersonSkill();
                $skill->skill_id = $id;
                $result = $skill->addEndorsement();

                echo json_encode(['success' => $result]);
            } else {
                throw new Exception('Invalid action');
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['skill_id'] ?? null;

            if (!$id) {
                throw new Exception('Skill ID is required');
            }

            $updates = [];
            if (isset($data['person_id'])) $updates['person_id'] = $data['person_id'];
            if (isset($data['skill_id'])) $updates['skill_id'] = $data['skill_id'];
            if (isset($data['skill_name'])) $updates['skill_name'] = $data['skill_name'];
            if (isset($data['skill_category'])) $updates['skill_category'] = $data['skill_category'];
            if (isset($data['skill_type'])) $updates['skill_type'] = $data['skill_type'];
            if (isset($data['proficiency_level'])) $updates['proficiency_level'] = $data['proficiency_level'];
            if (isset($data['years_of_experience'])) $updates['years_of_experience'] = $data['years_of_experience'];
            if (isset($data['months_of_experience'])) $updates['months_of_experience'] = $data['months_of_experience'];
            if (isset($data['last_used_date'])) $updates['last_used_date'] = $data['last_used_date'];
            if (isset($data['frequency_of_use'])) $updates['frequency_of_use'] = $data['frequency_of_use'];
            if (isset($data['context_of_use'])) $updates['context_of_use'] = $data['context_of_use'];
            if (isset($data['self_rating'])) $updates['self_rating'] = $data['self_rating'];
            if (isset($data['projects_count'])) $updates['projects_count'] = $data['projects_count'];
            if (isset($data['training_hours'])) $updates['training_hours'] = $data['training_hours'];
            if (isset($data['practice_hours'])) $updates['practice_hours'] = $data['practice_hours'];
            if (isset($data['certification_name'])) $updates['certification_name'] = $data['certification_name'];
            if (isset($data['certification_provider'])) $updates['certification_provider'] = $data['certification_provider'];
            if (isset($data['certification_number'])) $updates['certification_number'] = $data['certification_number'];
            if (isset($data['certification_date'])) $updates['certification_date'] = $data['certification_date'];
            if (isset($data['certification_expiry'])) $updates['certification_expiry'] = $data['certification_expiry'];
            if (isset($data['certification_url'])) $updates['certification_url'] = $data['certification_url'];
            if (isset($data['portfolio_url'])) $updates['portfolio_url'] = $data['portfolio_url'];
            if (isset($data['github_repos'])) $updates['github_repos'] = $data['github_repos'];
            if (isset($data['notes'])) $updates['notes'] = $data['notes'];
            if (isset($data['is_primary_skill'])) $updates['is_primary_skill'] = $data['is_primary_skill'] ? 1 : 0;
            if (isset($data['is_core_skill'])) $updates['is_core_skill'] = $data['is_core_skill'] ? 1 : 0;
            if (isset($data['willing_to_mentor'])) $updates['willing_to_mentor'] = $data['willing_to_mentor'] ? 1 : 0;
            if (isset($data['is_active'])) $updates['is_active'] = $data['is_active'] ? 1 : 0;
            if (isset($data['status'])) $updates['status'] = $data['status'];

            $result = $personSkill->updateSkill($id, $updates);
            echo json_encode(['success' => $result]);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('ID is required');
            }

            $result = $personSkill->removeSkill($id);
            echo json_encode(['success' => $result]);
            break;

        default:
            throw new Exception('Method not allowed');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
