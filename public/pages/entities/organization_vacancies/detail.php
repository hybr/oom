<?php
/**
 * Organization Vacancy Detail Page
 */

use Entities\OrganizationVacancy;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/organization_vacancies');
    exit;
}

$vacancy = OrganizationVacancy::find($id);

if (!$vacancy) {
    $_SESSION['error'] = 'Vacancy not found';
    redirect('/organization_vacancies');
    exit;
}

$pageTitle = 'Vacancy Details';

// Load related data
$organization = $vacancy->getOrganization();
$position = $vacancy->getPopularPosition();
$createdBy = $vacancy->getCreatedBy();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/organization_vacancies">Vacancies</a></li>
                    <li class="breadcrumb-item active">Vacancy #<?= $vacancy->id ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-journal-text"></i> Vacancy #<?= $vacancy->id ?>
                </h1>
                <div>
                    <a href="/organization_vacancies/<?= $vacancy->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/organization_vacancies/<?= $vacancy->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vacancy?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <!-- Main Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Vacancy Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="250">ID:</th>
                                    <td><?= $vacancy->id ?></td>
                                </tr>
                                <tr>
                                    <th>Organization:</th>
                                    <td><?= $organization ? htmlspecialchars($organization->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td><?= $position ? htmlspecialchars($position->name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Opening Date:</th>
                                    <td><?= $vacancy->opening_date ? date('F d, Y', strtotime($vacancy->opening_date)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Closing Date:</th>
                                    <td><?= $vacancy->closing_date ? date('F d, Y', strtotime($vacancy->closing_date)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
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
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td><?= $createdBy ? htmlspecialchars($createdBy->first_name . ' ' . $createdBy->last_name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($vacancy->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($vacancy->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $vacancy->version ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/organization_vacancies/<?= $vacancy->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit Vacancy
                                </a>
                                <a href="/vacancy_applications/create?vacancy_id=<?= $vacancy->id ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-plus"></i> Add Application
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- History -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Activity History</h5>
                        </div>
                        <div class="card-body">
                            <?php $history = $vacancy->getHistory(); ?>
                            <?php if (empty($history)): ?>
                                <p class="text-muted mb-0"><small>No history available</small></p>
                            <?php else: ?>
                                <div class="timeline">
                                    <?php foreach (array_slice($history, 0, 5) as $entry): ?>
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <?= date('M d, Y h:i A', strtotime($entry['created_at'])) ?>
                                            </small>
                                            <div>
                                                <span class="badge bg-secondary"><?= ucfirst($entry['action']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
