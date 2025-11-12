<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\MetadataLoader;
use V4L\Core\Database;

Auth::requireLogin();

$entityCode = $_GET['entity'] ?? null;
$recordId = $_GET['id'] ?? null;

if (!$entityCode || !$recordId) {
    flash('error', 'Entity or record ID not specified');
    redirect('dashboard.php');
}

$entity = MetadataLoader::getEntity($entityCode);

if (!$entity) {
    flash('error', 'Entity not found');
    redirect('dashboard.php');
}

// Check read permission
Auth::requirePermission($entityCode, 'READ');

// Load record
$record = Database::fetchOne(
    "SELECT * FROM {$entity['table_name']} WHERE id = :id",
    [':id' => $recordId]
);

if (!$record) {
    flash('error', 'Record not found');
    redirect("entity-list.php?entity=" . urlencode($entityCode));
}

// Get all attributes for display
$attributes = $entity['attributes'];

// Get relationships and load related data
$relationships = $entity['relationships'];
$relatedData = [];

foreach ($relationships as $rel) {
    if ($rel['from_entity_id'] === $entity['id']) {
        // This entity has a foreign key to another entity
        $targetEntityId = $rel['to_entity_id'];
        $targetEntity = MetadataLoader::getEntityById($targetEntityId);

        if ($targetEntity && isset($record[$rel['fk_field']])) {
            $relatedRecord = Database::fetchOne(
                "SELECT * FROM {$targetEntity['table_name']} WHERE id = :id",
                [':id' => $record[$rel['fk_field']]]
            );

            if ($relatedRecord) {
                $labelAttr = MetadataLoader::getLabelAttribute($targetEntity['code']);
                $relatedData[$rel['fk_field']] = [
                    'entity' => $targetEntity,
                    'record' => $relatedRecord,
                    'label' => $labelAttr ? $relatedRecord[$labelAttr['code']] : $relatedRecord['id']
                ];
            }
        }
    }
}

$pageTitle = $entity['name'] . ' Details - ' . APP_NAME;
ob_start();
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-eye"></i> <?= e($entity['name']) ?> Details</h1>
                <div>
                    <?php if (Auth::hasPermission($entityCode, 'UPDATE')): ?>
                    <a href="entity-form.php?entity=<?= e($entityCode) ?>&action=edit&id=<?= e($recordId) ?>"
                       class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="entity-list.php?entity=<?= e($entityCode) ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($attributes as $attr): ?>
                            <?php
                            // Skip system fields we'll show separately
                            if (in_array($attr['code'], ['created_at', 'updated_at'])) {
                                continue;
                            }

                            $value = $record[$attr['code']] ?? null;
                            $displayValue = $value;

                            // Format value based on type
                            if ($value !== null) {
                                switch (strtoupper($attr['data_type'])) {
                                    case 'BOOLEAN':
                                    case 'BOOL':
                                        $displayValue = $value ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
                                        break;
                                    case 'DATE':
                                        $displayValue = date('F j, Y', strtotime($value));
                                        break;
                                    case 'DATETIME':
                                    case 'TIMESTAMP':
                                        $displayValue = date('F j, Y g:i A', strtotime($value));
                                        break;
                                    case 'EMAIL':
                                        $displayValue = '<a href="mailto:' . e($value) . '">' . e($value) . '</a>';
                                        break;
                                    case 'URL':
                                        $displayValue = '<a href="' . e($value) . '" target="_blank">' . e($value) . ' <i class="bi bi-box-arrow-up-right"></i></a>';
                                        break;
                                    default:
                                        // Check if this is a foreign key
                                        if (isset($relatedData[$attr['code']])) {
                                            $related = $relatedData[$attr['code']];
                                            $displayValue = '<a href="entity-detail.php?entity=' . e($related['entity']['code']) . '&id=' . e($value) . '">' .
                                                          e($related['label']) .
                                                          ' <i class="bi bi-box-arrow-up-right"></i></a>';
                                        } else {
                                            $displayValue = e($value);
                                        }
                                }
                            } else {
                                $displayValue = '<span class="text-muted">N/A</span>';
                            }
                            ?>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted mb-1">
                                        <strong><?= e($attr['name']) ?></strong>
                                    </label>
                                    <div class="text-break">
                                        <?= $displayValue ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Record Metadata -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="bi bi-info-circle"></i> Record Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Record ID:</small><br>
                            <code><?= e($record['id']) ?></code>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Created:</small><br>
                            <span><?= isset($record['created_at']) ? date('F j, Y g:i A', strtotime($record['created_at'])) : 'N/A' ?></span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Last Updated:</small><br>
                            <span><?= isset($record['updated_at']) ? date('F j, Y g:i A', strtotime($record['updated_at'])) : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Records -->
            <?php
            // Find relationships where this entity is the target (OneToMany from other entities)
            $incomingRelationships = array_filter($relationships, function($rel) use ($entity) {
                return $rel['to_entity_id'] === $entity['id'];
            });

            foreach ($incomingRelationships as $rel):
                $sourceEntity = MetadataLoader::getEntityById($rel['from_entity_id']);
                if (!$sourceEntity) continue;

                // Get related records
                $relatedRecords = Database::fetchAll(
                    "SELECT * FROM {$sourceEntity['table_name']} WHERE {$rel['fk_field']} = :id LIMIT 10",
                    [':id' => $recordId]
                );

                if (empty($relatedRecords)) continue;

                $labelAttr = MetadataLoader::getLabelAttribute($sourceEntity['code']);
            ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-link-45deg"></i>
                        Related <?= e($sourceEntity['name']) ?> (<?= count($relatedRecords) ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($relatedRecords as $relRecord): ?>
                        <a href="entity-detail.php?entity=<?= e($sourceEntity['code']) ?>&id=<?= e($relRecord['id']) ?>"
                           class="list-group-item list-group-item-action">
                            <?= e($labelAttr ? $relRecord[$labelAttr['code']] : $relRecord['id']) ?>
                            <i class="bi bi-chevron-right float-end"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($relatedRecords) >= 10): ?>
                    <div class="text-center mt-3">
                        <a href="entity-list.php?entity=<?= e($sourceEntity['code']) ?>" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Delete Button -->
            <?php if (Auth::hasPermission($entityCode, 'DELETE')): ?>
            <div class="card border-danger mt-3">
                <div class="card-body">
                    <h6 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Danger Zone</h6>
                    <p class="mb-2">Once you delete this record, there is no going back.</p>
                    <button class="btn btn-danger" onclick="deleteRecord()">
                        <i class="bi bi-trash"></i> Delete This Record
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteRecord() {
    if (!confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
        return;
    }

    fetch('/api/entities/<?= e($entityCode) ?>/<?= e($recordId) ?>', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'entity-list.php?entity=<?= e($entityCode) ?>';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error deleting record: ' + error);
    });
}
</script>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
