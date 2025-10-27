<?php
/**
 * API: Save Task Data
 * Save task form data (partial or complete)
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

    if (empty($data['action'])) {
        throw new Exception('action is required (save or complete)');
    }

    $taskInstanceId = $data['task_instance_id'];
    $action = $data['action']; // 'save' or 'complete'
    $formData = $data['form_data'] ?? [];
    $comments = $data['comments'] ?? null;
    $completionAction = $data['completion_action'] ?? 'COMPLETE';

    // Get the current user's person_id
    $user = Auth::user();
    if (empty($user['person_id'])) {
        throw new Exception('User does not have an associated person record');
    }
    $personId = $user['person_id'];

    // Load task instance
    $sql = "SELECT ti.*, tfi.entity_code
            FROM task_instance ti
            INNER JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
            WHERE ti.id = ? AND ti.deleted_at IS NULL";

    $task = Database::fetchOne($sql, [$taskInstanceId]);

    if (!$task) {
        throw new Exception('Task not found');
    }

    // Verify user is assigned to this task or has override permission
    if ($task['assigned_to'] !== $personId) {
        // TODO: Check if user has override permission
        throw new Exception('You are not authorized to modify this task');
    }

    // Verify task is in valid state
    if (!in_array($task['status'], ['PENDING', 'IN_PROGRESS'])) {
        throw new Exception('Task cannot be modified from status: ' . $task['status']);
    }

    // Handle based on action
    if ($action === 'save') {
        // Partial save - no validation required
        $result = TaskManager::saveTaskData($taskInstanceId, $formData, $personId);

        if (!$result['success']) {
            throw new Exception($result['error']);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Task data saved successfully',
            'status' => $result['status']
        ]);

    } elseif ($action === 'complete') {
        // Complete - validate required fields first
        $validationErrors = self::validateTaskData($task['entity_code'], $formData);

        if (!empty($validationErrors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Validation failed',
                'validation_errors' => $validationErrors
            ]);
            exit;
        }

        // Complete the task
        $result = TaskManager::completeTask(
            $taskInstanceId,
            $personId,
            $completionAction,
            $comments,
            $formData
        );

        if (!$result['success']) {
            throw new Exception($result['error']);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Task completed successfully',
            'next_status' => $result['next_status'] ?? 'RUNNING'
        ]);

    } else {
        throw new Exception('Invalid action. Must be "save" or "complete"');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Validate task data against entity requirements
 *
 * @param string $entityCode Entity code
 * @param array $formData Form data to validate
 * @return array Validation errors (empty if valid)
 */
function validateTaskData($entityCode, $formData)
{
    $errors = [];

    try {
        // Get entity
        $entity = EntityManager::getEntity($entityCode);
        if (!$entity) {
            return ['entity' => 'Entity not found: ' . $entityCode];
        }

        // Get attributes
        $attributes = EntityManager::getAttributes($entity['id']);

        // Check required fields
        foreach ($attributes as $attr) {
            if ($attr['is_required'] == 1 && $attr['is_system'] != 1) {
                $fieldCode = $attr['code'];
                if (!isset($formData[$fieldCode]) || trim($formData[$fieldCode]) === '') {
                    $errors[$fieldCode] = $attr['name'] . ' is required';
                }
            }
        }

    } catch (Exception $e) {
        $errors['validation'] = 'Validation error: ' . $e->getMessage();
    }

    return $errors;
}
