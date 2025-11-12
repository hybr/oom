<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\MetadataLoader;
use V4L\Core\Database;

Auth::requireLogin();

$entityCode = $_GET['entity'] ?? null;
$action = $_GET['action'] ?? 'create'; // create or edit
$recordId = $_GET['id'] ?? null;

if (!$entityCode) {
    flash('error', 'Entity not specified');
    redirect('dashboard.php');
}

$entity = MetadataLoader::getEntity($entityCode);

if (!$entity) {
    flash('error', 'Entity not found');
    redirect('dashboard.php');
}

// Check permissions
if ($action === 'create') {
    Auth::requirePermission($entityCode, 'CREATE');
} else {
    Auth::requirePermission($entityCode, 'UPDATE');
}

// Get form fields (non-system fields)
$formFields = MetadataLoader::getFormFields($entityCode);

// Load existing record for edit
$record = null;
if ($action === 'edit' && $recordId) {
    $record = Database::fetchOne(
        "SELECT * FROM {$entity['table_name']} WHERE id = :id",
        [':id' => $recordId]
    );

    if (!$record) {
        flash('error', 'Record not found');
        redirect("entity-list.php?entity=" . urlencode($entityCode));
    }
}

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [];

    // Collect and validate form data
    foreach ($formFields as $field) {
        $fieldCode = $field['code'];
        $value = $_POST[$fieldCode] ?? null;

        // Handle empty values
        if ($value === '') {
            $value = null;
        }

        // Type conversion
        if ($value !== null) {
            switch (strtoupper($field['data_type'])) {
                case 'INTEGER':
                case 'INT':
                    $value = (int)$value;
                    break;
                case 'DECIMAL':
                case 'NUMBER':
                case 'FLOAT':
                    $value = (float)$value;
                    break;
                case 'BOOLEAN':
                case 'BOOL':
                    $value = isset($_POST[$fieldCode]) ? 1 : 0;
                    break;
            }
        }

        $formData[$fieldCode] = $value;
    }

    // Validate data
    $errors = MetadataLoader::validateData($entityCode, $formData);

    if (empty($errors)) {
        try {
            Database::beginTransaction();

            if ($action === 'create') {
                // Generate ID for new record
                $formData['id'] = Database::generateUuid();
                $formData['created_at'] = date('Y-m-d H:i:s');
                $formData['updated_at'] = date('Y-m-d H:i:s');

                Database::insert($entity['table_name'], $formData);

                flash('success', $entity['name'] . ' created successfully');
            } else {
                // Update existing record
                $formData['updated_at'] = date('Y-m-d H:i:s');

                Database::update(
                    $entity['table_name'],
                    $formData,
                    'id = :id',
                    [':id' => $recordId]
                );

                flash('success', $entity['name'] . ' updated successfully');
            }

            Database::commit();
            redirect("entity-list.php?entity=" . urlencode($entityCode));

        } catch (Exception $e) {
            Database::rollback();
            $errors['general'] = 'Failed to save record: ' . $e->getMessage();
        }
    }
}

