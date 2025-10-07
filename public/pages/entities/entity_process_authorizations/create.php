<?php
/**
 * Create Entity Process Authorization Page
 */

use Entities\EntityDefinition;
use Entities\PopularOrganizationPosition;

$pageTitle = 'Add New Entity Process Authorization';

// Get all entity definitions for the dropdown
$entityDefinitions = EntityDefinition::all();

// Get all positions for the dropdown
$positions = PopularOrganizationPosition::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/entity_process_authorizations">Entity Process Authorizations</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Entity Process Authorization
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/entity_process_authorizations/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="entity_id" class="form-label">Entity <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('entity_id') ? 'is-invalid' : '' ?>"
                                    id="entity_id"
                                    name="entity_id"
                                    required>
                                <option value="">Select an entity...</option>
                                <?php foreach ($entityDefinitions as $def): ?>
                                    <option value="<?= $def->id ?>" <?= old('entity_id') == $def->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($def->name ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'entity_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the entity this authorization applies to</div>
                        </div>

                        <div class="mb-3">
                            <label for="action" class="form-label">Action <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('action') ? 'is-invalid' : '' ?>"
                                   id="action"
                                   name="action"
                                   value="<?= old('action') ?>"
                                   required
                                   placeholder="e.g., create, read, update, delete, approve">
                            <?php $field = 'action'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the action (e.g., create, read, update, delete, approve)</div>
                        </div>

                        <div class="mb-3">
                            <label for="popular_position_id" class="form-label">Position <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('popular_position_id') ? 'is-invalid' : '' ?>"
                                    id="popular_position_id"
                                    name="popular_position_id"
                                    required>
                                <option value="">Select a position...</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?= $position->id ?>" <?= old('popular_position_id') == $position->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($position->title ?? 'Position #' . $position->id) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'popular_position_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the position that can perform this action</div>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control <?= errors('remarks') ? 'is-invalid' : '' ?>"
                                      id="remarks"
                                      name="remarks"
                                      rows="3"><?= old('remarks') ?></textarea>
                            <?php $field = 'remarks'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Optional notes about this authorization</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/entity_process_authorizations" class="btn btn-secondary">
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
