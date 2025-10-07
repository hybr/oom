<?php
/**
 * Vacancy Application Detail Page
 */

use Entities\VacancyApplication;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/vacancy_applications');
    exit;
}

$app = VacancyApplication::find($id);

if (!$app) {
    $_SESSION['error'] = 'Application not found';
    redirect('/vacancy_applications');
    exit;
}

$pageTitle = 'Application Details';

$vacancy = $app->getVacancy();
$applicant = $app->getApplicant();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/vacancy_applications">Applications</a></li>
                    <li class="breadcrumb-item active">Application #<?= $app->id ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-file-earmark-text"></i> Application #<?= $app->id ?>
                </h1>
                <div>
                    <a href="/vacancy_applications/<?= $app->id ?>/edit" class="btn btn-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/vacancy_applications/<?= $app->id ?>/delete" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <h5 class="mb-0">Application Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="250">ID:</th>
                                    <td><?= $app->id ?></td>
                                </tr>
                                <tr>
                                    <th>Vacancy:</th>
                                    <td><?= $vacancy ? '<a href="/organization_vacancies/' . $vacancy->id . '">Vacancy #' . $vacancy->id . '</a>' : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Applicant:</th>
                                    <td><?= $applicant ? htmlspecialchars($applicant->first_name . ' ' . $applicant->last_name) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Application Date:</th>
                                    <td><?= $app->application_date ? date('F d, Y', strtotime($app->application_date)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
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
                                </tr>
                                <tr>
                                    <th>Resume URL:</th>
                                    <td><?= $app->resume_url ? '<a href="' . htmlspecialchars($app->resume_url) . '" target="_blank">View Resume</a>' : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Cover Letter:</th>
                                    <td><?= $app->cover_letter ? nl2br(htmlspecialchars($app->cover_letter)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($app->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?= date('F d, Y h:i A', strtotime($app->updated_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td><?= $app->version ?></td>
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
                                <a href="/vacancy_applications/<?= $app->id ?>/edit" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit Application
                                </a>
                                <a href="/application_reviews/create?application_id=<?= $app->id ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-plus"></i> Add Review
                                </a>
                                <a href="/application_interviews/create?application_id=<?= $app->id ?>" class="btn btn-outline-info">
                                    <i class="bi bi-plus"></i> Schedule Interview
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
                            <?php $history = $app->getHistory(); ?>
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
