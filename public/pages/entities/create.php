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

    // Add geocoding meta tag for postal_address entity
    if ($entityCode === 'POSTAL_ADDRESS') {
        $apiKey = Config::get('geocoding.google_api_key', '');
        echo '<meta name="google-maps-api-key" content="' . htmlspecialchars($apiKey) . '">' . "\n";
    }

    // Check for pre-populated values from query parameters
    $prePopulatedRecord = null;
    if (!empty($_GET)) {
        $prePopulatedRecord = [];
        foreach ($_GET as $key => $value) {
            // Only include valid attribute codes (alphanumeric and underscore)
            if (preg_match('/^[a-z0-9_]+$/i', $key)) {
                $prePopulatedRecord[$key] = $value;
            }
        }
    }

    // Generate and display form with pre-populated values
    echo $generator->generateForm($prePopulatedRecord, 'create');

    require_once __DIR__ . '/../../../includes/footer.php';

} catch (Exception $e) {
    http_response_code(404);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
