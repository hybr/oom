<?php
use Entities\Language;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/languages'); exit; }
$entity = Entities\Language::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/languages'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Languages Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>