<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\MetadataLoader;
use V4L\Core\Database;

$entityCode = $_GET['entity'] ?? null;

if (!$entityCode) {
    flash('error', 'Entity not specified');
    redirect('dashboard.php');
}

$entity = MetadataLoader::getEntity($entityCode);

if (!$entity) {
    flash('error', 'Entity not found');
    redirect('dashboard.php');
}

// Get display columns
$displayColumns = MetadataLoader::getDisplayColumns($entityCode, 5);

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$pageSize = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $pageSize;

// Search
$search = $_GET['search'] ?? '';

// Build query
$whereClauses = [];
$params = [];

if ($search) {
    $searchClauses = [];
    foreach ($displayColumns as $col) {
        if (in_array($col['data_type'], ['TEXT', 'VARCHAR', 'STRING'])) {
            $searchClauses[] = "{$col['code']} LIKE :search";
        }
    }
    if ($searchClauses) {
        $whereClauses[] = '(' . implode(' OR ', $searchClauses) . ')';
        $params[':search'] = "%$search%";
    }
}

$whereClause = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// Get total count
$countSql = "SELECT COUNT(*) as total FROM {$entity['table_name']} $whereClause";
$totalResult = Database::fetchOne($countSql, $params);
$total = $totalResult['total'];
$totalPages = ceil($total / $pageSize);

// Get records
$sql = "SELECT * FROM {$entity['table_name']} $whereClause ORDER BY created_at DESC LIMIT $pageSize OFFSET $offset";
$records = Database::fetchAll($sql, $params);

$pageTitle = $entity['name'] . ' - ' . APP_NAME;
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-table"></i> <?= e($entity['name']) ?></h1>
        <div class="btn-group">
            <a href="entity-form.php?entity=<?= e($entityCode) ?>&action=create" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New <?= e($entity['name']) ?>
            </a>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="entity" value="<?= e($entityCode) ?>">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Search..."
                           value="<?= e($search) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($displayColumns as $col): ?>
                            <th><?= e($col['name']) ?></th>
                            <?php endforeach; ?>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="<?= count($displayColumns) + 1 ?>" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">No records found</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($records as $record): ?>
                            <tr>
                                <?php foreach ($displayColumns as $col): ?>
                                <td><?= e($record[$col['code']] ?? '') ?></td>
                                <?php endforeach; ?>
                                <td class="text-end">
                                    <a href="entity-detail.php?entity=<?= e($entityCode) ?>&id=<?= e($record['id']) ?>"
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (Auth::hasPermission($entityCode, 'UPDATE')): ?>
                                    <a href="entity-form.php?entity=<?= e($entityCode) ?>&action=edit&id=<?= e($record['id']) ?>"
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (Auth::hasPermission($entityCode, 'DELETE')): ?>
                                    <button class="btn btn-sm btn-danger" title="Delete"
                                            onclick="deleteRecord('<?= e($record['id']) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?entity=<?= e($entityCode) ?>&page=<?= $page - 1 ?>&search=<?= e($search) ?>">
                            Previous
                        </a>
                    </li>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?entity=<?= e($entityCode) ?>&page=<?= $i ?>&search=<?= e($search) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?entity=<?= e($entityCode) ?>&page=<?= $page + 1 ?>&search=<?= e($search) ?>">
                            Next
                        </a>
                    </li>
                </ul>
                <div class="text-center text-muted small">
                    Page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> total records)
                </div>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteRecord(id) {
    if (!confirm('Are you sure you want to delete this record?')) {
        return;
    }

    fetch('/api/entities/<?= e($entityCode) ?>/' + id, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
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
