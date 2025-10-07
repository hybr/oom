<?php
/**
 * Entity Instance Authorizations List Page
 */

use Entities\EntityInstanceAuthorization;

$pageTitle = 'Entity Instance Authorizations';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\EntityInstanceAuthorization::all($perPage, $offset);
$total = Entities\EntityInstanceAuthorization::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-key"></i> Entity Instance Authorizations</h1>
        <a href="/entity_instance_authorizations/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entity</th>
                            <th>Record ID</th>
                            <th>Action</th>
                            <th>Assigned To</th>
                            <th>Valid Period</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities as $entity): ?>
                        <?php
                        $entityDef = $entity->getEntity();
                        $person = $entity->getPerson();
                        $position = $entity->getPosition();
                        ?>
                        <tr>
                            <td><?= $entity->id ?></td>
                            <td>
                                <?php if ($entityDef): ?>
                                    <a href="/entity_definitions/<?= $entityDef->id ?>"><?= htmlspecialchars($entityDef->name ?? '') ?></a>
                                <?php else: ?>
                                    ID: <?= $entity->entity_id ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $entity->entity_record_id ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($entity->action ?? '') ?></span></td>
                            <td>
                                <?php if ($person): ?>
                                    <?= htmlspecialchars(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?>
                                <?php elseif ($position): ?>
                                    <?= htmlspecialchars($position->title ?? '') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $entity->valid_from ? date('Y-m-d', strtotime($entity->valid_from)) : '-' ?>
                                to
                                <?= $entity->valid_to ? date('Y-m-d', strtotime($entity->valid_to)) : 'Indefinite' ?>
                            </td>
                            <td>
                                <?php
                                $statusClasses = ['active' => 'success', 'revoked' => 'danger', 'expired' => 'warning'];
                                $statusClass = $statusClasses[$entity->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $statusClass ?>"><?= htmlspecialchars($entity->status ?? '') ?></span>
                            </td>
                            <td>
                                <a href="/entity_instance_authorizations/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $currentPage = $page; $baseUrl = '/entity_instance_authorizations'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
