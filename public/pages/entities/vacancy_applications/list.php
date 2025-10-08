<?php
/**
 * Vacancy Applications List Page
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Entities\VacancyApplication;

$pageTitle = 'Vacancy Applications';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;
$offset = ($page - 1) * $perPage;

// Search
$search = $_GET['search'] ?? '';

// Get applications
if ($search) {
    $applications = VacancyApplication::searchByName($search);
    $total = count($applications);
    $applications = array_slice($applications, $offset, $perPage);
} else {
    $applications = VacancyApplication::all($perPage, $offset);
    $total = VacancyApplication::count();
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
                    <i class="bi bi-file-earmark-text"></i> Vacancy Applications
                </h1>
                <a href="/vacancy_applications/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Application
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search applications..." value="<?= htmlspecialchars($search) ?>">
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
                        All Applications (<?= $total ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vacancy</th>
                                    <th>Applicant</th>
                                    <th>Application Date</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($applications)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> No applications found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($applications as $app): ?>
                                        <tr>
                                            <td><?= $app->id ?></td>
                                            <td>
                                                <?php $vacancy = $app->getVacancy(); ?>
                                                <?= $vacancy ? '<a href="/organization_vacancies/' . $vacancy->id . '">Vacancy #' . $vacancy->id . '</a>' : '-' ?>
                                            </td>
                                            <td>
                                                <a href="/vacancy_applications/<?= $app->id ?>" class="text-decoration-none">
                                                    <?php $applicant = $app->getApplicant(); ?>
                                                    <strong><?= $applicant ? htmlspecialchars($applicant->first_name . ' ' . $applicant->last_name) : 'N/A' ?></strong>
                                                </a>
                                            </td>
                                            <td><?= $app->application_date ? date('M d, Y', strtotime($app->application_date)) : '-' ?></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'Applied' => 'primary',
                                                    'Shortlisted' => 'info',
                                                    'Rejected' => 'danger',
                                                    'Selected' => 'success',
                                                    'Withdrawn' => 'secondary'
                                                ];
                                                $class = $statusClass[$app->status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $class ?>"><?= htmlspecialchars($app->status) ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($app->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/vacancy_applications/<?= $app->id ?>" class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/vacancy_applications/<?= $app->id ?>/edit" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/vacancy_applications/<?= $app->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                    $baseUrl = '/vacancy_applications';
                    include __DIR__ . '/../../../../views/components/pagination.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
