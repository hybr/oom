<?php
use Entities\OrganizationLegalCategory;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organization_legal_categories'); exit; }
$entity = Entities\OrganizationLegalCategory::find($id);
if (!$entity) { redirect('/organization_legal_categories'); exit; }
$pageTitle = 'Edit Legal Categories';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Legal Categories</h1>
<form method="POST" action="/organization_legal_categories/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>