$pageTitle = ($action === 'create' ? 'Add New ' : 'Edit ') . $entity['name'] . ' - ' . APP_NAME;
ob_start();
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-<?= $action === 'create' ? 'plus-circle' : 'pencil' ?>"></i>
                    <?= $action === 'create' ? 'Add New' : 'Edit' ?> <?= e($entity['name']) ?>
                </h1>
                <a href="entity-list.php?entity=<?= e($entityCode) ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>

            <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= e($errors['general']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <?php foreach ($formFields as $field): ?>
                            <?php
                            $fieldCode = $field['code'];
                            $fieldValue = $action === 'edit' && $record ? ($record[$fieldCode] ?? '') : ($_POST[$fieldCode] ?? '');
                            $hasError = isset($errors[$fieldCode]);
                            ?>

                            <div class="col-md-<?= in_array(strtoupper($field['data_type']), ['TEXT', 'TEXTAREA']) ? '12' : '6' ?>">
                                <label for="<?= e($fieldCode) ?>" class="form-label">
                                    <?= e($field['name']) ?>
                                    <?php if ($field['is_required']): ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?>
                                </label>

                                <?php if (strtoupper($field['data_type']) === 'BOOLEAN' || strtoupper($field['data_type']) === 'BOOL'): ?>
                                    <!-- Checkbox for boolean -->
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input <?= $hasError ? 'is-invalid' : '' ?>"
                                               id="<?= e($fieldCode) ?>"
                                               name="<?= e($fieldCode) ?>"
                                               value="1"
                                               <?= $fieldValue ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= e($fieldCode) ?>">
                                            <?= e($field['description'] ?? 'Yes') ?>
                                        </label>
                                    </div>

                                <?php elseif ($field['enum_values']): ?>
                                    <!-- Select dropdown for enum -->
                                    <?php $enumValues = json_decode($field['enum_values'], true); ?>
                                    <select class="form-select <?= $hasError ? 'is-invalid' : '' ?>"
                                            id="<?= e($fieldCode) ?>"
                                            name="<?= e($fieldCode) ?>"
                                            <?= $field['is_required'] ? 'required' : '' ?>>
                                        <option value="">-- Select --</option>
                                        <?php foreach ($enumValues as $enumValue): ?>
                                        <option value="<?= e($enumValue) ?>" <?= $fieldValue == $enumValue ? 'selected' : '' ?>>
                                            <?= e($enumValue) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>

                                <?php elseif (in_array(strtoupper($field['data_type']), ['TEXT', 'TEXTAREA'])): ?>
                                    <!-- Textarea for long text -->
                                    <textarea class="form-control <?= $hasError ? 'is-invalid' : '' ?>"
                                              id="<?= e($fieldCode) ?>"
                                              name="<?= e($fieldCode) ?>"
                                              rows="3"
                                              <?= $field['is_required'] ? 'required' : '' ?>
                                              <?= $field['max_value'] ? 'maxlength="' . e($field['max_value']) . '"' : '' ?>
                                              placeholder="<?= e($field['description'] ?? '') ?>"><?= e($fieldValue) ?></textarea>

                                <?php else: ?>
                                    <!-- Text input for other types -->
                                    <?php
                                    $inputType = 'text';
                                    switch (strtoupper($field['data_type'])) {
                                        case 'INTEGER':
                                        case 'INT':
                                        case 'NUMBER':
                                        case 'DECIMAL':
                                            $inputType = 'number';
                                            break;
                                        case 'EMAIL':
                                            $inputType = 'email';
                                            break;
                                        case 'URL':
                                            $inputType = 'url';
                                            break;
                                        case 'DATE':
                                            $inputType = 'date';
                                            break;
                                        case 'DATETIME':
                                        case 'TIMESTAMP':
                                            $inputType = 'datetime-local';
                                            break;
                                    }
                                    ?>
                                    <input type="<?= $inputType ?>"
                                           class="form-control <?= $hasError ? 'is-invalid' : '' ?>"
                                           id="<?= e($fieldCode) ?>"
                                           name="<?= e($fieldCode) ?>"
                                           value="<?= e($fieldValue) ?>"
                                           <?= $field['is_required'] ? 'required' : '' ?>
                                           <?= $field['min_value'] ? 'min="' . e($field['min_value']) . '"' : '' ?>
                                           <?= $field['max_value'] ? 'max="' . e($field['max_value']) . '"' : '' ?>
                                           placeholder="<?= e($field['description'] ?? '') ?>">
                                <?php endif; ?>

                                <?php if ($hasError): ?>
                                <div class="invalid-feedback d-block">
                                    <?= e($errors[$fieldCode]) ?>
                                </div>
                                <?php elseif ($field['description']): ?>
                                <div class="form-text">
                                    <?= e($field['description']) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="entity-list.php?entity=<?= e($entityCode) ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i>
                                <?= $action === 'create' ? 'Create' : 'Update' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($action === 'edit' && $record): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="text-muted">Record Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Created:</small><br>
                            <span><?= e($record['created_at'] ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Last Updated:</small><br>
                            <span><?= e($record['updated_at'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
