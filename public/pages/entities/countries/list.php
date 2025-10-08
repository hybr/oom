<?php
/**
 * Countries List Page
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\Country;

$pageTitle = 'Countries';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\Country::all($perPage, $offset);
$total = Entities\Country::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi-flag"></i> Countries</h1>
        <a href="/countries/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover">
            <thead><tr><th>ID</th><th>Details</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($entities as $entity): ?>
                <tr><td><?= $entity->id ?></td><td><!-- Add fields --></td>
                <td><a href="/countries/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
        <?php $currentPage = $page; $baseUrl = '/countries'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
    </div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>