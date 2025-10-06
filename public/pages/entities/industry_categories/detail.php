<?php
use Entities\IndustryCategory;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/industry_categories'); exit; }
$entity = Entities\IndustryCategory::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/industry_categories'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Industry Categories Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>