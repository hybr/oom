<?php
/**
 * Job Offer Detail Page
 */

use Entities\JobOffer;

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('/job_offers');
    exit;
}

$entity = Entities\JobOffer::find($id);
if (!$entity) {
    $_SESSION['error'] = 'Job Offer not found';
    redirect('/job_offers');
    exit;
}

$pageTitle = 'Job Offer #' . $entity->id;

// Get related entities
$application = $entity->getApplication();
$offeredBy = $entity->getOfferedBy();
$applicant = $entity->getApplicant();
$vacancy = $entity->getVacancy();
$contract = $entity->getEmploymentContract();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/job_offers">Job Offers</a></li>
            <li class="breadcrumb-item active">Job Offer #<?= $entity->id ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-briefcase-fill"></i> Job Offer Details</h1>
        <div>
            <a href="/job_offers/<?= $entity->id ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form method="POST" action="/job_offers/<?= $entity->id ?>/delete" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job offer?')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Main Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Offer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID:</strong><br>
                            <?= $entity->id ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
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
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Position Title:</strong><br>
                            <?= htmlspecialchars($entity->position_title ?? '-') ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Salary Offered:</strong><br>
                            <?= $entity->salary_offered ? '$' . number_format($entity->salary_offered, 2) : '-' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Application ID:</strong><br>
                            <?php if ($application): ?>
                                <a href="/vacancy_applications/<?= $application->id ?>">#<?= $application->id ?></a>
                            <?php else: ?>
                                <?= $entity->application_id ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Offer Date:</strong><br>
                            <?= $entity->offer_date ? date('F j, Y', strtotime($entity->offer_date)) : '-' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Joining Date:</strong><br>
                            <?= $entity->joining_date ? date('F j, Y', strtotime($entity->joining_date)) : '-' ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Offered By:</strong><br>
                            <?php if ($offeredBy): ?>
                                <a href="/persons/<?= $offeredBy->id ?>">
                                    <?= htmlspecialchars(($offeredBy->first_name ?? '') . ' ' . ($offeredBy->last_name ?? '')) ?>
                                </a>
                            <?php else: ?>
                                Person ID: <?= $entity->offered_by ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Applicant:</strong><br>
                            <?php if ($applicant): ?>
                                <a href="/persons/<?= $applicant->id ?>">
                                    <?= htmlspecialchars(($applicant->first_name ?? '') . ' ' . ($applicant->last_name ?? '')) ?>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($vacancy): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Vacancy:</strong><br>
                            <a href="/organization_vacancies/<?= $vacancy->id ?>">
                                <?= htmlspecialchars($vacancy->title ?? 'Vacancy #' . $vacancy->id) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($contract): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Employment Contract:</strong><br>
                            <a href="/employment_contracts/<?= $contract->id ?>">Contract #<?= $contract->id ?></a>
                            <span class="badge bg-info"><?= htmlspecialchars($contract->status ?? '') ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Timeline Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <p><strong>Days Since Offer:</strong> <?= $entity->getDaysSinceOffer() ?> days</p>
                    <?php if ($entity->joining_date): ?>
                    <p><strong>Days Until Joining:</strong> <?= $entity->getDaysUntilJoining() ?> days</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timestamps Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Record Info</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Created:</strong><br>
                        <?= $entity->created_at ? date('Y-m-d H:i:s', strtotime($entity->created_at)) : '-' ?>
                    </p>
                    <p class="mb-0">
                        <strong>Updated:</strong><br>
                        <?= $entity->updated_at ? date('Y-m-d H:i:s', strtotime($entity->updated_at)) : '-' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
