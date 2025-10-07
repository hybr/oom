<?php
/**
 * Entity Definition Detail Page
 */

use Entities\EntityDefinition;

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('/entity_definitions');
    exit;
}

$entity = Entities\EntityDefinition::find($id);
if (!$entity) {
    $_SESSION['error'] = 'Entity Definition not found';
    redirect('/entity_definitions');
    exit;
}

$pageTitle = 'Entity Definition: ' . $entity->name;

// Get related data
$processAuths = $entity->getAuthorizations();
$instanceAuths = $entity->getActiveInstanceAuthorizations();
$authSummary = $entity->getAuthorizationSummary();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/entity_definitions">Entity Definitions</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($entity->name ?? '') ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-diagram-3"></i> <?= htmlspecialchars($entity->name ?? '') ?></h1>
        <div>
            <a href="/entity_definitions/<?= $entity->id ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form method="POST" action="/entity_definitions/<?= $entity->id ?>/delete" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this entity definition?')">
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
                    <h5 class="mb-0">Entity Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID:</strong><br>
                            <?= $entity->id ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Entity Name:</strong><br>
                            <?= htmlspecialchars($entity->name ?? '') ?>
                        </div>
                    </div>

                    <?php if ($entity->description): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Description:</strong><br>
                            <?= nl2br(htmlspecialchars($entity->description)) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Process Authorizations Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Process Authorizations (<?= count($processAuths) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($processAuths)): ?>
                        <p class="text-muted">No process authorizations defined yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Position</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($processAuths as $auth): ?>
                                    <?php $position = $auth->getPosition(); ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($auth->action ?? '') ?></span></td>
                                        <td>
                                            <?php if ($position): ?>
                                                <a href="/popular_organization_positions/<?= $position->id ?>">
                                                    <?= htmlspecialchars($position->title ?? 'Position #' . $position->id) ?>
                                                </a>
                                            <?php else: ?>
                                                Position ID: <?= $auth->popular_position_id ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($auth->remarks ?? '-') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <a href="/entity_process_authorizations/create?entity_id=<?= $entity->id ?>" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-plus"></i> Add Process Authorization
                    </a>
                </div>
            </div>

            <!-- Instance Authorizations Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Active Instance Authorizations (<?= count($instanceAuths) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($instanceAuths)): ?>
                        <p class="text-muted">No active instance authorizations.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Record ID</th>
                                        <th>Action</th>
                                        <th>Assigned To</th>
                                        <th>Valid Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($instanceAuths, 0, 10) as $auth): ?>
                                    <tr>
                                        <td><?= $auth->entity_record_id ?></td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($auth->action ?? '') ?></span></td>
                                        <td>
                                            <?php if ($auth->assigned_person_id): ?>
                                                Person ID: <?= $auth->assigned_person_id ?>
                                            <?php elseif ($auth->assigned_position_id): ?>
                                                Position ID: <?= $auth->assigned_position_id ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $auth->valid_from ? date('Y-m-d', strtotime($auth->valid_from)) : '-' ?>
                                            to
                                            <?= $auth->valid_to ? date('Y-m-d', strtotime($auth->valid_to)) : 'Indefinite' ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($instanceAuths) > 10): ?>
                            <p class="text-muted mt-2">Showing first 10 of <?= count($instanceAuths) ?> instance authorizations.</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Summary Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Authorization Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Process Authorizations:</strong> <?= $authSummary['process_authorizations_count'] ?></p>
                    <p><strong>Instance Authorizations:</strong> <?= $authSummary['instance_authorizations_count'] ?></p>

                    <?php if (!empty($authSummary['actions'])): ?>
                    <hr>
                    <p><strong>Actions:</strong></p>
                    <ul class="list-unstyled">
                        <?php foreach ($authSummary['actions'] as $action => $count): ?>
                        <li>
                            <span class="badge bg-secondary"><?= htmlspecialchars($action) ?></span>
                            <span class="text-muted">x<?= $count ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>

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
