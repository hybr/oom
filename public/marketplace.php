<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Database;

// Get subdomain if present
$subdomain = null;
$host = $_SERVER['HTTP_HOST'];
$parts = explode('.', $host);
if (count($parts) > 2 && $parts[0] !== 'www') {
    $subdomain = $parts[0];
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$pageSize = 24; // 24 items per page for grid layout
$offset = ($page - 1) * $pageSize;

// Filters
$categoryId = $_GET['category'] ?? null;
$search = $_GET['search'] ?? '';

// Build query
$whereClauses = ['i.is_active = 1'];
$params = [];

// Filter by organization subdomain
if ($subdomain) {
    $org = Database::fetchOne(
        "SELECT id FROM organization WHERE subdomain = :subdomain",
        [':subdomain' => $subdomain]
    );
    if ($org) {
        $whereClauses[] = 'i.organization_id = :org_id';
        $params[':org_id'] = $org['id'];
    }
}

// Filter by category
if ($categoryId) {
    $whereClauses[] = 'i.category_id = :category_id';
    $params[':category_id'] = $categoryId;
}

// Search
if ($search) {
    $whereClauses[] = '(i.name LIKE :search OR i.description LIKE :search)';
    $params[':search'] = "%$search%";
}

$whereClause = 'WHERE ' . implode(' AND ', $whereClauses);

// Get total count
$countSql = "SELECT COUNT(*) as total FROM item i $whereClause";
$totalResult = Database::fetchOne($countSql, $params);
$total = $totalResult['total'];
$totalPages = ceil($total / $pageSize);

// Get items
$sql = "SELECT i.*, c.name as category_name, COALESCE(o.short_name || ' ' || lt.name, o.short_name, 'Unknown') as organization_name
        FROM item i
        LEFT JOIN category c ON i.category_id = c.id
        LEFT JOIN organization o ON i.organization_id = o.id
        LEFT JOIN popular_organization_legal_types lt ON o.legal_type_id = lt.id
        $whereClause
        ORDER BY i.created_at DESC
        LIMIT $pageSize OFFSET $offset";
$items = Database::fetchAll($sql, $params);

// Get categories for filter
$categories = Database::fetchAll("SELECT * FROM category WHERE is_active = 1 ORDER BY name");

$pageTitle = 'Marketplace - ' . APP_NAME;
ob_start();
?>

<div class="bg-light py-4">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-cart"></i> Marketplace</h1>
        <?php if ($subdomain): ?>
            <p class="text-muted mb-0">Browsing items from <?= e($subdomain) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container my-4">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="<?= e($search) ?>"
                                   placeholder="Search items...">
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= e($cat['id']) ?>"
                                            <?= $categoryId === $cat['id'] ? 'selected' : '' ?>>
                                        <?= e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Apply Filters
                        </button>

                        <?php if ($categoryId || $search): ?>
                            <a href="marketplace.php" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="bi bi-x-circle"></i> Clear Filters
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0">
                    Showing <?= count($items) ?> of <?= $total ?> items
                </p>
            </div>

            <?php if (empty($items)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
                    <h3 class="mt-3">No items found</h3>
                    <p class="text-muted">Try adjusting your filters or search query</p>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    <?php foreach ($items as $item): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title text-truncate" title="<?= e($item['name']) ?>">
                                        <?= e($item['name']) ?>
                                    </h6>
                                    <?php if ($item['category_name']): ?>
                                        <p class="card-text small text-muted mb-1">
                                            <i class="bi bi-tag"></i> <?= e($item['category_name']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($item['organization_name']): ?>
                                        <p class="card-text small text-muted mb-2">
                                            <i class="bi bi-building"></i> <?= e($item['organization_name']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <a href="item.php?id=<?= e($item['id']) ?>" class="btn btn-sm btn-primary w-100">
                                        View Details
                                    </a>
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
                                <a class="page-link" href="?page=<?= $page - 1 ?>&category=<?= e($categoryId) ?>&search=<?= e($search) ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&category=<?= e($categoryId) ?>&search=<?= e($search) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>&category=<?= e($categoryId) ?>&search=<?= e($search) ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
