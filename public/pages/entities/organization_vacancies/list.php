<?php
/**
 * Organization Vacancies List Page
 */

use Entities\OrganizationVacancy;

$pageTitle = 'Organization Vacancies';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Search
$search = $_GET['search'] ?? '';

// Get vacancies
if ($search) {
    $vacancies = OrganizationVacancy::searchByName($search);
    $total = count($vacancies);
    $vacancies = array_slice($vacancies, $offset, $perPage);
} else {
    $vacancies = OrganizationVacancy::all($perPage, $offset);
    $total = OrganizationVacancy::count();
}

$totalPages = ceil($total / $perPage);

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-journal-text"></i> Organization Vacancies
                </h1>
                <a href="/organization_vacancies/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Vacancy
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search vacancies..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="per_page" class="form-select">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 per page</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25 per page</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 per page</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        All Vacancies (<?= $total ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Organization</th>
                                    <th>Position</th>
                                    <th>Opening Date</th>
                                    <th>Closing Date</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($vacancies)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> No vacancies found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($vacancies as $vacancy): ?>
                                        <tr>
                                            <td><?= $vacancy->id ?></td>
                                            <td>
                                                <?php $org = $vacancy->getOrganization(); ?>
                                                <?= $org ? htmlspecialchars($org->name) : '-' ?>
                                            </td>
                                            <td>
                                                <a href="/organization_vacancies/<?= $vacancy->id ?>" class="text-decoration-none">
                                                    <?php $position = $vacancy->getPopularPosition(); ?>
                                                    <strong><?= $position ? htmlspecialchars($position->name) : 'N/A' ?></strong>
                                                </a>
                                            </td>
                                            <td><?= $vacancy->opening_date ? date('M d, Y', strtotime($vacancy->opening_date)) : '-' ?></td>
                                            <td><?= $vacancy->closing_date ? date('M d, Y', strtotime($vacancy->closing_date)) : '-' ?></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'Open' => 'success',
                                                    'Closed' => 'danger',
                                                    'On Hold' => 'warning'
                                                ];
                                                $class = $statusClass[$vacancy->status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $class ?>"><?= htmlspecialchars($vacancy->status) ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($vacancy->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/organization_vacancies/<?= $vacancy->id ?>" class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/organization_vacancies/<?= $vacancy->id ?>/edit" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/organization_vacancies/<?= $vacancy->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vacancy?');">
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
                    $baseUrl = '/organization_vacancies';
                    include __DIR__ . '/../../../../views/components/pagination.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
