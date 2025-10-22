<?php
/**
 * API: Flow Status
 * Get status of a flow instance
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
    $flowInstanceId = $_GET['flow_instance_id'] ?? null;

    if (!$flowInstanceId) {
        throw new Exception('flow_instance_id is required');
    }

    // Get process status
    $status = ProcessEngine::getProcessStatus($flowInstanceId);

    if (!$status) {
        throw new Exception('Flow instance not found');
    }

    // Format response
    $response = [
        'success' => true,
        'flow' => [
            'id' => $status['flow']['id'],
            'reference_number' => $status['flow']['reference_number'],
            'status' => $status['flow']['status'],
            'started_at' => $status['flow']['started_at'],
            'completed_at' => $status['flow']['completed_at'],
            'entity_code' => $status['flow']['entity_code'],
            'entity_record_id' => $status['flow']['entity_record_id']
        ],
        'tasks' => [],
        'audit_log' => []
    ];

    foreach ($status['tasks'] as $task) {
        $response['tasks'][] = [
            'task_id' => $task['id'],
            'node_name' => $task['node_name'],
            'assigned_to' => ($task['first_name'] ?? '') . ' ' . ($task['last_name'] ?? ''),
            'status' => $task['status'],
            'created_at' => $task['created_at'],
            'completed_at' => $task['completed_at'],
            'completion_action' => $task['completion_action']
        ];
    }

    foreach ($status['audit_log'] as $audit) {
        $response['audit_log'][] = [
            'action' => $audit['action'],
            'actor' => ($audit['first_name'] ?? '') . ' ' . ($audit['last_name'] ?? ''),
            'created_at' => $audit['created_at'],
            'from_status' => $audit['from_status'],
            'to_status' => $audit['to_status'],
            'comments' => $audit['comments']
        ];
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
