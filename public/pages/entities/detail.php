<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

// Get entity code and ID from URL
$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');
$id = $pathParts[3] ?? '';

try {
    // Load entity and create page generator
    $generator = new PageGenerator($entityCode);

    // Fetch record
    $record = EntityManager::read($entityCode, $id);

    if (!$record) {
        throw new Exception("Record not found");
    }

    $entity = EntityManager::getEntity($entityCode);
    $pageTitle = $entity['name'] . ' Details';

    require_once __DIR__ . '/../../../includes/header.php';

    // Generate and display detail view
    echo $generator->generateDetailView($record);

    require_once __DIR__ . '/../../../includes/footer.php';

} catch (Exception $e) {
    http_response_code(404);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
