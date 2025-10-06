<?php
use Entities\IndustryCategory;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/industry_categories'); exit; }
$entity = Entities\IndustryCategory::find($id);
if (!$entity) { redirect('/industry_categories'); exit; }
$pageTitle = 'Edit Industry Categories';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Industry Categories</h1>
<form method="POST" action="/industry_categories/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>