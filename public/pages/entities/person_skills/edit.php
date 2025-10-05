<?php
use Entities\PersonSkill;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/person_skills'); exit; }
$entity = Entities\PersonSkill::find($id);
if (!$entity) { redirect('/person_skills'); exit; }
$pageTitle = 'Edit Person Skills';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Person Skills</h1>
<form method="POST" action="/person_skills/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>