<?php
/**
 * API: My Tasks
 * Get tasks assigned to current user
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
    // Get the current user's person_id (required for foreign key constraint)
    $user = Auth::user();
    if (empty($user['person_id'])) {
        throw new Exception('User does not have an associated person record');
    }
    $personId = $user['person_id'];

    $status = $_GET['status'] ?? null;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

    // Get tasks
    $tasks = TaskManager::getMyTasks($personId, $status, $limit);

    // Format response
    $formattedTasks = [];
    foreach ($tasks as $task) {
        $formattedTasks[] = [
            'task_id' => $task['id'],
            'node_name' => $task['node_name'],
            'process_name' => $task['process_name'],
            'reference_number' => $task['reference_number'],
            'status' => $task['status'],
            'created_at' => $task['created_at'],
            'due_date' => $task['due_date'],
            'instructions' => $task['instructions'],
            'entity_code' => $task['entity_code'],
            'entity_record_id' => $task['entity_record_id'],
            'flow_instance_id' => $task['flow_instance_id']
        ];
    }

    echo json_encode([
        'success' => true,
        'tasks' => $formattedTasks,
        'count' => count($formattedTasks)
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
