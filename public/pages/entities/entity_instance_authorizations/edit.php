<?php
use Entities\EntityInstanceAuthorization;
use Entities\EntityDefinition;
use Entities\PopularOrganizationPosition;
use Entities\Person;
$id = $_GET['id'] ?? null;
if (!$id) { redirect('/entity_instance_authorizations'); exit; }
$entity = Entities\EntityInstanceAuthorization::find($id);
if (!$entity) { redirect('/entity_instance_authorizations'); exit; }
$pageTitle = 'Edit Entity Instance Authorization';
$entityDefinitions = EntityDefinition::all();
$positions = PopularOrganizationPosition::all();
$persons = Person::all();
include __DIR__ . '/../../../../includes/header.php';
?>
<div class="container-fluid mt-4"><div class="row"><div class="col-md-8 offset-md-2">
<h1 class="mb-4"><i class="bi bi-pencil"></i> Edit Entity Instance Authorization</h1>
<div class="card"><div class="card-body">
<?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
<form method="POST" action="/entity_instance_authorizations/<?= $entity->id ?>/update">
<?= csrf_field() ?>
<div class="mb-3">
<label for="entity_id" class="form-label">Entity <span class="text-danger">*</span></label>
<select class="form-select" id="entity_id" name="entity_id" required>
<option value="">Select...</option>
<?php foreach ($entityDefinitions as $def): ?>
<option value="<?= $def->id ?>" <?= (old('entity_id') ?? $entity->entity_id) == $def->id ? 'selected' : '' ?>>
<?= htmlspecialchars($def->name ?? '') ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="mb-3">
<label>Entity Record ID <span class="text-danger">*</span></label>
<input type="number" class="form-control" name="entity_record_id" value="<?= old('entity_record_id') ?? $entity->entity_record_id ?>" required>
</div>
<div class="mb-3">
<label>Action <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="action" value="<?= old('action') ?? $entity->action ?>" required>
</div>
<div class="mb-3">
<label>Assigned Position</label>
<select class="form-select" name="assigned_position_id">
<option value="">None</option>
<?php foreach ($positions as $pos): ?>
<option value="<?= $pos->id ?>" <?= (old('assigned_position_id') ?? $entity->assigned_position_id) == $pos->id ? 'selected' : '' ?>>
<?= htmlspecialchars($pos->title ?? '') ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="mb-3">
<label>Assigned Person</label>
<select class="form-select" name="assigned_person_id">
<option value="">None</option>
<?php foreach ($persons as $person): ?>
<option value="<?= $person->id ?>" <?= (old('assigned_person_id') ?? $entity->assigned_person_id) == $person->id ? 'selected' : '' ?>>
<?= htmlspecialchars(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="row">
<div class="col-md-6 mb-3">
<label>Valid From</label>
<input type="datetime-local" class="form-control" name="valid_from" value="<?= old('valid_from') ?? ($entity->valid_from ? date('Y-m-d\TH:i', strtotime($entity->valid_from)) : '') ?>">
</div>
<div class="col-md-6 mb-3">
<label>Valid To</label>
<input type="datetime-local" class="form-control" name="valid_to" value="<?= old('valid_to') ?? ($entity->valid_to ? date('Y-m-d\TH:i', strtotime($entity->valid_to)) : '') ?>">
</div>
</div>
<div class="mb-3">
<label>Status <span class="text-danger">*</span></label>
<select class="form-select" name="status" required>
<option value="active" <?= (old('status') ?? $entity->status) == 'active' ? 'selected' : '' ?>>Active</option>
<option value="revoked" <?= (old('status') ?? $entity->status) == 'revoked' ? 'selected' : '' ?>>Revoked</option>
<option value="expired" <?= (old('status') ?? $entity->status) == 'expired' ? 'selected' : '' ?>>Expired</option>
</select>
</div>
<div class="d-flex justify-content-between">
<a href="/entity_instance_authorizations/<?= $entity->id ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancel</a>
<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
</div>
</form>
</div></div>
</div></div></div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
