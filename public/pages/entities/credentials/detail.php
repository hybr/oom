<?php
use Entities\Credential;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/credentials'); exit; }
$entity = Entities\Credential::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/credentials'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Credentials Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>