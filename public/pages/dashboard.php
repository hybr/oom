<?php
require_once __DIR__ . '/../../bootstrap.php';

Auth::requireAuth();

$pageTitle = 'Dashboard';
$user = Auth::user();

// If user is false or null, redirect to login
if (!$user || !is_array($user)) {
    Router::redirect('/login');
    exit;
}

// Get statistics
$entities = EntityManager::loadEntities();

// Get published process graphs
$sql = "SELECT id, code, name, description, category, version_number
        FROM process_graph
        WHERE is_active = 1 AND is_published = 1
        ORDER BY category, name";
$processes = Database::fetchAll($sql);

// Get user's current organization from session
$userOrganizationId = Auth::currentOrganizationId();

// Get user's pending tasks count
$pendingTasksCount = 0;
if (!empty($user['person_id'])) {
    $sql = "SELECT COUNT(*) as cnt FROM task_instance
            WHERE assigned_to = ? AND status IN ('PENDING', 'IN_PROGRESS') AND deleted_at IS NULL";
    $result = Database::fetchOne($sql, [$user['person_id']]);
    $pendingTasksCount = $result['cnt'] ?? 0;
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">
        <i class="bi bi-speedometer2"></i> Dashboard
    </h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($user['username'] ?? $user['email'] ?? 'User'); ?>!</h5>
                </div>
                <div class="card-body">
                    <p class="lead">Welcome to your V4L dashboard. From here you can manage your entities and explore the platform.</p>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="bi bi-database"></i> Available Entities</h6>
                                    <h3 class="text-primary"><?php echo count($entities); ?></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="bi bi-clock-history"></i> Member Since</h6>
                                    <h5><?php
                                        if (!empty($user['created_at'])) {
                                            echo date('M d, Y', strtotime($user['created_at']));
                                        } else {
                                            echo 'N/A';
                                        }
                                    ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Flows -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Process Flows</h5>
                </div>
                <div class="card-body">
                    <?php if (count($processes) > 0): ?>
                        <div class="list-group">
                            <?php
                            $currentCategory = null;
                            foreach ($processes as $process):
                                if ($currentCategory !== $process['category']):
                                    if ($currentCategory !== null) echo '</div>';
                                    $currentCategory = $process['category'];
                                    echo '<h6 class="mt-3 mb-2 text-muted">' . htmlspecialchars($currentCategory ?: 'General') . '</h6>';
                                    echo '<div class="mb-3">';
                                endif;
                            ?>
                                <a href="/pages/process/process-viewer.php?graph_id=<?php echo urlencode($process['id']); ?>"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="bi bi-flow-chart"></i>
                                            <?php echo htmlspecialchars($process['name']); ?>
                                        </h6>
                                        <small class="text-muted">v<?php echo $process['version_number']; ?></small>
                                    </div>
                                    <?php if ($process['description']): ?>
                                        <p class="mb-1 small text-muted"><?php echo htmlspecialchars($process['description']); ?></p>
                                    <?php endif; ?>
                                    <small class="text-primary">
                                        <i class="bi bi-arrow-right-circle"></i> View Diagram & Start Process
                                    </small>
                                </a>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> No process flows available.
                            <a href="/entities/process_graph/list">Create a process</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="/pages/process/my-tasks.php" class="btn btn-outline-success w-100">
                                <i class="bi bi-list-task"></i> My Tasks
                                <?php if ($pendingTasksCount > 0): ?>
                                    <span class="badge bg-danger"><?php echo $pendingTasksCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/entities/process_graph/list" class="btn btn-outline-primary w-100">
                                <i class="bi bi-diagram-3"></i> Manage Processes
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/entities/continent/list" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-globe"></i> Manage Continents
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/entities/organization/list" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-building"></i> Manage Organizations
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list"></i> All Entities</h5>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($entities as $entity): ?>
                            <li class="list-group-item">
                                <a href="/entities/<?php echo strtolower($entity['code']); ?>/list" class="text-decoration-none">
                                    <i class="bi bi-folder"></i> <?php echo htmlspecialchars($entity['name']); ?>
                                </a>
                                <br>
                                <small class="text-muted"><?php echo htmlspecialchars($entity['description'] ?? ''); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> System Info</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Version:</dt>
                        <dd class="col-sm-6">1.0</dd>

                        <dt class="col-sm-6">Environment:</dt>
                        <dd class="col-sm-6"><?php echo Config::get('app.env'); ?></dd>

                        <dt class="col-sm-6">PHP Version:</dt>
                        <dd class="col-sm-6"><?php echo PHP_VERSION; ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
