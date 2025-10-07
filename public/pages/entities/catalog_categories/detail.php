<?php
use Entities\CatalogCategory;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/catalog_categories'); exit; }
$entity = Entities\CatalogCategory::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/catalog_categories'); exit; }
$pageTitle = $entity->name;
$parent = $entity->getParentCategory();
$children = $entity->getChildCategories();
$items = $entity->getCatalogItems();
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4">
<div class="d-flex justify-content-between align-items-center mb-4">
<h1><i class="bi bi-folder"></i> <?= htmlspecialchars($entity->name ?? '') ?></h1>
<div>
<a href="/catalog_categories/<?= $entity->id ?>/edit" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
<form method="POST" action="/catalog_categories/<?= $entity->id ?>/delete" style="display: inline;">
<?= csrf_field() ?>
<button type="submit" class="btn btn-danger" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i> Delete</button>
</form>
</div>
</div>
<div class="card">
<div class="card-body">
<p><strong>ID:</strong> <?= $entity->id ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($entity->name ?? '') ?></p>
<?php if ($entity->description): ?><p><strong>Description:</strong> <?= nl2br(htmlspecialchars($entity->description)) ?></p><?php endif; ?>
<p><strong>Full Path:</strong> <?= htmlspecialchars($entity->getFullCategoryPath()) ?></p>
<?php if ($parent): ?><p><strong>Parent:</strong> <a href="/catalog_categories/<?= $parent->id ?>"><?= htmlspecialchars($parent->name ?? '') ?></a></p><?php endif; ?>
<p><strong>Status:</strong> <span class="badge bg-<?= $entity->is_active ? 'success' : 'secondary' ?>"><?= $entity->is_active ? 'Active' : 'Inactive' ?></span></p>
<p><strong>Child Categories:</strong> <?= count($children) ?></p>
<p><strong>Items in Category:</strong> <?= count($items) ?></p>
<p><strong>Total Items (Recursive):</strong> <?= $entity->countItemsRecursive() ?></p>
</div>
</div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
