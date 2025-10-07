<?php
use Entities\CatalogCategory;
$pageTitle = 'Add New Catalog Category';
$categories = CatalogCategory::all();
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><div class="row"><div class="col-md-8 offset-md-2">
<h1 class="mb-4"><i class="bi bi-plus-circle"></i> Add New Catalog Category</h1>
<div class="card"><div class="card-body">
<?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
<form method="POST" action="/catalog_categories/store" class="needs-validation" novalidate>
<?= csrf_field() ?>
<div class="mb-3">
<label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
<input type="text" class="form-control <?= errors('name') ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= old('name') ?>" required autofocus>
<?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
</div>
<div class="mb-3">
<label for="parent_category_id" class="form-label">Parent Category</label>
<select class="form-select <?= errors('parent_category_id') ? 'is-invalid' : '' ?>" id="parent_category_id" name="parent_category_id">
<option value="">None (Root Category)</option>
<?php foreach ($categories as $cat): ?>
<option value="<?= $cat->id ?>" <?= old('parent_category_id') == $cat->id ? 'selected' : '' ?>><?= htmlspecialchars($cat->name ?? '') ?></option>
<?php endforeach; ?>
</select>
<?php $field = 'parent_category_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
</div>
<div class="mb-3">
<label for="description" class="form-label">Description</label>
<textarea class="form-control <?= errors('description') ? 'is-invalid' : '' ?>" id="description" name="description" rows="3"><?= old('description') ?></textarea>
<?php $field = 'description'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
</div>
<div class="mb-3 form-check">
<input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= old('is_active', true) ? 'checked' : '' ?>>
<label class="form-check-label" for="is_active">Active</label>
</div>
<div class="d-flex justify-content-between">
<a href="/catalog_categories" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancel</a>
<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Category</button>
</div>
</form>
</div></div>
</div></div></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
