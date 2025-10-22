<?php
/**
 * TaskManager - Manages task instances and assignments
 *
 * Handles:
 * - Creating task instances
 * - Assigning tasks to users based on positions
 * - Task completion and actions
 * - Task reassignment
 * - SLA tracking
 */

class TaskManager
{
    /**
     * Create a new task instance
     *
     * @param string $flowInstanceId Flow instance ID
     * @param string $nodeId Node ID for this task
     * @param string $createdBy Person ID who triggered task creation
     * @return array ['success' => bool, 'task_instance_id' => string, 'error' => string]
     */
    public static function createTask($flowInstanceId, $nodeId, $createdBy)
    {
        try {
            // Get node details
            $sql = "SELECT * FROM process_node WHERE id = ? AND deleted_at IS NULL";
            $node = Database::fetchOne($sql, [$nodeId]);

            if (!$node) {
                throw new Exception("Node not found: {$nodeId}");
            }

            if ($node['node_type'] !== 'TASK') {
                throw new Exception("Cannot create task for non-TASK node type: {$node['node_type']}");
            }

            // Get flow instance for organization context
            $sql = "SELECT * FROM task_flow_instance WHERE id = ? AND deleted_at IS NULL";
            $flowInstance = Database::fetchOne($sql, [$flowInstanceId]);

            if (!$flowInstance) {
                throw new Exception("Flow instance not found: {$flowInstanceId}");
            }

            // Resolve who should be assigned to this task
            $assignmentResult = PositionResolver::resolveAssignment(
                $node['position_id'],
                $node['permission_type_id'],
                $flowInstance['organization_id']
            );

            if (!$assignmentResult['success']) {
                throw new Exception("Could not resolve task assignment: " . $assignmentResult['error']);
            }

            // Calculate due date based on SLA
            $dueDate = null;
            if ($node['sla_hours']) {
                $dueDate = date('Y-m-d H:i:s', strtotime("+{$node['sla_hours']} hours"));
            }

            // Create task instance
            $taskInstanceId = Auth::generateUuid();
            $sql = "INSERT INTO task_instance (
                        id, flow_instance_id, node_id, assigned_to, assigned_at,
                        assigned_by, assignment_type, status, due_date
                    ) VALUES (?, ?, ?, ?, datetime('now'), ?, ?, 'PENDING', ?)";

            Database::execute($sql, [
                $taskInstanceId,
                $flowInstanceId,
                $nodeId,
                $assignmentResult['person_id'],
                $createdBy,
                $assignmentResult['assignment_type'],
                $dueDate
            ]);

            // Log task creation
            self::logTaskAudit($flowInstanceId, $taskInstanceId, 'TASK_CREATE', $createdBy, null, 'PENDING');

            // Send notification if configured
            if ($node['notify_on_assignment'] == 1) {
                self::sendTaskNotification($taskInstanceId, $assignmentResult['person_id'], 'ASSIGNED');
            }

            return [
                'success' => true,
                'task_instance_id' => $taskInstanceId,
                'assigned_to' => $assignmentResult['person_id']
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Complete a task
     *
     * @param string $taskInstanceId Task instance ID
     * @param string $completedBy Person ID completing the task
     * @param string $completionAction Action taken (APPROVE, REJECT, COMPLETE, etc.)
     * @param string|null $comments Completion comments
     * @param array $completionData Additional data from form
     * @return array ['success' => bool, 'error' => string]
     */
    public static function completeTask($taskInstanceId, $completedBy, $completionAction, $comments = null, $completionData = [])
    {
        try {
            Database::beginTransaction();

            // Get task instance
            $sql = "SELECT * FROM task_instance WHERE id = ? AND deleted_at IS NULL";
            $task = Database::fetchOne($sql, [$taskInstanceId]);

            if (!$task) {
                throw new Exception("Task not found: {$taskInstanceId}");
            }

            // Verify user is assigned to this task
            if ($task['assigned_to'] !== $completedBy) {
                // Check if user has override permission (admin/supervisor)
                if (!self::canOverrideTask($completedBy, $task['flow_instance_id'])) {
                    throw new Exception("User not authorized to complete this task");
                }
            }

            // Verify task is in valid state
            if (!in_array($task['status'], ['PENDING', 'IN_PROGRESS'])) {
                throw new Exception("Task cannot be completed from status: {$task['status']}");
            }

            // Update task instance
            $sql = "UPDATE task_instance
                    SET status = 'COMPLETED',
                        completed_at = datetime('now'),
                        completion_action = ?,
                        completion_comments = ?,
                        completion_data = ?,
                        updated_at = datetime('now')
                    WHERE id = ?";

            Database::execute($sql, [
                $completionAction,
                $comments,
                json_encode($completionData),
                $taskInstanceId
            ]);

            // Log task completion
            self::logTaskAudit(
                $task['flow_instance_id'],
                $taskInstanceId,
                'TASK_COMPLETE',
                $completedBy,
                $task['status'],
                'COMPLETED',
                $comments
            );

            // Move process to next node
            $moveResult = ProcessEngine::moveToNextNode(
                $task['flow_instance_id'],
                $task['node_id'],
                $completedBy,
                $completionData
            );

            if (!$moveResult['success']) {
                throw new Exception("Failed to move to next node: " . $moveResult['error']);
            }

            Database::commit();

            return [
                'success' => true,
                'next_status' => $moveResult['status'] ?? 'RUNNING'
            ];

        } catch (Exception $e) {
            Database::rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Start working on a task (change status from PENDING to IN_PROGRESS)
     */
    public static function startTask($taskInstanceId, $userId)
    {
        try {
            $sql = "SELECT * FROM task_instance WHERE id = ? AND deleted_at IS NULL";
            $task = Database::fetchOne($sql, [$taskInstanceId]);

            if (!$task) {
                throw new Exception("Task not found");
            }

            if ($task['assigned_to'] !== $userId) {
                throw new Exception("User not authorized to start this task");
            }

            if ($task['status'] !== 'PENDING') {
                throw new Exception("Task is not in PENDING status");
            }

            $sql = "UPDATE task_instance
                    SET status = 'IN_PROGRESS',
                        started_at = datetime('now'),
                        updated_at = datetime('now')
                    WHERE id = ?";
            Database::execute($sql, [$taskInstanceId]);

            self::logTaskAudit($task['flow_instance_id'], $taskInstanceId, 'TASK_START', $userId, 'PENDING', 'IN_PROGRESS');

            return ['success' => true];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reassign a task to a different user
     */
    public static function reassignTask($taskInstanceId, $newAssigneeId, $reassignedBy, $reason = null)
    {
        try {
            Database::beginTransaction();

            $sql = "SELECT * FROM task_instance WHERE id = ? AND deleted_at IS NULL";
            $task = Database::fetchOne($sql, [$taskInstanceId]);

            if (!$task) {
                throw new Exception("Task not found");
            }

            if (!in_array($task['status'], ['PENDING', 'IN_PROGRESS'])) {
                throw new Exception("Cannot reassign task with status: {$task['status']}");
            }

            $oldAssignee = $task['assigned_to'];

            // Update assignment
            $sql = "UPDATE task_instance
                    SET assigned_to = ?,
                        assigned_at = datetime('now'),
                        assigned_by = ?,
                        assignment_type = 'MANUAL',
                        attempts = attempts + 1,
                        updated_at = datetime('now')
                    WHERE id = ?";
            Database::execute($sql, [$newAssigneeId, $reassignedBy, $taskInstanceId]);

            // Log reassignment
            self::logTaskAudit(
                $task['flow_instance_id'],
                $taskInstanceId,
                'TASK_REASSIGN',
                $reassignedBy,
                null,
                null,
                "Reassigned from {$oldAssignee} to {$newAssigneeId}. Reason: {$reason}"
            );

            // Notify new assignee
            self::sendTaskNotification($taskInstanceId, $newAssigneeId, 'REASSIGNED');

            Database::commit();

            return ['success' => true];

        } catch (Exception $e) {
            Database::rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get tasks assigned to a user
     */
    public static function getMyTasks($userId, $status = null, $limit = 50)
    {
        $sql = "SELECT
                    ti.*,
                    pn.node_name,
                    pn.node_type,
                    pn.instructions,
                    tfi.reference_number,
                    tfi.entity_code,
                    tfi.entity_record_id,
                    pg.name as process_name
                FROM task_instance ti
                JOIN process_node pn ON ti.node_id = pn.id
                JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
                JOIN process_graph pg ON tfi.graph_id = pg.id
                WHERE ti.assigned_to = ?
                AND ti.deleted_at IS NULL";

        $params = [$userId];

        if ($status) {
            $sql .= " AND ti.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY
                    CASE ti.status
                        WHEN 'IN_PROGRESS' THEN 1
                        WHEN 'PENDING' THEN 2
                        ELSE 3
                    END,
                    ti.due_date ASC,
                    ti.created_at DESC
                LIMIT ?";
        $params[] = $limit;

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get overdue tasks
     */
    public static function getOverdueTasks($organizationId = null)
    {
        $sql = "SELECT
                    ti.*,
                    pn.node_name,
                    tfi.reference_number,
                    p.first_name,
                    p.last_name,
                    p.email
                FROM task_instance ti
                JOIN process_node pn ON ti.node_id = pn.id
                JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
                LEFT JOIN person p ON ti.assigned_to = p.id
                WHERE ti.status IN ('PENDING', 'IN_PROGRESS')
                AND ti.due_date < datetime('now')
                AND ti.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND tfi.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY ti.due_date ASC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Check if user can override task assignment (admin/supervisor)
     */
    private static function canOverrideTask($userId, $flowInstanceId)
    {
        // Get flow instance
        $sql = "SELECT organization_id FROM task_flow_instance WHERE id = ?";
        $flow = Database::fetchOne($sql, [$flowInstanceId]);

        if (!$flow) {
            return false;
        }

        // Check if user is organization admin
        $sql = "SELECT COUNT(*) as cnt FROM organization WHERE id = ? AND admin_id = ?";
        $result = Database::fetchOne($sql, [$flow['organization_id'], $userId]);

        return ($result['cnt'] > 0);
    }

    /**
     * Log task audit event
     */
    private static function logTaskAudit($flowInstanceId, $taskInstanceId, $action, $actorId, $fromStatus = null, $toStatus = null, $comments = null)
    {
        $auditId = Auth::generateUuid();
        $sql = "INSERT INTO task_audit_log (
                    id, flow_instance_id, task_instance_id, action, actor_id,
                    from_status, to_status, comments
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        Database::execute($sql, [
            $auditId,
            $flowInstanceId,
            $taskInstanceId,
            $action,
            $actorId,
            $fromStatus,
            $toStatus,
            $comments
        ]);
    }

    /**
     * Send task notification (placeholder - implement actual notification system)
     */
    private static function sendTaskNotification($taskInstanceId, $personId, $notificationType)
    {
        // TODO: Implement actual notification system (email, SMS, push notification)
        // For now, just log it
        error_log("Task notification: {$notificationType} for task {$taskInstanceId} to person {$personId}");
    }

    /**
     * Escalate overdue task
     */
    public static function escalateTask($taskInstanceId, $systemActorId)
    {
        try {
            Database::beginTransaction();

            $sql = "SELECT ti.*, pn.escalate_to_position_id
                    FROM task_instance ti
                    JOIN process_node pn ON ti.node_id = pn.id
                    WHERE ti.id = ?";
            $task = Database::fetchOne($sql, [$taskInstanceId]);

            if (!$task || !$task['escalate_to_position_id']) {
                throw new Exception("No escalation configured for this task");
            }

            // Get flow for organization context
            $sql = "SELECT organization_id FROM task_flow_instance WHERE id = ?";
            $flow = Database::fetchOne($sql, [$task['flow_instance_id']]);

            // Resolve escalation assignment
            $assignmentResult = PositionResolver::resolveAssignment(
                $task['escalate_to_position_id'],
                null,  // No specific permission required for escalation
                $flow['organization_id']
            );

            if (!$assignmentResult['success']) {
                throw new Exception("Could not resolve escalation assignment");
            }

            // Reassign to escalation target
            $sql = "UPDATE task_instance
                    SET assigned_to = ?,
                        assigned_at = datetime('now'),
                        assignment_type = 'ESCALATED',
                        status = 'ESCALATED',
                        updated_at = datetime('now')
                    WHERE id = ?";
            Database::execute($sql, [$assignmentResult['person_id'], $taskInstanceId]);

            // Log escalation
            self::logTaskAudit(
                $task['flow_instance_id'],
                $taskInstanceId,
                'TASK_ESCALATE',
                $systemActorId,
                $task['status'],
                'ESCALATED',
                "Escalated due to SLA breach"
            );

            Database::commit();

            return ['success' => true];

        } catch (Exception $e) {
            Database::rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
