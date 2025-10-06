<?php
use Entities\OrganizationBranch;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organization_branches'); exit; }
$entity = Entities\OrganizationBranch::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/organization_branches'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Branches Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>