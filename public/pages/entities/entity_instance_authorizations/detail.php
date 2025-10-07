<?php
use Entities\EntityInstanceAuthorization;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/entity_instance_authorizations'); exit; }
$entity = Entities\EntityInstanceAuthorization::find($id);
if (!$entity) { $_SESSION['error'] = 'Not found'; redirect('/entity_instance_authorizations'); exit; }
$pageTitle = 'Entity Instance Authorization #' . $entity->id;
$entityDef = $entity->getEntity();
$person = $entity->getPerson();
$position = $entity->getPosition();
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4">
<nav aria-label="breadcrumb"><ol class="breadcrumb">
<li class="breadcrumb-item"><a href="/">Home</a></li>
<li class="breadcrumb-item"><a href="/entity_instance_authorizations">Entity Instance Authorizations</a></li>
<li class="breadcrumb-item active">Authorization #<?= $entity->id ?></li>
</ol></nav>
<div class="d-flex justify-content-between align-items-center mb-4">
<h1><i class="bi bi-key"></i> Instance Authorization Details</h1>
<div>
<a href="/entity_instance_authorizations/<?= $entity->id ?>/edit" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
<form method="POST" action="/entity_instance_authorizations/<?= $entity->id ?>/delete" style="display: inline;">
<?= csrf_field() ?>
<button type="submit" class="btn btn-danger" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i> Delete</button>
</form>
</div>
</div>
<div class="card">
<div class="card-body">
<p><strong>ID:</strong> <?= $entity->id ?></p>
<p><strong>Entity:</strong> <?= $entityDef ? htmlspecialchars($entityDef->name) : 'ID: ' . $entity->entity_id ?></p>
<p><strong>Record ID:</strong> <?= $entity->entity_record_id ?></p>
<p><strong>Action:</strong> <span class="badge bg-info"><?= htmlspecialchars($entity->action ?? '') ?></span></p>
<?php if ($person): ?><p><strong>Assigned Person:</strong> <?= htmlspecialchars(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?></p><?php endif; ?>
<?php if ($position): ?><p><strong>Assigned Position:</strong> <?= htmlspecialchars($position->title ?? '') ?></p><?php endif; ?>
<p><strong>Valid From:</strong> <?= $entity->valid_from ? date('Y-m-d H:i', strtotime($entity->valid_from)) : '-' ?></p>
<p><strong>Valid To:</strong> <?= $entity->valid_to ? date('Y-m-d H:i', strtotime($entity->valid_to)) : 'Indefinite' ?></p>
<p><strong>Status:</strong> <span class="badge bg-<?= $entity->status == 'active' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($entity->status ?? '') ?></span></p>
<?php if ($entity->isActive()): ?><p class="text-success"><i class="bi bi-check-circle"></i> Currently Active</p><?php endif; ?>
<?php if ($entity->hasExpired()): ?><p class="text-warning"><i class="bi bi-exclamation-triangle"></i> Expired</p><?php endif; ?>
</div>
</div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
