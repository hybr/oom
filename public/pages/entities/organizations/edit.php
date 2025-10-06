<?php
use Entities\Organization;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organizations'); exit; }
$entity = Entities\Organization::find($id);
if (!$entity) { redirect('/organizations'); exit; }
$pageTitle = 'Edit Organizations';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Organizations</h1>
<form method="POST" action="/organizations/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>