<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Database;

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$pageSize = 10;
$offset = ($page - 1) * $pageSize;

// Filters
$search = $_GET['search'] ?? '';
$organizationId = $_GET['organization'] ?? null;

// Build query
$whereClauses = ['ov.is_active = 1', "ov.application_deadline >= date('now')"];
$params = [];

// Search
if ($search) {
    $whereClauses[] = '(ov.title LIKE :search OR ov.description LIKE :search)';
    $params[':search'] = "%$search%";
}

// Filter by organization
if ($organizationId) {
    $whereClauses[] = 'ov.organization_id = :org_id';
    $params[':org_id'] = $organizationId;
}

$whereClause = 'WHERE ' . implode(' AND ', $whereClauses);

// Get total count
$countSql = "SELECT COUNT(*) as total FROM organization_vacancy ov $whereClause";
$totalResult = Database::fetchOne($countSql, $params);
$total = $totalResult['total'];
$totalPages = ceil($total / $pageSize);

// Get vacancies
$sql = "SELECT ov.*, COALESCE(o.short_name || ' ' || lt.name, o.short_name, 'Unknown') as organization_name
        FROM organization_vacancy ov
        LEFT JOIN organization o ON ov.organization_id = o.id
        LEFT JOIN popular_organization_legal_types lt ON o.legal_type_id = lt.id
        $whereClause
        ORDER BY ov.created_at DESC
        LIMIT $pageSize OFFSET $offset";
$vacancies = Database::fetchAll($sql, $params);

// Get organizations for filter
$organizations = Database::fetchAll(
    "SELECT DISTINCT o.*, COALESCE(o.short_name || ' ' || lt.name, o.short_name, 'Unknown') as name
     FROM organization o
     LEFT JOIN popular_organization_legal_types lt ON o.legal_type_id = lt.id
     INNER JOIN organization_vacancy ov ON o.id = ov.organization_id
     WHERE ov.is_active = 1 AND ov.application_deadline >= date('now')
     ORDER BY o.short_name"
);

$pageTitle = 'Job Vacancies - ' . APP_NAME;
ob_start();
?>

<div class="bg-primary text-white py-4">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-briefcase"></i> Job Vacancies</h1>
        <p class="mb-0">Find your next opportunity</p>
    </div>
</div>

<div class="container my-4">
    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" placeholder="Search jobs..."
                           value="<?= e($search) ?>">
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="organization">
                        <option value="">All Organizations</option>
                        <?php foreach ($organizations as $org): ?>
                            <option value="<?= e($org['id']) ?>"
                                    <?= $organizationId === $org['id'] ? 'selected' : '' ?>>
                                <?= e($org['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vacancies List -->
    <?php if (empty($vacancies)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="mt-3">No vacancies found</h3>
            <p class="text-muted">Check back later for new opportunities</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($vacancies as $vacancy): ?>
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <h4 class="card-title"><?= e($vacancy['title']) ?></h4>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-building"></i> <?= e($vacancy['organization_name']) ?>
                                    </p>
                                    <?php if ($vacancy['description']): ?>
                                        <p class="card-text"><?= e(truncate($vacancy['description'], 200)) ?></p>
                                    <?php endif; ?>
                                    <div class="mt-3">
                                        <span class="badge bg-info me-2">
                                            <i class="bi bi-calendar"></i>
                                            Posted: <?= e(formatDate($vacancy['created_at'], 'M d, Y')) ?>
                                        </span>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i>
                                            Closes: <?= e(formatDate($vacancy['application_deadline'], 'M d, Y')) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <a href="vacancy.php?id=<?= e($vacancy['id']) ?>" class="btn btn-primary">
                                        <i class="bi bi-arrow-right-circle"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= e($search) ?>&organization=<?= e($organizationId) ?>">
                            Previous
                        </a>
                    </li>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= e($search) ?>&organization=<?= e($organizationId) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= e($search) ?>&organization=<?= e($organizationId) ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
