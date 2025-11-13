<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\Database;

Auth::requireLogin();

$processCode = $_GET['code'] ?? null;

if (!$processCode) {
    header('Location: dashboard.php');
    exit;
}

// Get process details
$process = Database::fetchOne(
    "SELECT * FROM process_graph WHERE code = :code AND is_active = 1",
    [':code' => $processCode]
);

if (!$process) {
    header('Location: dashboard.php');
    exit;
}

// Get process nodes (steps)
$nodes = Database::fetchAll(
    "SELECT * FROM process_node
     WHERE graph_id = :graph_id
     ORDER BY display_x, display_y",
    [':graph_id' => $process['id']]
);

// Get process edges (transitions)
$edges = Database::fetchAll(
    "SELECT * FROM process_edge
     WHERE graph_id = :graph_id
     ORDER BY edge_order",
    [':graph_id' => $process['id']]
);

// Handle process execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entityId = $_POST['entity_id'] ?? null;
    $startData = $_POST['data'] ?? [];

    try {
        Database::beginTransaction();

        // Create task flow instance
        $instanceId = Database::generateUuid();
        $instanceCode = 'FLOW-' . date('Ymd-His') . '-' . substr($instanceId, 0, 8);

        // Get user's organization
        $currentUser = Auth::getCurrentUser();
        $userOrgs = Auth::getUserOrganizations();
        $organizationId = !empty($userOrgs) ? $userOrgs[0]['id'] : null;

        Database::insert('task_flow_instance', [
            'id' => $instanceId,
            'instance_code' => $instanceCode,
            'graph_id' => $process['id'],
            'organization_id' => $organizationId,
            'entity_id' => $entityId,
            'status' => 'IN_PROGRESS',
            'initiated_by' => Auth::getUserId(),
            'initiated_at' => date('Y-m-d H:i:s'),
            'started_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Find START node and create first task
        $startNode = null;
        foreach ($nodes as $node) {
            if ($node['node_type'] === 'START') {
                $startNode = $node;
                break;
            }
        }

        if ($startNode) {
            // Find next node after START
            $nextEdge = null;
            foreach ($edges as $edge) {
                if ($edge['from_node_id'] === $startNode['id']) {
                    $nextEdge = $edge;
                    break;
                }
            }

            if ($nextEdge) {
                // Find the target node
                $nextNode = null;
                foreach ($nodes as $node) {
                    if ($node['id'] === $nextEdge['to_node_id']) {
                        $nextNode = $node;
                        break;
                    }
                }

                if ($nextNode) {
                    // Create first task
                    $taskId = Database::generateUuid();
                    $taskCode = 'TASK-' . date('Ymd-His') . '-' . substr($taskId, 0, 8);

                    Database::insert('task_instance', [
                        'id' => $taskId,
                        'task_code' => $taskCode,
                        'flow_instance_id' => $instanceId,
                        'node_id' => $nextNode['id'],
                        'status' => 'PENDING',
                        'assigned_to' => Auth::getUserId(),
                        'assigned_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        Database::commit();

        $_SESSION['success_message'] = 'Process started successfully!';
        header('Location: process-instance.php?id=' . $instanceId);
        exit;

    } catch (Exception $e) {
        Database::rollback();
        $error = 'Failed to start process: ' . $e->getMessage();
    }
}

$pageTitle = $process['name'] . ' - ' . APP_NAME;
ob_start();
?>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($process['name']) ?></li>
                </ol>
            </nav>

            <!-- Process Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h2 class="card-title mb-2">
                                <i class="bi bi-diagram-3 text-primary"></i>
                                <?= e($process['name']) ?>
                            </h2>
                            <p class="text-muted mb-3"><?= e($process['description']) ?></p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-info">
                                    <?= e(ucwords(str_replace('_', ' ', strtolower($process['category'] ?? 'General')))) ?>
                                </span>
                                <span class="badge bg-success">
                                    Version <?= e($process['version_number']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Flow Visualization -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-flow-chart"></i> Process Steps</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($nodes as $node): ?>
                            <?php if ($node['node_type'] === 'TASK'): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="bi bi-circle text-primary"></i>
                                        <?= e($node['node_name']) ?>
                                    </h6>
                                    <?php if ($node['sla_hours']): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> <?= e($node['sla_hours']) ?>h SLA
                                    </small>
                                    <?php endif; ?>
                                </div>
                                <?php if ($node['description']): ?>
                                <p class="mb-1 small text-muted"><?= e($node['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Start Process Form -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-play-circle"></i> Start Process</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= e($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Process Context (Optional)</label>
                            <input type="text" class="form-control" name="entity_id"
                                   placeholder="Enter record ID if applicable">
                            <small class="form-text text-muted">
                                Leave empty to start a new instance without linking to existing records.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="data[notes]" rows="3"
                                      placeholder="Add any initial notes or context for this process"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-play-fill"></i> Start Process
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Process Statistics -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Process Information</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Total Steps:</dt>
                        <dd class="col-sm-8"><?= count(array_filter($nodes, fn($n) => $n['node_type'] === 'TASK')) ?></dd>

                        <dt class="col-sm-4">Process Code:</dt>
                        <dd class="col-sm-8"><code><?= e($process['code']) ?></code></dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <?php if ($process['is_published']): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
