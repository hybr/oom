<?php
$pageTitle = 'Add Postal Addresses';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Add Postal Addresses</h1>
<form method="POST" action="/postal_addresses/store">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Save</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>