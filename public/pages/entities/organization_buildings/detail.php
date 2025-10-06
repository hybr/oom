<?php
use Entities\OrganizationBuilding;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organization_buildings'); exit; }
$entity = Entities\OrganizationBuilding::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/organization_buildings'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Buildings Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>