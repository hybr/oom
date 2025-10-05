<?php
use Entities\Country;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/countries'); exit; }
$entity = Entities\Country::find($id);
if (!$entity) { redirect('/countries'); exit; }
$pageTitle = 'Edit Countries';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Countries</h1>
<form method="POST" action="/countries/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>