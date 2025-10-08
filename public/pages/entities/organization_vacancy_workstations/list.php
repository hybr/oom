<?php
/**
 * Organization Vacancy Workstations List Page
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\OrganizationVacancyWorkstation;

$pageTitle = 'Vacancy Workstations';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Get workstations
$workstations = OrganizationVacancyWorkstation::all($perPage, $offset);
$total = OrganizationVacancyWorkstation::count();

$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-building"></i> Vacancy Workstations
                </h1>
                <a href="/organization_vacancy_workstations/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Workstation
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-9">
                            <select name="per_page" class="form-select">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 per page</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25 per page</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 per page</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-search"></i> Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        All Vacancy Workstations (<?= $total ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vacancy</th>
                                    <th>Workstation</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($workstations)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> No vacancy workstations found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($workstations as $ws): ?>
                                        <tr>
                                            <td><?= $ws->id ?></td>
                                            <td>
                                                <?php $vacancy = $ws->getVacancy(); ?>
                                                <?php if ($vacancy): ?>
                                                    <a href="/organization_vacancies/<?= $vacancy->id ?>">
                                                        Vacancy #<?= $vacancy->id ?>
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php $workstation = $ws->getWorkstation(); ?>
                                                <?= $workstation ? htmlspecialchars($workstation->name) : '-' ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($ws->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/organization_vacancy_workstations/<?= $ws->id ?>" class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/organization_vacancy_workstations/<?= $ws->id ?>/edit" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/organization_vacancy_workstations/<?= $ws->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php
                    $currentPage = $page;
                    $baseUrl = '/organization_vacancy_workstations';
                    include __DIR__ . '/../../../../views/components/pagination.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
