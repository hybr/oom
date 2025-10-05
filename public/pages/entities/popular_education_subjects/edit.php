<?php
use Entities\PopularEducationSubject;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_education_subjects'); exit; }
$entity = Entities\PopularEducationSubject::find($id);
if (!$entity) { redirect('/popular_education_subjects'); exit; }
$pageTitle = 'Edit Education Subjects';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Education Subjects</h1>
<form method="POST" action="/popular_education_subjects/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>