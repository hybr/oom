<?php
use Entities\PopularSkill;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_skills'); exit; }
$entity = Entities\PopularSkill::find($id);
if (!$entity) { redirect('/popular_skills'); exit; }
$pageTitle = 'Edit Popular Skills';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Popular Skills</h1>
<form method="POST" action="/popular_skills/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>