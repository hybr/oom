<?php
use Entities\Country;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/countries'); exit; }
$entity = Entities\Country::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/countries'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Countries Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>