<?php
require_once __DIR__ . '/../../bootstrap.php';

auth()->requireAuth();

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../../includes/header.php';

$db = db();
$userId = auth()->id();
$orgId = auth()->organizationId();

// Get user statistics
$userOrgs = count(auth()->getUserOrganizations());
$userApplications = $db->selectOne(
    "SELECT COUNT(*) as count FROM vacancy_applications WHERE applicant_id = ? AND deleted_at IS NULL",
    [$userId]
)['count'] ?? 0;

// If user has an active organization, get org statistics
$orgStats = null;
if ($orgId) {
    $orgStats = [
        'vacancies' => $db->selectOne(
            "SELECT COUNT(*) as count FROM organization_vacancies WHERE organization_id = ? AND deleted_at IS NULL",
            [$orgId]
        )['count'] ?? 0,
        'seller_items' => $db->selectOne(
            "SELECT COUNT(*) as count FROM seller_items WHERE organization_id = ? AND deleted_at IS NULL",
            [$orgId]
        )['count'] ?? 0,
        'applications' => $db->selectOne(
            "SELECT COUNT(*) as count FROM vacancy_applications va
             JOIN organization_vacancies ov ON va.vacancy_id = ov.id
             WHERE ov.organization_id = ? AND va.deleted_at IS NULL",
            [$orgId]
        )['count'] ?? 0,
    ];
}

?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="bi bi-speedometer2"></i> Dashboard
            </h1>

            <?php if ($orgId): ?>
                <?php
                $activeOrg = $db->selectOne(
                    "SELECT o.*, olc.name as legal_category_name
                     FROM organizations o
                     LEFT JOIN organization_legal_categories olc ON o.legal_category_id = olc.id
                     WHERE o.id = ?",
                    [$orgId]
                );
                ?>
                <div class="alert alert-info">
                    <i class="bi bi-building"></i> Active Organization:
                    <strong><?php echo escape($activeOrg['short_name']); ?></strong>
                    <?php if ($activeOrg['legal_category_name']): ?>
                        (<?php echo escape($activeOrg['legal_category_name']); ?>)
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Personal Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">🏢</div>
                    <h3><?php echo $userOrgs; ?></h3>
                    <p class="text-muted mb-0">My Organizations</p>
                    <a href="<?php echo url('pages/entities/organizations/list.php'); ?>" class="btn btn-sm btn-primary mt-2">View All</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">📝</div>
                    <h3><?php echo $userApplications; ?></h3>
                    <p class="text-muted mb-0">My Applications</p>
                    <a href="<?php echo url('pages/entities/vacancy_applications/list.php'); ?>" class="btn btn-sm btn-primary mt-2">View All</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">👤</div>
                    <h3>Profile</h3>
                    <p class="text-muted mb-0">My Information</p>
                    <a href="<?php echo url('pages/entities/persons/detail.php?id=' . $userId); ?>" class="btn btn-sm btn-primary mt-2">View Profile</a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($orgStats): ?>
        <!-- Organization Statistics -->
        <h3 class="mb-3 mt-5">Organization Overview</h3>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="fs-1 mb-2">💼</div>
                        <h3><?php echo $orgStats['vacancies']; ?></h3>
                        <p class="text-muted mb-0">Active Vacancies</p>
                        <a href="<?php echo url('pages/entities/organization_vacancies/list.php'); ?>" class="btn btn-sm btn-success mt-2">Manage</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="fs-1 mb-2">🛒</div>
                        <h3><?php echo $orgStats['seller_items']; ?></h3>
                        <p class="text-muted mb-0">Products/Services</p>
                        <a href="<?php echo url('pages/entities/seller_items/list.php'); ?>" class="btn btn-sm btn-success mt-2">Manage</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="fs-1 mb-2">📋</div>
                        <h3><?php echo $orgStats['applications']; ?></h3>
                        <p class="text-muted mb-0">Job Applications</p>
                        <a href="<?php echo url('pages/entities/vacancy_applications/list.php'); ?>" class="btn btn-sm btn-success mt-2">Review</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-3">Quick Actions</h3>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-building"></i> Organization</h5>
                    <p class="card-text">Manage your organizations and their operations.</p>
                    <a href="<?php echo url('pages/entities/organizations/create.php'); ?>" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Create Organization
                    </a>
                    <a href="<?php echo url('pages/entities/organizations/list.php'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-list"></i> View All
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-shop"></i> Marketplace</h5>
                    <p class="card-text">Browse local products, services, and job opportunities.</p>
                    <a href="<?php echo url('pages/market/catalog.php'); ?>" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Browse Catalog
                    </a>
                    <a href="<?php echo url('pages/market/jobs.php'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-briefcase"></i> Find Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
