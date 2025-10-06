<?php
use Entities\OrganizationBuilding;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organization_buildings'); exit; }
$entity = Entities\OrganizationBuilding::find($id);
if (!$entity) { redirect('/organization_buildings'); exit; }
$pageTitle = 'Edit Buildings';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Buildings</h1>
<form method="POST" action="/organization_buildings/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>