<?php
require_once '../../entities/Person.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    $person = new Person();

    switch ($method) {
        case 'GET':
            if ($action === 'list' || empty($action)) {
                $limit = $_GET['limit'] ?? 100;
                $result = Person::all();

                echo json_encode([
                    'success' => true,
                    'persons' => $result
                ]);
            } elseif ($action === 'get') {
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    throw new Exception('ID is required');
                }

                $person->person_id = $id;
                $result = $person->getPerson();
                echo json_encode(['success' => true, 'person' => $result]);
            } else {
                throw new Exception('Invalid action');
            }
            break;

        default:
            throw new Exception('Method not allowed');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
