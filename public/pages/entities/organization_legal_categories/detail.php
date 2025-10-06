<?php
use Entities\OrganizationLegalCategory;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/organization_legal_categories'); exit; }
$entity = Entities\OrganizationLegalCategory::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/organization_legal_categories'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Legal Categories Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>