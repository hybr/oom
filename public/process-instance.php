<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\Database;

Auth::requireLogin();

$instanceId = $_GET['id'] ?? null;

if (!$instanceId) {
    header('Location: dashboard.php');
    exit;
}

// Get process instance
$instance = Database::fetchOne(
    "SELECT tfi.*, pg.name as process_name, pg.code as process_code, pg.description as process_description
     FROM task_flow_instance tfi
     JOIN process_graph pg ON tfi.graph_id = pg.id
     WHERE tfi.id = :id",
    [':id' => $instanceId]
);

if (!$instance) {
    header('Location: dashboard.php');
    exit;
}

// Get all tasks for this instance
$tasks = Database::fetchAll(
    "SELECT ti.*, pn.node_name, pn.node_code, pn.description as node_description, pn.node_type,
            p.first_name, p.last_name, p.username
     FROM task_instance ti
     JOIN process_node pn ON ti.node_id = pn.id
     LEFT JOIN person p ON ti.assigned_to = p.id
     WHERE ti.flow_instance_id = :instance_id
     ORDER BY ti.created_at ASC",
    [':instance_id' => $instanceId]
);

// Get audit log
$auditLog = Database::fetchAll(
    "SELECT tal.*, p.first_name, p.last_name, p.username
     FROM task_audit_log tal
     LEFT JOIN person p ON tal.performed_by = p.id
     WHERE tal.flow_instance_id = :instance_id
     ORDER BY tal.performed_at DESC",
    [':instance_id' => $instanceId]
);

$pageTitle = 'Process Instance - ' . APP_NAME;
ob_start();
?>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="process-execute.php?code=<?= e($instance['process_code']) ?>"><?= e($instance['process_name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Instance</li>
                </ol>
            </nav>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> <?= e($_SESSION['success_message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- Instance Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="bi bi-diagram-3 text-primary"></i>
                                <?= e($instance['process_name']) ?>
                            </h2>
                            <p class="text-muted mb-3"><?= e($instance['process_description']) ?></p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-<?= $instance['status'] === 'COMPLETED' ? 'success' : ($instance['status'] === 'IN_PROGRESS' ? 'info' : 'warning') ?>">
                                    <?= e($instance['status']) ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    Started <?= e(formatDate($instance['initiated_at'], 'M d, Y H:i')) ?>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Tasks</h5>
                    <span class="badge bg-primary"><?= count($tasks) ?> task(s)</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($tasks)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">No tasks yet</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($tasks as $task): ?>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-1">
                                                <i class="bi bi-<?= $task['status'] === 'COMPLETED' ? 'check-circle text-success' : ($task['status'] === 'IN_PROGRESS' ? 'hourglass-split text-warning' : 'circle text-secondary') ?>"></i>
                                                <?= e($task['node_name']) ?>
                                            </h6>
                                            <?php if ($task['node_description']): ?>
                                                <p class="mb-1 small text-muted"><?= e($task['node_description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if ($task['assigned_to']): ?>
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i>
                                                    <?= e($task['first_name'] . ' ' . $task['last_name']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <span class="badge bg-<?= $task['status'] === 'COMPLETED' ? 'success' : ($task['status'] === 'IN_PROGRESS' ? 'warning' : 'secondary') ?>">
                                                <?= e($task['status']) ?>
                                            </span>
                                            <?php if ($task['status'] === 'PENDING' && $task['assigned_to'] === Auth::getUserId()): ?>
                                                <a href="task-execute.php?id=<?= e($task['id']) ?>" class="btn btn-sm btn-primary ms-2">
                                                    <i class="bi bi-play"></i> Start
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Activity Log -->
            <?php if (!empty($auditLog)): ?>
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Activity Log</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($auditLog as $entry): ?>
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-circle-fill text-primary" style="font-size: 0.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <strong><?= e($entry['action']) ?></strong>
                                            <small class="text-muted">
                                                <?= e(formatDate($entry['performed_at'], 'M d, Y H:i')) ?>
                                            </small>
                                        </div>
                                        <?php if ($entry['comments']): ?>
                                            <p class="mb-1 text-muted"><?= e($entry['comments']) ?></p>
                                        <?php endif; ?>
                                        <?php if ($entry['username']): ?>
                                            <small class="text-muted">
                                                by <?= e($entry['first_name'] . ' ' . $entry['last_name']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
