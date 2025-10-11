<?php
require_once __DIR__ . '/bootstrap.php';

// Test the autocomplete search logic
$entityCode = 'POPULAR_SKILL';
$query = 'Car';

echo "Testing autocomplete for entity: $entityCode, query: $query\n";
echo str_repeat('-', 60) . "\n";

// Get entity
$entity = EntityManager::getEntity($entityCode);
if (!$entity) {
    die("Entity not found\n");
}

echo "Entity: {$entity['name']} (table: {$entity['table_name']})\n";

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

echo "Label fields: " . implode(', ', $labelFields) . "\n";
echo str_repeat('-', 60) . "\n";

// Use database-level search
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
echo "SQL: $sql\n";
echo "Param: $queryParam\n";
echo str_repeat('-', 60) . "\n";

$stmt = $db->prepare($sql);
$stmt->bindValue(':query', $queryParam, PDO::PARAM_STR);
$stmt->execute();

$results = [];
$count = 0;
while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count++;

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

    echo "$count. $displayLabel\n";

    // Limit to 5 results
    if (count($results) >= 5) {
        break;
    }
}

echo str_repeat('-', 60) . "\n";
echo "Total results: " . count($results) . "\n";
