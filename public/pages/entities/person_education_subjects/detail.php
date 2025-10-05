<?php
use Entities\PersonEducationSubject;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/person_education_subjects'); exit; }
$entity = Entities\PersonEducationSubject::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/person_education_subjects'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Education Subjects Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>