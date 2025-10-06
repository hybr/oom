<?php
use Entities\PopularOrganizationTeam;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_organization_teams'); exit; }
$entity = Entities\PopularOrganizationTeam::find($id);
if (!$entity) { redirect('/popular_organization_teams'); exit; }
$pageTitle = 'Edit Teams';
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Edit Teams</h1>
<form method="POST" action="/popular_organization_teams/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<!-- Add form fields -->
<button type="submit" class="btn btn-primary">Update</button>
</form></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>