<?php
use Entities\PersonSkill;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/person_skills'); exit; }
$entity = Entities\PersonSkill::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/person_skills'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Person Skills Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>