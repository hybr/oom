<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Database;

// Get featured items from marketplace
$featuredItems = Database::fetchAll(
    "SELECT i.*, c.name as category_name
     FROM item i
     LEFT JOIN category c ON i.category_id = c.id
     WHERE i.is_active = 1
     ORDER BY i.created_at DESC
     LIMIT 6"
);

// Get recent vacancies
$recentVacancies = Database::fetchAll(
    "SELECT ov.*, COALESCE(o.short_name || ' ' || lt.name, o.short_name, 'Unknown') as organization_name
     FROM organization_vacancy ov
     LEFT JOIN organization o ON ov.organization_id = o.id
     LEFT JOIN popular_organization_legal_types lt ON o.legal_type_id = lt.id
     WHERE ov.is_active = 1 AND ov.application_deadline >= date('now')
     ORDER BY ov.created_at DESC
     LIMIT 4"
);

$pageTitle = APP_NAME . ' - Your Community, Your Marketplace';
ob_start();
?>

<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">
                    Welcome to V4L
                </h1>
                <p class="lead mb-4">
                    Your local marketplace connecting communities. Buy and sell goods, find services, and discover job opportunities in your area.
                </p>
                <div class="d-grid gap-2 d-md-flex">
                    <a href="marketplace.php" class="btn btn-light btn-lg">
                        <i class="bi bi-cart"></i> Browse Marketplace
                    </a>
                    <a href="vacancies.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-briefcase"></i> Find Jobs
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-shop" style="font-size: 10rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-cart-check text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4>Local Marketplace</h4>
                    <p class="text-muted">
                        Buy and sell products and services from local businesses and community members.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-briefcase text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h4>Job Opportunities</h4>
                    <p class="text-muted">
                        Find local job openings and apply directly to organizations in your community.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                    </div>
                    <h4>Community First</h4>
                    <p class="text-muted">
                        Support local businesses and build stronger community connections.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Items -->
<?php if (!empty($featuredItems)): ?>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-star"></i> Featured Items</h2>
        <a href="marketplace.php" class="btn btn-outline-primary">View All</a>
    </div>

    <div class="row g-4">
        <?php foreach ($featuredItems as $item): ?>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-img-top bg-light" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
                <div class="card-body p-2">
                    <h6 class="card-title text-truncate"><?= e($item['name']) ?></h6>
                    <?php if ($item['category_name']): ?>
                        <p class="card-text small text-muted mb-1"><?= e($item['category_name']) ?></p>
                    <?php endif; ?>
                    <a href="item.php?id=<?= e($item['id']) ?>" class="btn btn-sm btn-primary w-100">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Recent Vacancies -->
<?php if (!empty($recentVacancies)): ?>
<div class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-briefcase"></i> Recent Job Openings</h2>
            <a href="vacancies.php" class="btn btn-outline-primary">View All Jobs</a>
        </div>

        <div class="row g-4">
            <?php foreach ($recentVacancies as $vacancy): ?>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= e($vacancy['title']) ?></h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-building"></i> <?= e($vacancy['organization_name']) ?>
                        </p>
                        <?php if ($vacancy['description']): ?>
                            <p class="card-text"><?= e(truncate($vacancy['description'], 100)) ?></p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i>
                                Closes: <?= e(formatDate($vacancy['application_deadline'], 'M d, Y')) ?>
                            </small>
                            <a href="vacancy.php?id=<?= e($vacancy['id']) ?>" class="btn btn-sm btn-primary">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
