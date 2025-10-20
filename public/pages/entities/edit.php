<?php
require_once __DIR__ . '/../../../bootstrap.php';
Auth::requireAuth();

$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');
$id = $pathParts[3] ?? '';

try {
    $generator = new PageGenerator($entityCode);
    $record = EntityManager::read($entityCode, $id);

    if (!$record) {
        throw new Exception("Record not found");
    }

    $entity = EntityManager::getEntity($entityCode);
    $pageTitle = 'Edit ' . $entity['name'];

    require_once __DIR__ . '/../../../includes/header.php';

    // Add geocoding meta tag for postal_address entity
    if ($entityCode === 'POSTAL_ADDRESS') {
        $apiKey = Config::get('geocoding.google_api_key', '');
        echo '<meta name="google-maps-api-key" content="' . htmlspecialchars($apiKey) . '">' . "\n";
    }

    echo $generator->generateForm($record, 'edit');
    require_once __DIR__ . '/../../../includes/footer.php';

} catch (Exception $e) {
    http_response_code(404);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
