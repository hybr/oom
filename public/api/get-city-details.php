<?php
/**
 * API endpoint to get city details with joined district, state, and country names
 * Used by geocoding to build complete address
 */

require_once __DIR__ . '/../../bootstrap.php';

// Set JSON response header
header('Content-Type: application/json');

// Get city_id from query parameter
$cityId = $_GET['city_id'] ?? '';

if (empty($cityId)) {
    http_response_code(400);
    echo json_encode(['error' => 'city_id parameter is required']);
    exit;
}

try {
    // Query to get city with joined district, state, and country names
    $sql = "
        SELECT
            c.id as city_id,
            c.name as city_name,
            c.code as city_code,
            d.id as district_id,
            d.name as district_name,
            d.code as district_code,
            s.id as state_id,
            s.name as state_name,
            s.code as state_code,
            co.id as country_id,
            co.name as country_name,
            co.code as country_code,
            co.iso_alpha2 as country_iso
        FROM city c
        LEFT JOIN district d ON c.district_id = d.id
        LEFT JOIN state s ON c.state_id = s.id
        LEFT JOIN country co ON c.country_id = co.id
        WHERE c.id = ?
        LIMIT 1
    ";

    $result = Database::fetchOne($sql, [$cityId]);

    if (!$result) {
        http_response_code(404);
        echo json_encode(['error' => 'City not found']);
        exit;
    }

    // Return the city details
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);

} catch (Exception $e) {
    error_log('Error fetching city details: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}
