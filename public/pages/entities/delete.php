<?php
require_once __DIR__ . '/../../../bootstrap.php';
Auth::requireAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? '';

if (!Auth::validateCsrfToken($input['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'error' => 'Invalid security token']);
    exit;
}

try {
    EntityManager::delete($entityCode, $id);
    echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
