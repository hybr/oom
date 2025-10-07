<?php
/**
 * Job Offers List Page
 */

use Entities\JobOffer;

$pageTitle = 'Job Offers';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\JobOffer::all($perPage, $offset);
$total = Entities\JobOffer::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-briefcase-fill"></i> Job Offers</h1>
        <a href="/job_offers/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Position Title</th>
                            <th>Salary Offered</th>
                            <th>Offer Date</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities as $entity): ?>
                        <tr>
                            <td><?= $entity->id ?></td>
                            <td><?= htmlspecialchars($entity->position_title ?? '') ?></td>
                            <td><?= $entity->salary_offered ? '$' . number_format($entity->salary_offered, 2) : '-' ?></td>
                            <td><?= $entity->offer_date ? date('Y-m-d', strtotime($entity->offer_date)) : '-' ?></td>
                            <td><?= $entity->joining_date ? date('Y-m-d', strtotime($entity->joining_date)) : '-' ?></td>
                            <td>
                                <?php
                                $statusClasses = [
                                    'pending' => 'warning',
                                    'accepted' => 'success',
                                    'declined' => 'danger',
                                    'expired' => 'secondary',
                                    'withdrawn' => 'dark'
                                ];
                                $statusClass = $statusClasses[$entity->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $statusClass ?>"><?= htmlspecialchars($entity->status ?? '') ?></span>
                            </td>
                            <td>
                                <a href="/job_offers/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="/job_offers/<?= $entity->id ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $currentPage = $page; $baseUrl = '/job_offers'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
