<?php
/**
 * Foreign Key Autocomplete API
 * Returns matching records based on search query
 */

require_once __DIR__ . '/../../bootstrap.php';

header('Content-Type: application/json');

/**
 * Format postal address as a single line with full location hierarchy
 */
function formatPostalAddress($record, $db) {
    $parts = [];

    // Street address
    if (!empty($record['first_street'])) {
        $parts[] = $record['first_street'];
    }
    if (!empty($record['second_street'])) {
        $parts[] = $record['second_street'];
    }

    // Area/Locality
    if (!empty($record['area'])) {
        $parts[] = $record['area'];
    }

    // City, District, State, Country (resolve from FK chain)
    if (!empty($record['city_id'])) {
        $cityStmt = $db->prepare("SELECT name, district_id, state_id, country_id FROM city WHERE id = :id");
        $cityStmt->execute(['id' => $record['city_id']]);
        $city = $cityStmt->fetch(PDO::FETCH_ASSOC);

        if ($city) {
            $parts[] = $city['name'];

            // District
            if (!empty($city['district_id'])) {
                $districtStmt = $db->prepare("SELECT name FROM district WHERE id = :id");
                $districtStmt->execute(['id' => $city['district_id']]);
                $district = $districtStmt->fetch(PDO::FETCH_ASSOC);
                if ($district) {
                    $parts[] = $district['name'];
                }
            }

            // State
            if (!empty($city['state_id'])) {
                $stateStmt = $db->prepare("SELECT name FROM state WHERE id = :id");
                $stateStmt->execute(['id' => $city['state_id']]);
                $state = $stateStmt->fetch(PDO::FETCH_ASSOC);
                if ($state) {
                    $parts[] = $state['name'];
                }
            }

            // Country
            if (!empty($city['country_id'])) {
                $countryStmt = $db->prepare("SELECT name FROM country WHERE id = :id");
                $countryStmt->execute(['id' => $city['country_id']]);
                $country = $countryStmt->fetch(PDO::FETCH_ASSOC);
                if ($country) {
                    $parts[] = $country['name'];
                }
            }
        }
    }

    // Postal code
    if (!empty($record['postal_code'])) {
        $parts[] = $record['postal_code'];
    }

    return !empty($parts) ? implode(', ', $parts) : 'Address #' . substr($record['id'], 0, 8);
}

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

    // Special handling for postal address search - search across multiple relevant fields
    if ($entityCode === 'POSTAL_ADDRESS') {
        $labelFields = ['first_street', 'second_street', 'area', 'postal_code', 'landmark'];
    }

    // Use database-level search for better performance
    // Build WHERE clause for searching across label fields
    $db = Database::connection();
    $tableName = $entity['table_name'];

    // Build OR conditions for each label field with unique placeholders
    $whereConditions = [];
    $queryParam = '%' . $query . '%';
    $params = [];

    foreach ($labelFields as $index => $field) {
        $placeholder = ":query{$index}";
        $whereConditions[] = "$field LIKE $placeholder COLLATE NOCASE";
        $params[$placeholder] = $queryParam;
    }

    $whereClause = implode(' OR ', $whereConditions);

    // Execute search query
    $sql = "SELECT * FROM $tableName WHERE ($whereClause) AND deleted_at IS NULL ORDER BY " . $labelFields[0] . " COLLATE NOCASE LIMIT 10";
    $stmt = $db->prepare($sql);

    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, PDO::PARAM_STR);
    }

    $stmt->execute();

    $results = [];
    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Build display label
        $displayLabel = '';

        // Special formatting for postal addresses
        if ($entityCode === 'POSTAL_ADDRESS') {
            $displayLabel = formatPostalAddress($record, $db);
        } else {
            $displayParts = [];
            foreach ($labelFields as $field) {
                if (isset($record[$field]) && !empty($record[$field])) {
                    $displayParts[] = $record[$field];
                }
            }

            $displayLabel = !empty($displayParts)
                ? implode(' - ', $displayParts)
                : ($record['name'] ?? $record['code'] ?? substr($record['id'], 0, 8));
        }

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
