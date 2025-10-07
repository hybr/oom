<?php
/**
 * Entity Process Authorization Detail Page
 */

use Entities\EntityProcessAuthorization;

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('/entity_process_authorizations');
    exit;
}

$entity = Entities\EntityProcessAuthorization::find($id);
if (!$entity) {
    $_SESSION['error'] = 'Entity Process Authorization not found';
    redirect('/entity_process_authorizations');
    exit;
}

$pageTitle = 'Entity Process Authorization #' . $entity->id;

// Get related entities
$entityDef = $entity->getEntity();
$position = $entity->getPosition();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/entity_process_authorizations">Entity Process Authorizations</a></li>
            <li class="breadcrumb-item active">Authorization #<?= $entity->id ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-shield-check"></i> Process Authorization Details</h1>
        <div>
            <a href="/entity_process_authorizations/<?= $entity->id ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form method="POST" action="/entity_process_authorizations/<?= $entity->id ?>/delete" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this authorization?')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Main Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Authorization Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Authorization ID:</strong><br>
                            <?= $entity->id ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Action:</strong><br>
                            <span class="badge bg-primary"><?= htmlspecialchars($entity->action ?? '') ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Entity:</strong><br>
                            <?php if ($entityDef): ?>
                                <a href="/entity_definitions/<?= $entityDef->id ?>">
                                    <?= htmlspecialchars($entityDef->name ?? '') ?>
                                </a>
                            <?php else: ?>
                                Entity ID: <?= $entity->entity_id ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Position:</strong><br>
                            <?php if ($position): ?>
                                <a href="/popular_organization_positions/<?= $position->id ?>">
                                    <?= htmlspecialchars($position->title ?? 'Position #' . $position->id) ?>
                                </a>
                            <?php else: ?>
                                Position ID: <?= $entity->popular_position_id ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($entity->remarks): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Remarks:</strong><br>
                            <?= nl2br(htmlspecialchars($entity->remarks)) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Timestamps Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Record Info</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Created:</strong><br>
                        <?= $entity->created_at ? date('Y-m-d H:i:s', strtotime($entity->created_at)) : '-' ?>
                    </p>
                    <p class="mb-0">
                        <strong>Updated:</strong><br>
                        <?= $entity->updated_at ? date('Y-m-d H:i:s', strtotime($entity->updated_at)) : '-' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
