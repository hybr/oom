<?php
use Entities\PopularOrganizationDepartment;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_organization_departments'); exit; }
$entity = Entities\PopularOrganizationDepartment::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/popular_organization_departments'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Departments Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>