<?php
$pageTitle = 'Add Person Skills';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Add Person Skills</h1>
<form method="POST" action="/person_skills/store">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Save</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>