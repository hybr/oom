<?php
/**
 * Person Education List Page
 */

use Entities\PersonEducation;

$pageTitle = 'Person Education';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\PersonEducation::all($perPage, $offset);
$total = Entities\PersonEducation::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi-mortarboard"></i> Person Education</h1>
        <a href="/person_education/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover">
            <thead><tr><th>ID</th><th>Details</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($entities as $entity): ?>
                <tr><td><?= $entity->id ?></td><td><!-- Add fields --></td>
                <td><a href="/person_education/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
        <?php $currentPage = $page; $baseUrl = '/person_education'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
    </div></div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>