<?php
use Entities\PostalAddress;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/postal_addresses'); exit; }
$entity = Entities\PostalAddress::find($id);
if (!$entity) { redirect('/postal_addresses'); exit; }
$pageTitle = 'Edit Postal Addresses';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Postal Addresses</h1>
<form method="POST" action="/postal_addresses/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>