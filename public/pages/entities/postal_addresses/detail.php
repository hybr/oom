<?php
use Entities\PostalAddress;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/postal_addresses'); exit; }
$entity = Entities\PostalAddress::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/postal_addresses'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Postal Addresses Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>