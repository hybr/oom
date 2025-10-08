<?php
/**
 * Entity Definitions List Page
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\EntityDefinition;

$pageTitle = 'Entity Definitions';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\EntityDefinition::all($perPage, $offset);
$total = Entities\EntityDefinition::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-diagram-3"></i> Entity Definitions</h1>
        <a href="/entity_definitions/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Process Authorizations</th>
                            <th>Instance Authorizations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities as $entity): ?>
                        <?php
                        $processAuths = $entity->getAuthorizations();
                        $instanceAuths = $entity->getActiveInstanceAuthorizations();
                        ?>
                        <tr>
                            <td><?= $entity->id ?></td>
                            <td><strong><?= htmlspecialchars($entity->name ?? '') ?></strong></td>
                            <td><?= htmlspecialchars(substr($entity->description ?? '', 0, 100)) ?><?= strlen($entity->description ?? '') > 100 ? '...' : '' ?></td>
                            <td><span class="badge bg-primary"><?= count($processAuths) ?></span></td>
                            <td><span class="badge bg-info"><?= count($instanceAuths) ?></span></td>
                            <td>
                                <a href="/entity_definitions/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="/entity_definitions/<?= $entity->id ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $currentPage = $page; $baseUrl = '/entity_definitions'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
