<?php
use Entities\PopularOrganizationDesignation;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_organization_designations'); exit; }
$entity = Entities\PopularOrganizationDesignation::find($id);
if (!$entity) { redirect('/popular_organization_designations'); exit; }
$pageTitle = 'Edit Designations';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Designations</h1>
<form method="POST" action="/popular_organization_designations/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>