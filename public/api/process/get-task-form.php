<?php
/**
 * API: Get Task Form
 * Generate dynamic form for task completion based on entity attributes
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
    // Get task_instance_id from query parameter
    if (empty($_GET['task_instance_id'])) {
        throw new Exception('task_instance_id is required');
    }

    $taskInstanceId = $_GET['task_instance_id'];

    // Get the current user's person_id
    $user = Auth::user();
    if (empty($user['person_id'])) {
        throw new Exception('User does not have an associated person record');
    }
    $personId = $user['person_id'];

    // Load task instance
    $sql = "SELECT ti.*, tfi.entity_code, tfi.entity_record_id, tfi.organization_id, tfi.reference_number
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
        throw new Exception('You are not authorized to view this task');
    }

    // Load process node to get form_entities configuration
    $sql = "SELECT * FROM process_node WHERE id = ? AND deleted_at IS NULL";
    $node = Database::fetchOne($sql, [$task['node_id']]);

    if (!$node) {
        throw new Exception('Process node not found');
    }

    // Prepare entity codes list
    $entityCodes = [];

    // Add main entity from flow
    if (!empty($task['entity_code'])) {
        $entityCodes[] = $task['entity_code'];
    }

    // Add related entities from node configuration
    if (!empty($node['form_entities'])) {
        $formEntities = json_decode($node['form_entities'], true);
        if (is_array($formEntities)) {
            $entityCodes = array_merge($entityCodes, $formEntities);
        }
    }

    // Remove duplicates
    $entityCodes = array_unique($entityCodes);

    // If no entities configured, try to get entity from process graph
    if (empty($entityCodes)) {
        $sql = "SELECT ed.code FROM process_graph pg
                INNER JOIN task_flow_instance tfi ON tfi.graph_id = pg.id
                LEFT JOIN entity_definition ed ON pg.entity_id = ed.id
                WHERE tfi.id = ? AND pg.entity_id IS NOT NULL";
        $graphEntity = Database::fetchOne($sql, [$task['flow_instance_id']]);

        if ($graphEntity && !empty($graphEntity['code'])) {
            $entityCodes[] = $graphEntity['code'];
        }
    }

    if (empty($entityCodes)) {
        // No entities configured - return empty form sections
        echo json_encode([
            'success' => true,
            'task' => [
                'id' => $task['id'],
                'node_name' => $node['node_name'],
                'instructions' => $node['instructions'],
                'status' => $task['status'],
                'due_date' => $task['due_date'],
                'reference_number' => $task['reference_number'],
                'entity_code' => $task['entity_code'],
                'entity_record_id' => $task['entity_record_id']
            ],
            'form_sections' => [],
            'existing_data' => $existingData
        ]);
        exit;
    }

    // Parse existing completion_data
    $existingData = [];
    if (!empty($task['completion_data'])) {
        $existingData = json_decode($task['completion_data'], true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
    }

    // Load entity record data if available (for pre-population)
    $entityRecordData = null;
    if (!empty($task['entity_code']) && !empty($task['entity_record_id'])) {
        $entityRecordData = EntityManager::read($task['entity_code'], $task['entity_record_id']);
    }

    // Generate forms for each entity
    $formSections = [];
    foreach ($entityCodes as $entityCode) {
        try {
            $generator = new PageGenerator($entityCode);
            $entity = EntityManager::getEntity($entityCode);

            if (!$entity) {
                continue;
            }

            // Determine pre-populate data for this entity
            $recordData = null;
            if ($entityCode === $task['entity_code'] && $entityRecordData) {
                // Main entity - use loaded record data merged with existing completion data
                $recordData = array_merge($entityRecordData, $existingData);
            } else {
                // Related entity - use only completion data
                $recordData = $existingData;
            }

            // Generate form HTML (without full page wrapper)
            $formHtml = $generator->generateFormFields($recordData);

            $formSections[] = [
                'entity_code' => $entityCode,
                'entity_name' => $entity['name'],
                'entity_id' => $entity['id'],
                'form_html' => $formHtml,
                'attributes' => EntityManager::getAttributes($entity['id'])
            ];

        } catch (Exception $e) {
            // Log error but continue with other entities
            error_log("Error generating form for entity {$entityCode}: " . $e->getMessage());
        }
    }

    if (empty($formSections)) {
        throw new Exception('No forms could be generated');
    }

    // Return response
    echo json_encode([
        'success' => true,
        'task' => [
            'id' => $task['id'],
            'node_name' => $node['node_name'],
            'instructions' => $node['instructions'],
            'status' => $task['status'],
            'due_date' => $task['due_date'],
            'reference_number' => $task['reference_number'],
            'entity_code' => $task['entity_code'],
            'entity_record_id' => $task['entity_record_id']
        ],
        'form_sections' => $formSections,
        'existing_data' => $existingData
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
