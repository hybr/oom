<?php
/**
 * Employment Contracts List Page
 */

use Entities\EmploymentContract;

$pageTitle = 'Employment Contracts';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get entities
$entities = Entities\EmploymentContract::all($perPage, $offset);
$total = Entities\EmploymentContract::count();
$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-file-earmark-text"></i> Employment Contracts</h1>
        <a href="/employment_contracts/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Organization</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entities as $entity): ?>
                        <?php
                        $employee = $entity->getEmployee();
                        $organization = $entity->getOrganization();
                        ?>
                        <tr>
                            <td><?= $entity->id ?></td>
                            <td>
                                <?php if ($employee): ?>
                                    <?= htmlspecialchars(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')) ?>
                                <?php else: ?>
                                    ID: <?= $entity->employee_id ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($organization): ?>
                                    <?= htmlspecialchars($organization->name ?? '') ?>
                                <?php else: ?>
                                    ID: <?= $entity->organization_id ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $entity->start_date ? date('Y-m-d', strtotime($entity->start_date)) : '-' ?></td>
                            <td>
                                <?php if ($entity->end_date): ?>
                                    <?= date('Y-m-d', strtotime($entity->end_date)) ?>
                                <?php else: ?>
                                    <span class="badge bg-info">Permanent</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusClasses = [
                                    'draft' => 'secondary',
                                    'active' => 'success',
                                    'completed' => 'info',
                                    'terminated' => 'danger',
                                    'expired' => 'warning'
                                ];
                                $statusClass = $statusClasses[$entity->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $statusClass ?>"><?= htmlspecialchars($entity->status ?? '') ?></span>
                            </td>
                            <td>
                                <a href="/employment_contracts/<?= $entity->id ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="/employment_contracts/<?= $entity->id ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php $currentPage = $page; $baseUrl = '/employment_contracts'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
