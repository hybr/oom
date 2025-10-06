<?php
use Entities\PopularOrganizationDesignation;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_organization_designations'); exit; }
$entity = Entities\PopularOrganizationDesignation::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/popular_organization_designations'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Designations Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>