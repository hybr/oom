<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\MetadataLoader;
use V4L\Core\Database;

Auth::requireLogin();

$currentUser = Auth::getCurrentUser();
$organizations = Auth::getUserOrganizations();
$isSuperAdmin = Auth::isSuperAdmin();

// Get all entities
$entities = MetadataLoader::loadEntities();

// Get all active processes grouped by category
$processes = Database::fetchAll(
    "SELECT * FROM process_graph
     WHERE is_active = 1 AND is_published = 1
     ORDER BY category, name"
);

// Group processes by category
$processCategories = [];
foreach ($processes as $process) {
    $category = $process['category'] ?? 'Other';
    if (!isset($processCategories[$category])) {
        $processCategories[$category] = [];
    }
    $processCategories[$category][] = $process;
}

$pageTitle = 'Dashboard - ' . APP_NAME;
ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">
                    <span>Entities</span>
                </h6>
                <ul class="nav flex-column">
                    <?php foreach ($entities as $entity): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="entity-list.php?entity=<?= e($entity['code']) ?>">
                            <i class="bi bi-table"></i> <?= e($entity['name']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($isSuperAdmin): ?>
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">
                    <span>Administration</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="admin-users.php">
                            <i class="bi bi-people"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-organizations.php">
                            <i class="bi bi-building"></i> Organizations
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3>Welcome back, <?= e($currentUser['first_name'] ?? $currentUser['username']) ?>!</h3>
                            <p class="mb-0">You have access to <?= count($organizations) ?> organization(s).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizations -->
            <?php if (!empty($organizations)): ?>
            <h3 class="mb-3">My Organizations</h3>
            <div class="row g-4 mb-4">
                <?php foreach ($organizations as $org): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= e($org['name']) ?></h5>
                            <?php if ($org['description']): ?>
                                <p class="card-text text-muted"><?= e(truncate($org['description'], 80)) ?></p>
                            <?php endif; ?>
                            <a href="organization.php?id=<?= e($org['id']) ?>" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Quick Access -->
            <h3 class="mb-3">Quick Access</h3>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-cart text-primary" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Marketplace</h5>
                            <a href="marketplace.php" class="btn btn-sm btn-outline-primary">Browse</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-briefcase text-success" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Job Vacancies</h5>
                            <a href="vacancies.php" class="btn btn-sm btn-outline-success">View Jobs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-person text-info" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">My Profile</h5>
                            <a href="profile.php" class="btn btn-sm btn-outline-info">Edit Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-gear text-secondary" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Settings</h5>
                            <a href="settings.php" class="btn btn-sm btn-outline-secondary">Configure</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Processes -->
            <?php if (!empty($processCategories)): ?>
            <h3 class="mb-3 mt-5">Business Processes</h3>
            <?php foreach ($processCategories as $categoryName => $categoryProcesses): ?>
                <div class="mb-4">
                    <h5 class="text-muted mb-3">
                        <i class="bi bi-diagram-3"></i>
                        <?= e(ucwords(str_replace('_', ' ', strtolower($categoryName)))) ?>
                    </h5>
                    <div class="row g-3">
                        <?php foreach ($categoryProcesses as $process): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-play-circle text-primary"></i>
                                        <?= e($process['name']) ?>
                                    </h6>
                                    <?php if ($process['description']): ?>
                                        <p class="card-text small text-muted mb-3">
                                            <?= e(truncate($process['description'], 80)) ?>
                                        </p>
                                    <?php endif; ?>
                                    <a href="process-execute.php?code=<?= e($process['code']) ?>"
                                       class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-arrow-right-circle"></i> Execute
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
.hover-shadow {
    transition: box-shadow 0.3s ease-in-out;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
