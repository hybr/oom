<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Router::redirect('/');
}

// Get entity code from URL
$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');

// Validate CSRF token
if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Invalid security token.';
    Router::redirect('/entities/' . strtolower($entityCode) . '/create');
}

try {
    // Sanitize input data
    $data = [];
    foreach ($_POST as $key => $value) {
        if ($key !== 'csrf_token') {
            $data[$key] = is_string($value) ? Validator::sanitize($value) : $value;
        }
    }

    // Create record
    $id = EntityManager::create($entityCode, $data);

    $_SESSION['success'] = 'Record created successfully!';
    Router::redirect('/entities/' . strtolower($entityCode) . '/detail/' . $id);

} catch (Exception $e) {
    $_SESSION['error'] = 'Failed to create record: ' . $e->getMessage();
    Router::redirect('/entities/' . strtolower($entityCode) . '/create');
}
