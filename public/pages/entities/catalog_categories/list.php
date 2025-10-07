<?php
/**
 * Catalog Categories List Page
 */

use Entities\CatalogCategory;

$pageTitle = 'Catalog Categories';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\CatalogCategory::all($perPage, $offset);
$total = Entities\CatalogCategory::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-folder"></i> Catalog Categories</h1>
        <a href="/catalog_categories/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities as $entity): ?>
                        <?php
                        $parent = $entity->getParentCategory();
                        $items = $entity->getCatalogItems();
                        ?>
                        <tr>
                            <td><?= $entity->id ?></td>
                            <td><strong><?= htmlspecialchars($entity->name ?? '') ?></strong></td>
                            <td>
                                <?php if ($parent): ?>
                                    <a href="/catalog_categories/<?= $parent->id ?>"><?= htmlspecialchars($parent->name ?? '') ?></a>
                                <?php else: ?>
                                    <span class="badge bg-info">Root</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary"><?= count($items) ?></span></td>
                            <td>
                                <?php if ($entity->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/catalog_categories/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="/catalog_categories/<?= $entity->id ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $currentPage = $page; $baseUrl = '/catalog_categories'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
