<?php
use Entities\Person;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/persons'); exit; }
$entity = Entities\Person::find($id);
if (!$entity) { redirect('/persons'); exit; }
$pageTitle = 'Edit Persons';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Persons</h1>
<form method="POST" action="/persons/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>