<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

// Get entity code from URL
$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');

try {
    // Load entity and create page generator
    $generator = new PageGenerator($entityCode);

    $entity = EntityManager::getEntity($entityCode);
    $pageTitle = 'Create ' . $entity['name'];

    require_once __DIR__ . '/../../../includes/header.php';

    // Generate and display form
    echo $generator->generateForm(null, 'create');

    require_once __DIR__ . '/../../../includes/footer.php';

} catch (Exception $e) {
    http_response_code(404);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
