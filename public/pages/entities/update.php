<?php
require_once __DIR__ . '/../../../bootstrap.php';
Auth::requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Router::redirect('/');
}

$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');

if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Invalid security token.';
    Router::redirect('/entities/' . strtolower($entityCode) . '/list');
}

try {
    $id = $_POST['id'] ?? '';

    $data = [];
    foreach ($_POST as $key => $value) {
        if ($key !== 'csrf_token' && $key !== 'id') {
            $data[$key] = is_string($value) ? Validator::sanitize($value) : $value;
        }
    }

    EntityManager::update($entityCode, $id, $data);

    $_SESSION['success'] = 'Record updated successfully!';
    Router::redirect('/entities/' . strtolower($entityCode) . '/detail/' . $id);

} catch (Exception $e) {
    $_SESSION['error'] = 'Failed to update record: ' . $e->getMessage();
    Router::redirect('/entities/' . strtolower($entityCode) . '/list');
}
