<?php
/**
 * API: Complete Task
 * Complete a task instance
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
    if (empty($data['task_instance_id'])) {
        throw new Exception('task_instance_id is required');
    }

    if (empty($data['completion_action'])) {
        throw new Exception('completion_action is required');
    }

    $taskInstanceId = $data['task_instance_id'];
    $completionAction = $data['completion_action'];
    $comments = $data['comments'] ?? null;
    $completionData = $data['completion_data'] ?? [];

    $userId = Auth::id();

    // Complete the task
    $result = TaskManager::completeTask(
        $taskInstanceId,
        $userId,
        $completionAction,
        $comments,
        $completionData
    );

    if (!$result['success']) {
        throw new Exception($result['error']);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Task completed successfully',
        'next_status' => $result['next_status'] ?? 'RUNNING'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
