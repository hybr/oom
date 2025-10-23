<?php
/**
 * ProcessEngine - Core workflow execution engine
 *
 * Handles:
 * - Starting new process instances
 * - Transitioning between nodes
 * - Evaluating edge conditions
 * - Managing process state
 * - Handling parallel execution (fork/join)
 */

class ProcessEngine
{
    /**
     * Start a new process instance
     *
     * @param string $graphCode Process graph code
     * @param string|null $entityCode Entity this process operates on
     * @param string|null $entityRecordId Specific record being processed
     * @param string $organizationId Organization context
     * @param string $startedBy Person ID who started the process
     * @param array $initialVariables Initial flow variables
     * @return array ['success' => bool, 'flow_instance_id' => string, 'error' => string]
     */
    public static function startProcess($graphCode, $entityCode, $entityRecordId, $organizationId, $startedBy, $initialVariables = [])
    {
        try {
            Database::beginTransaction();

            // Get the active published process graph
            $sql = "SELECT * FROM process_graph
                    WHERE code = ? AND is_active = 1 AND is_published = 1
                    ORDER BY version_number DESC LIMIT 1";
            $graph = Database::fetchOne($sql, [$graphCode]);

            if (!$graph) {
                throw new Exception("Process graph not found or not published: {$graphCode}");
            }

            // Find the START node
            $sql = "SELECT * FROM process_node
                    WHERE graph_id = ? AND node_type = 'START' AND deleted_at IS NULL
                    LIMIT 1";
            $startNode = Database::fetchOne($sql, [$graph['id']]);

            if (!$startNode) {
                throw new Exception("No START node found in process graph: {$graphCode}");
            }

            // Generate reference number
            $referenceNumber = self::generateReferenceNumber($graphCode);

            // Create flow instance
            $flowInstanceId = Auth::generateUuid();
            $sql = "INSERT INTO task_flow_instance (
                        id, graph_id, graph_version, entity_code, entity_record_id,
                        organization_id, current_node_id, status, started_at, started_by,
                        flow_variables, reference_number
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'RUNNING', datetime('now'), ?, ?, ?)";

            Database::execute($sql, [
                $flowInstanceId,
                $graph['id'],
                $graph['version_number'],
                $entityCode,
                $entityRecordId,
                $organizationId,
                $startNode['id'],
                $startedBy,
                json_encode($initialVariables),
                $referenceNumber
            ]);

            // Log flow start
            self::logAudit($flowInstanceId, null, 'FLOW_START', $startedBy, null, 'RUNNING', null, $startNode['id']);

            // Move to next node from START
            $moveResult = self::moveToNextNode($flowInstanceId, $startNode['id'], $startedBy);

            if (!$moveResult['success']) {
                throw new Exception($moveResult['error']);
            }

            Database::commit();

            return [
                'success' => true,
                'flow_instance_id' => $flowInstanceId,
                'reference_number' => $referenceNumber
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
     * Move process to next node(s) from current node
     *
     * @param string $flowInstanceId Flow instance ID
     * @param string $currentNodeId Current node ID
     * @param string $actorId Person performing the action
     * @param array $taskData Data from completed task
     * @return array ['success' => bool, 'error' => string]
     */
    public static function moveToNextNode($flowInstanceId, $currentNodeId, $actorId, $taskData = [])
    {
        try {
            // Get flow instance
            $flowInstance = self::getFlowInstance($flowInstanceId);
            if (!$flowInstance) {
                throw new Exception("Flow instance not found: {$flowInstanceId}");
            }

            // Get current node
            $currentNode = self::getNode($currentNodeId);
            if (!$currentNode) {
                throw new Exception("Node not found: {$currentNodeId}");
            }

            // Get outgoing edges from current node
            $sql = "SELECT * FROM process_edge
                    WHERE from_node_id = ? AND deleted_at IS NULL
                    ORDER BY edge_order ASC";
            $edges = Database::fetchAll($sql, [$currentNodeId]);

            if (empty($edges) && $currentNode['node_type'] !== 'END') {
                throw new Exception("No outgoing edges found from node: {$currentNode['node_name']}");
            }

            // If END node, complete the process
            if ($currentNode['node_type'] === 'END') {
                return self::completeProcess($flowInstanceId, $actorId);
            }

            // Evaluate edges to find which path(s) to take
            $targetNodes = self::evaluateEdges($edges, $flowInstance, $taskData);

            if (empty($targetNodes)) {
                throw new Exception("No valid transition found from node: {$currentNode['node_name']}");
            }

            // Handle different node types
            if ($currentNode['node_type'] === 'FORK') {
                // FORK: Create tasks for all target nodes (parallel execution)
                return self::handleFork($flowInstanceId, $targetNodes, $actorId);
            } else {
                // Normal transition or DECISION: Single path
                $targetNode = $targetNodes[0];
                return self::transitionToNode($flowInstanceId, $currentNodeId, $targetNode, $actorId);
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Transition to a specific node
     * Note: This method does not manage transactions - it expects to be called within an existing transaction
     */
    private static function transitionToNode($flowInstanceId, $fromNodeId, $toNode, $actorId)
    {
        try {
            // Update flow instance current node
            $sql = "UPDATE task_flow_instance
                    SET current_node_id = ?, updated_at = datetime('now')
                    WHERE id = ?";
            Database::execute($sql, [$toNode['id'], $flowInstanceId]);

            // Log transition
            self::logAudit($flowInstanceId, null, 'FLOW_TRANSITION', $actorId, null, null, $fromNodeId, $toNode['id']);

            // If target is TASK node, create task instance
            if ($toNode['node_type'] === 'TASK') {
                $taskResult = TaskManager::createTask($flowInstanceId, $toNode['id'], $actorId);
                if (!$taskResult['success']) {
                    throw new Exception($taskResult['error']);
                }
            } elseif ($toNode['node_type'] === 'END') {
                // Auto-complete if END node
                return self::completeProcess($flowInstanceId, $actorId);
            } elseif (in_array($toNode['node_type'], ['DECISION', 'FORK', 'JOIN'])) {
                // Auto-transition through control nodes
                return self::moveToNextNode($flowInstanceId, $toNode['id'], $actorId);
            }

            return ['success' => true];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle FORK node - create multiple parallel tasks
     * Note: This method does not manage transactions - it expects to be called within an existing transaction
     */
    private static function handleFork($flowInstanceId, $targetNodes, $actorId)
    {
        try {
            foreach ($targetNodes as $targetNode) {
                if ($targetNode['node_type'] === 'TASK') {
                    $taskResult = TaskManager::createTask($flowInstanceId, $targetNode['id'], $actorId);
                    if (!$taskResult['success']) {
                        throw new Exception($taskResult['error']);
                    }
                }
            }

            // Update flow to first target node (for tracking)
            $sql = "UPDATE task_flow_instance
                    SET current_node_id = ?, updated_at = datetime('now')
                    WHERE id = ?";
            Database::execute($sql, [$targetNodes[0]['id'], $flowInstanceId]);

            return ['success' => true];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Evaluate edges to determine which path(s) to take
     *
     * @return array Array of target nodes
     */
    private static function evaluateEdges($edges, $flowInstance, $taskData)
    {
        $targetNodes = [];
        $flowVariables = json_decode($flowInstance['flow_variables'] ?? '{}', true);

        // Get entity record data if applicable
        $entityData = [];
        if ($flowInstance['entity_code'] && $flowInstance['entity_record_id']) {
            $entityData = EntityManager::read($flowInstance['entity_code'], $flowInstance['entity_record_id']) ?? [];
        }

        foreach ($edges as $edge) {
            // Get conditions for this edge
            $sql = "SELECT * FROM process_edge_condition
                    WHERE edge_id = ? AND deleted_at IS NULL
                    ORDER BY condition_order ASC";
            $conditions = Database::fetchAll($sql, [$edge['id']]);

            // If no conditions, or is default edge, evaluate to true
            if (empty($conditions)) {
                if ($edge['is_default'] == 1 && empty($targetNodes)) {
                    // Default edge only used if no other edge matched
                    $targetNode = self::getNode($edge['to_node_id']);
                    if ($targetNode) {
                        $targetNodes[] = $targetNode;
                    }
                } elseif (empty($conditions)) {
                    // No conditions = always true
                    $targetNode = self::getNode($edge['to_node_id']);
                    if ($targetNode) {
                        $targetNodes[] = $targetNode;
                    }
                }
                continue;
            }

            // Evaluate conditions
            $conditionMet = ConditionEvaluator::evaluate($conditions, $entityData, $flowVariables, $taskData);

            if ($conditionMet) {
                $targetNode = self::getNode($edge['to_node_id']);
                if ($targetNode) {
                    $targetNodes[] = $targetNode;
                }

                // For non-FORK nodes, return first matching edge
                $fromNode = self::getNode($edge['from_node_id']);
                if ($fromNode && $fromNode['node_type'] !== 'FORK') {
                    break;
                }
            }
        }

        return $targetNodes;
    }

    /**
     * Complete a process instance
     */
    private static function completeProcess($flowInstanceId, $actorId)
    {
        $sql = "UPDATE task_flow_instance
                SET status = 'COMPLETED', completed_at = datetime('now'), updated_at = datetime('now')
                WHERE id = ?";
        Database::execute($sql, [$flowInstanceId]);

        self::logAudit($flowInstanceId, null, 'FLOW_COMPLETE', $actorId, 'RUNNING', 'COMPLETED');

        return ['success' => true, 'status' => 'COMPLETED'];
    }

    /**
     * Cancel a process instance
     */
    public static function cancelProcess($flowInstanceId, $actorId, $reason = null)
    {
        try {
            Database::beginTransaction();

            // Cancel all pending tasks
            $sql = "UPDATE task_instance
                    SET status = 'CANCELLED', updated_at = datetime('now')
                    WHERE flow_instance_id = ? AND status IN ('PENDING', 'IN_PROGRESS')";
            Database::execute($sql, [$flowInstanceId]);

            // Update flow instance
            $sql = "UPDATE task_flow_instance
                    SET status = 'CANCELLED', updated_at = datetime('now')
                    WHERE id = ?";
            Database::execute($sql, [$flowInstanceId]);

            // Log cancellation
            self::logAudit($flowInstanceId, null, 'FLOW_CANCEL', $actorId, 'RUNNING', 'CANCELLED', null, null, $reason);

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
     * Get flow instance by ID
     */
    public static function getFlowInstance($flowInstanceId)
    {
        $sql = "SELECT * FROM task_flow_instance WHERE id = ? AND deleted_at IS NULL";
        return Database::fetchOne($sql, [$flowInstanceId]);
    }

    /**
     * Get node by ID
     */
    private static function getNode($nodeId)
    {
        $sql = "SELECT * FROM process_node WHERE id = ? AND deleted_at IS NULL";
        return Database::fetchOne($sql, [$nodeId]);
    }

    /**
     * Log audit event
     */
    private static function logAudit($flowInstanceId, $taskInstanceId, $action, $actorId, $fromStatus = null, $toStatus = null, $fromNodeId = null, $toNodeId = null, $comments = null)
    {
        $auditId = Auth::generateUuid();
        $sql = "INSERT INTO task_audit_log (
                    id, flow_instance_id, task_instance_id, action, actor_id,
                    from_status, to_status, from_node_id, to_node_id, comments
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        Database::execute($sql, [
            $auditId,
            $flowInstanceId,
            $taskInstanceId,
            $action,
            $actorId,
            $fromStatus,
            $toStatus,
            $fromNodeId,
            $toNodeId,
            $comments
        ]);
    }

    /**
     * Generate reference number for process instance
     */
    private static function generateReferenceNumber($graphCode)
    {
        $prefix = strtoupper(substr($graphCode, 0, 3));
        $timestamp = date('Ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Get process status with tasks
     */
    public static function getProcessStatus($flowInstanceId)
    {
        $flow = self::getFlowInstance($flowInstanceId);
        if (!$flow) {
            return null;
        }

        // Get all tasks for this flow
        $sql = "SELECT ti.*, pn.node_name, pn.node_type, p.first_name, p.last_name
                FROM task_instance ti
                JOIN process_node pn ON ti.node_id = pn.id
                LEFT JOIN person p ON ti.assigned_to = p.id
                WHERE ti.flow_instance_id = ? AND ti.deleted_at IS NULL
                ORDER BY ti.created_at ASC";
        $tasks = Database::fetchAll($sql, [$flowInstanceId]);

        // Get audit trail
        $sql = "SELECT tal.*, p.first_name, p.last_name
                FROM task_audit_log tal
                LEFT JOIN person p ON tal.actor_id = p.id
                WHERE tal.flow_instance_id = ?
                ORDER BY tal.created_at DESC
                LIMIT 50";
        $auditLog = Database::fetchAll($sql, [$flowInstanceId]);

        return [
            'flow' => $flow,
            'tasks' => $tasks,
            'audit_log' => $auditLog
        ];
    }
}
