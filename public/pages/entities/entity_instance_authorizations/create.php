<?php
/**
 * Create Entity Instance Authorization Page
 */

use Entities\EntityDefinition;
use Entities\PopularOrganizationPosition;
use Entities\Person;

$pageTitle = 'Add New Entity Instance Authorization';

$entityDefinitions = EntityDefinition::all();
$positions = PopularOrganizationPosition::all();
$persons = Person::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/entity_instance_authorizations">Entity Instance Authorizations</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <h1 class="mb-4"><i class="bi bi-plus-circle"></i> Add New Entity Instance Authorization</h1>

            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/entity_instance_authorizations/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="entity_id" class="form-label">Entity <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('entity_id') ? 'is-invalid' : '' ?>" id="entity_id" name="entity_id" required>
                                <option value="">Select an entity...</option>
                                <?php foreach ($entityDefinitions as $def): ?>
                                    <option value="<?= $def->id ?>" <?= old('entity_id') == $def->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($def->name ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'entity_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="entity_record_id" class="form-label">Entity Record ID <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?= errors('entity_record_id') ? 'is-invalid' : '' ?>"
                                   id="entity_record_id" name="entity_record_id" value="<?= old('entity_record_id') ?>" required>
                            <?php $field = 'entity_record_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">The ID of the specific record</div>
                        </div>

                        <div class="mb-3">
                            <label for="action" class="form-label">Action <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= errors('action') ? 'is-invalid' : '' ?>"
                                   id="action" name="action" value="<?= old('action') ?>" required>
                            <?php $field = 'action'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="assigned_position_id" class="form-label">Assigned Position</label>
                            <select class="form-select <?= errors('assigned_position_id') ? 'is-invalid' : '' ?>"
                                    id="assigned_position_id" name="assigned_position_id">
                                <option value="">Select a position...</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?= $position->id ?>" <?= old('assigned_position_id') == $position->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($position->title ?? 'Position #' . $position->id) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'assigned_position_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="assigned_person_id" class="form-label">Assigned Person</label>
                            <select class="form-select <?= errors('assigned_person_id') ? 'is-invalid' : '' ?>"
                                    id="assigned_person_id" name="assigned_person_id">
                                <option value="">Select a person...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('assigned_person_id') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'assigned_person_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="valid_from" class="form-label">Valid From</label>
                                <input type="datetime-local" class="form-control <?= errors('valid_from') ? 'is-invalid' : '' ?>"
                                       id="valid_from" name="valid_from" value="<?= old('valid_from') ?>">
                                <?php $field = 'valid_from'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="valid_to" class="form-label">Valid To</label>
                                <input type="datetime-local" class="form-control <?= errors('valid_to') ? 'is-invalid' : '' ?>"
                                       id="valid_to" name="valid_to" value="<?= old('valid_to') ?>">
                                <?php $field = 'valid_to'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('status') ? 'is-invalid' : '' ?>" id="status" name="status" required>
                                <option value="">Select status...</option>
                                <option value="active" <?= old('status') == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="revoked" <?= old('status') == 'revoked' ? 'selected' : '' ?>>Revoked</option>
                                <option value="expired" <?= old('status') == 'expired' ? 'selected' : '' ?>>Expired</option>
                            </select>
                            <?php $field = 'status'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/entity_instance_authorizations" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Authorization
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
