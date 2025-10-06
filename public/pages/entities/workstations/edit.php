<?php
use Entities\Workstation;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/workstations'); exit; }
$entity = Entities\Workstation::find($id);
if (!$entity) { redirect('/workstations'); exit; }
$pageTitle = 'Edit Workstations';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Workstations</h1>
<form method="POST" action="/workstations/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>