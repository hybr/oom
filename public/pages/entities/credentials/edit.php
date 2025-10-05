<?php
use Entities\Credential;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/credentials'); exit; }
$entity = Entities\Credential::find($id);
if (!$entity) { redirect('/credentials'); exit; }
$pageTitle = 'Edit Credentials';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Credentials</h1>
<form method="POST" action="/credentials/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>