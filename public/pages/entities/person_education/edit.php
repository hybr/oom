<?php
use Entities\PersonEducation;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/person_education'); exit; }
$entity = Entities\PersonEducation::find($id);
if (!$entity) { redirect('/person_education'); exit; }
$pageTitle = 'Edit Person Education';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Person Education</h1>
<form method="POST" action="/person_education/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>