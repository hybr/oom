<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../entities/Organization.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? 'list';

    switch ($action) {
        case 'list':
            handleList();
            break;
        case 'get':
            handleGet();
            break;
        default:
            handleList();
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

function handleList() {
    try {
        $organizations = Organization::all();

        echo json_encode([
            'success' => true,
            'organizations' => array_map(function($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'legal_name' => $org->legal_name ?? $org->name,
                    'status' => $org->status ?? 'Active'
                ];
            }, $organizations)
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to load organizations: ' . $e->getMessage()
        ]);
    }
}

function handleGet() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Organization ID is required'
        ]);
        return;
    }

    try {
        $organization = Organization::find($id);

        if (!$organization) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Organization not found'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'organization' => $organization->toArray()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to load organization: ' . $e->getMessage()
        ]);
    }
}
?>