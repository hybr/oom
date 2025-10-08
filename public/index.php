<?php
require_once __DIR__ . '/../bootstrap.php';

$pageTitle = 'Home';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold">Welcome to V4L</h1>
            <p class="lead text-muted">Your Community, Your Marketplace.</p>
            <p class="fs-5">
                Connecting local organizations with local customers. Discover local products, services, and job opportunities all in one place.
            </p>
            <div class="d-grid gap-2 d-md-flex">
                <?php if (!auth()->check()): ?>
                    <a href="<?php echo url('pages/auth/signup.php'); ?>" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="<?php echo url('pages/auth/login.php'); ?>" class="btn btn-outline-secondary btn-lg">Login</a>
                <?php else: ?>
                    <a href="<?php echo url('pages/dashboard.php'); ?>" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    <a href="<?php echo url('pages/market/catalog.php'); ?>" class="btn btn-outline-secondary btn-lg">Browse Marketplace</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="display-1">🏪🤝👥</div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row g-4 py-5">
        <div class="col-md-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <div class="fs-1 mb-3">🛒</div>
                    <h5 class="card-title">Local Marketplace</h5>
                    <p class="card-text text-muted">
                        Discover products and services from local businesses in your community.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <div class="fs-1 mb-3">💼</div>
                    <h5 class="card-title">Job Opportunities</h5>
                    <p class="card-text text-muted">
                        Find local employment opportunities and connect with employers in your area.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <div class="fs-1 mb-3">🏢</div>
                    <h5 class="card-title">For Organizations</h5>
                    <p class="card-text text-muted">
                        Manage your organization, post vacancies, and sell products/services locally.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <?php
    $db = db();
    $stats = [
        'organizations' => $db->selectOne("SELECT COUNT(*) as count FROM organizations WHERE deleted_at IS NULL")['count'] ?? 0,
        'catalog_items' => $db->selectOne("SELECT COUNT(*) as count FROM catalog_items WHERE deleted_at IS NULL")['count'] ?? 0,
        'vacancies' => $db->selectOne("SELECT COUNT(*) as count FROM organization_vacancies WHERE deleted_at IS NULL AND status = 'Open'")['count'] ?? 0,
    ];
    ?>

    <div class="row g-4 py-5 border-top">
        <div class="col-md-4">
            <div class="stats-card">
                <h3><?php echo number_format($stats['organizations']); ?></h3>
                <p>Local Organizations</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h3><?php echo number_format($stats['catalog_items']); ?></h3>
                <p>Products & Services</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h3><?php echo number_format($stats['vacancies']); ?></h3>
                <p>Open Vacancies</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row py-5 bg-light rounded-3 border-top">
        <div class="col-12 text-center p-5">
            <h2 class="mb-4">Ready to support your local community?</h2>
            <?php if (!auth()->check()): ?>
                <a href="<?php echo url('pages/auth/signup.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Create Your Account
                </a>
            <?php else: ?>
                <a href="<?php echo url('pages/entities/organizations/create.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-building"></i> Register Your Organization
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
