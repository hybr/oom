<?php
use Entities\PopularEducationSubject;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/popular_education_subjects'); exit; }
$entity = Entities\PopularEducationSubject::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/popular_education_subjects'); exit; }
$pageTitle = $entity->id;
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><h1>Education Subjects Details</h1>
<div class="card"><div class="card-body"><p>ID: <?= $entity->id ?></p></div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>