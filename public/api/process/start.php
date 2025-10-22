<?php
/**
 * API: Start Process
 * Start a new process instance
 */

require_once __DIR__ . '/../../../bootstrap.php';

header('Content-Type: application/json');

// Require authentication
if (!Auth::check()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['graph_code'])) {
        throw new Exception('graph_code is required');
    }

    if (empty($data['organization_id'])) {
        throw new Exception('organization_id is required');
    }

    $graphCode = $data['graph_code'];
    $entityCode = $data['entity_code'] ?? null;
    $entityRecordId = $data['entity_record_id'] ?? null;
    $organizationId = $data['organization_id'];
    $initialVariables = $data['variables'] ?? [];

    $userId = Auth::id();

    // Start the process
    $result = ProcessEngine::startProcess(
        $graphCode,
        $entityCode,
        $entityRecordId,
        $organizationId,
        $userId,
        $initialVariables
    );

    if (!$result['success']) {
        throw new Exception($result['error']);
    }

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Process started successfully',
        'flow_instance_id' => $result['flow_instance_id'],
        'reference_number' => $result['reference_number']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
