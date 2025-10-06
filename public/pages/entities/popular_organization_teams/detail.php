<?php
use Entities\PopularOrganizationTeam;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_organization_teams'); exit; }
$entity = Entities\PopularOrganizationTeam::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/popular_organization_teams'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Teams Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>