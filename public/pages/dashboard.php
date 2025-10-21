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

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="/entities/continent/list" class="btn btn-outline-primary w-100">
                                <i class="bi bi-globe"></i> Manage Continents
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/entities/country/list" class="btn btn-outline-primary w-100">
                                <i class="bi bi-flag"></i> Manage Countries
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/entities/state/list" class="btn btn-outline-primary w-100">
                                <i class="bi bi-map"></i> Manage States
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/entities/city/list" class="btn btn-outline-primary w-100">
                                <i class="bi bi-building"></i> Manage Cities
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
