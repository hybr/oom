<?php
use Entities\CatalogCategory;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/catalog_categories'); exit; }
$entity = Entities\CatalogCategory::find($id);
if (!$entity) { redirect('/catalog_categories'); exit; }
$pageTitle = 'Edit Catalog Category';
$categories = CatalogCategory::all();
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><div class="row"><div class="col-md-8 offset-md-2">
<h1 class="mb-4"><i class="bi bi-pencil"></i> Edit Catalog Category</h1>
<div class="card"><div class="card-body">
<?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
<form method="POST" action="/catalog_categories/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<div class="mb-3">
<label>Category Name <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="name" value="<?= old('name') ?? $entity->name ?>" required>
</div>
<div class="mb-3">
<label>Parent Category</label>
<select class="form-select" name="parent_category_id">
<option value="">None (Root Category)</option>
<?php foreach ($categories as $cat): ?>
<?php if ($cat->id != $entity->id): ?>
<option value="<?= $cat->id ?>" <?= (old('parent_category_id') ?? $entity->parent_category_id) == $cat->id ? 'selected' : '' ?>><?= htmlspecialchars($cat->name ?? '') ?></option>
<?php endif; ?>
<?php endforeach; ?>
</select>
</div>
<div class="mb-3">
<label>Description</label>
<textarea class="form-control" name="description" rows="3"><?= old('description') ?? $entity->description ?></textarea>
</div>
<div class="mb-3 form-check">
<input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= old('is_active', $entity->is_active) ? 'checked' : '' ?>>
<label class="form-check-label" for="is_active">Active</label>
</div>
<div class="d-flex justify-content-between">
<a href="/catalog_categories/<?= $entity->id ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancel</a>
<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
</div>
</form>
</div></div>
</div></div></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
