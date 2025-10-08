<?php
require_once __DIR__ . '/../../../bootstrap.php';

// Public page - no auth required

$db = db();
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$page = $_GET['page'] ?? 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Build query
$sql = "SELECT ci.*, cc.name as category_name
        FROM catalog_items ci
        LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
        WHERE ci.deleted_at IS NULL AND ci.status = 'Active'";

$params = [];

if ($search) {
    $sql .= " AND (ci.name LIKE ? OR ci.short_description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $sql .= " AND ci.category_id = ?";
    $params[] = $category;
}

// Count total
$countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as t";
$totalRecords = $db->selectOne($countSql, $params)['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

// Get data
$sql .= " ORDER BY ci.created_date DESC LIMIT $perPage OFFSET $offset";
$items = $db->select($sql, $params);

// Get categories for filter
$categories = $db->select("SELECT * FROM catalog_categories WHERE deleted_at IS NULL AND is_active = 1 ORDER BY name");

$pageTitle = 'Marketplace Catalog';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="text-center py-5">
        <h1 class="display-4">🛒 Marketplace Catalog</h1>
        <p class="lead text-muted">Discover local products and services from your community</p>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search products and services..." value="<?php echo escape($search); ?>">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo escape($cat['name']); ?>
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

    <!-- Results Summary -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">
            Showing <strong><?php echo count($items); ?></strong> of <strong><?php echo $totalRecords; ?></strong> items
        </p>
        <?php if (auth()->check()): ?>
            <a href="<?php echo url('pages/entities/catalog_items/create.php'); ?>" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle"></i> Add Item to Catalog
            </a>
        <?php endif; ?>
    </div>

    <!-- Items Grid -->
    <?php if (empty($items)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h4>No items found</h4>
            <p class="text-muted">Try adjusting your search criteria or browse all categories.</p>
        </div>
    <?php else: ?>
        <div class="row g-4 mb-4">
            <?php foreach ($items as $item): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php if ($item['thumbnail_url']): ?>
                            <img src="<?php echo escape($item['thumbnail_url']); ?>" class="card-img-top" alt="<?php echo escape($item['name']); ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image fs-1 text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2"><?php echo escape($item['type']); ?></span>
                            <?php if ($item['category_name']): ?>
                                <span class="badge bg-secondary mb-2"><?php echo escape($item['category_name']); ?></span>
                            <?php endif; ?>
                            <h5 class="card-title"><?php echo escape($item['name']); ?></h5>
                            <?php if ($item['brand_name']): ?>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag"></i> <?php echo escape($item['brand_name']); ?>
                                </p>
                            <?php endif; ?>
                            <p class="card-text text-muted small">
                                <?php echo escape(substr($item['short_description'] ?? '', 0, 100)); ?>
                                <?php if (strlen($item['short_description'] ?? '') > 100) echo '...'; ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <?php if (auth()->check()): ?>
                                <a href="<?php echo url('pages/entities/catalog_items/detail.php?id=' . $item['id']); ?>" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            <?php else: ?>
                                <a href="<?php echo url('pages/auth/login.php'); ?>" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to View
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Call to Action -->
    <?php if (!auth()->check()): ?>
        <div class="card bg-light mt-5">
            <div class="card-body text-center py-5">
                <h3 class="mb-3">Want to sell on V4L?</h3>
                <p class="text-muted mb-4">Join our community and start reaching local customers today!</p>
                <a href="<?php echo url('pages/auth/signup.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Sign Up Now
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
