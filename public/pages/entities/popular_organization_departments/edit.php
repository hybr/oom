<?php
use Entities\PopularOrganizationDepartment;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_organization_departments'); exit; }
$entity = Entities\PopularOrganizationDepartment::find($id);
if (!$entity) { redirect('/popular_organization_departments'); exit; }
$pageTitle = 'Edit Departments';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Departments</h1>
<form method="POST" action="/popular_organization_departments/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>