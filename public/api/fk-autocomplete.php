<?php
/**
 * Foreign Key Autocomplete API
 * Returns matching records based on search query
 */

require_once __DIR__ . '/../../bootstrap.php';

header('Content-Type: application/json');

// Require authentication
if (!Auth::check()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    // Get parameters
    $entityCode = strtoupper($_GET['entity'] ?? '');
    $query = $_GET['query'] ?? '';

    if (empty($entityCode)) {
        throw new Exception('Entity code is required');
    }

    if (strlen($query) < 1) {
        echo json_encode(['results' => []]);
        exit;
    }

    // Get entity
    $entity = EntityManager::getEntity($entityCode);
    if (!$entity) {
        throw new Exception('Entity not found');
    }

    // Get attributes with is_label = 1
    $attributes = EntityManager::getAttributes($entity['id']);
    $labelFields = [];
    foreach ($attributes as $attr) {
        if (isset($attr['is_label']) && $attr['is_label'] == 1) {
            $labelFields[] = $attr['code'];
        }
    }

    // Fallback to name and code if no label fields
    if (empty($labelFields)) {
        $labelFields = ['name', 'code'];
    }

    // Use database-level search for better performance
    // Build WHERE clause for searching across label fields
    $db = Database::connection();
    $tableName = $entity['table_name'];

    // Build OR conditions for each label field
    $whereConditions = [];
    $queryParam = '%' . $query . '%';

    foreach ($labelFields as $field) {
        $whereConditions[] = "$field LIKE :query";
    }

    $whereClause = implode(' OR ', $whereConditions);

    // Execute search query
    $sql = "SELECT * FROM $tableName WHERE $whereClause ORDER BY " . $labelFields[0] . " COLLATE NOCASE LIMIT 10";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':query', $queryParam, PDO::PARAM_STR);
    $stmt->execute();

    $results = [];
    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Build display label
        $displayParts = [];
        foreach ($labelFields as $field) {
            if (isset($record[$field]) && !empty($record[$field])) {
                $displayParts[] = $record[$field];
            }
        }

        $displayLabel = !empty($displayParts)
            ? implode(' - ', $displayParts)
            : ($record['name'] ?? $record['code'] ?? substr($record['id'], 0, 8));

        $results[] = [
            'id' => $record['id'],
            'label' => $displayLabel
        ];

        // Limit to 5 results (extra safety)
        if (count($results) >= 5) {
            break;
        }
    }

    echo json_encode(['results' => $results]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
