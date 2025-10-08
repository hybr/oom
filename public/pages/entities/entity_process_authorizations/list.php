<?php
/**
 * Entity Process Authorizations List Page
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\EntityProcessAuthorization;

$pageTitle = 'Entity Process Authorizations';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\EntityProcessAuthorization::all($perPage, $offset);
$total = Entities\EntityProcessAuthorization::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-shield-check"></i> Entity Process Authorizations</h1>
        <a href="/entity_process_authorizations/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entity</th>
                            <th>Action</th>
                            <th>Position</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities as $entity): ?>
                        <?php
                        $entityDef = $entity->getEntity();
                        $position = $entity->getPosition();
                        ?>
                        <tr>
                            <td><?= $entity->id ?></td>
                            <td>
                                <?php if ($entityDef): ?>
                                    <a href="/entity_definitions/<?= $entityDef->id ?>">
                                        <?= htmlspecialchars($entityDef->name ?? '') ?>
                                    </a>
                                <?php else: ?>
                                    ID: <?= $entity->entity_id ?>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($entity->action ?? '') ?></span></td>
                            <td>
                                <?php if ($position): ?>
                                    <?= htmlspecialchars($position->title ?? 'Position #' . $position->id) ?>
                                <?php else: ?>
                                    Position ID: <?= $entity->popular_position_id ?>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($entity->remarks ?? '', 0, 50)) ?><?= strlen($entity->remarks ?? '') > 50 ? '...' : '' ?></td>
                            <td>
                                <a href="/entity_process_authorizations/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="/entity_process_authorizations/<?= $entity->id ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $currentPage = $page; $baseUrl = '/entity_process_authorizations'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
