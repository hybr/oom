<?php
require_once __DIR__ . '/../../../bootstrap.php';

Auth::requireAuth();

// Get entity code from URL
$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$entityCode = strtoupper($pathParts[1] ?? '');

try {
    // Load entity and create page generator
    $generator = new PageGenerator($entityCode);

    // Get pagination parameters
    $page = (int) ($_GET['page'] ?? 1);
    $perPage = 25;
    $offset = ($page - 1) * $perPage;

    // Get filters from query string
    $filters = [];
    // Parse filter from query string (simple implementation)
    if (isset($_GET['filter'])) {
        $filterParts = explode('=', $_GET['filter']);
        if (count($filterParts) === 2) {
            $filters[$filterParts[0]] = $filterParts[1];
        }
    }

    // Fetch records
    $records = EntityManager::search($entityCode, $filters, $perPage, $offset);
    $totalCount = EntityManager::count($entityCode, $filters);

    $entity = EntityManager::getEntity($entityCode);
    $pageTitle = $entity['name'] . ' List';

    require_once __DIR__ . '/../../../includes/header.php';

    // Generate and display list view
    echo $generator->generateListView($records, $totalCount, $page, $perPage);

    require_once __DIR__ . '/../../../includes/footer.php';

} catch (Exception $e) {
    http_response_code(404);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
