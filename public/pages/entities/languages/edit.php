<?php
use Entities\Language;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/languages'); exit; }
$entity = Entities\Language::find($id);
if (!$entity) { redirect('/languages'); exit; }
$pageTitle = 'Edit Languages';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Languages</h1>
<form method="POST" action="/languages/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>