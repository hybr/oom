<?php
use Entities\OrganizationBranch;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organization_branches'); exit; }
$entity = Entities\OrganizationBranch::find($id);
if (!$entity) { redirect('/organization_branches'); exit; }
$pageTitle = 'Edit Branches';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Branches</h1>
<form method="POST" action="/organization_branches/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>