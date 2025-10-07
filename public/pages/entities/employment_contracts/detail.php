<?php
/**
 * Employment Contract Detail Page
 */

use Entities\EmploymentContract;

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('/employment_contracts');
    exit;
}

$entity = Entities\EmploymentContract::find($id);
if (!$entity) {
    $_SESSION['error'] = 'Employment Contract not found';
    redirect('/employment_contracts');
    exit;
}

$pageTitle = 'Employment Contract #' . $entity->id;

// Get related entities
$jobOffer = $entity->getJobOffer();
$organization = $entity->getOrganization();
$employee = $entity->getEmployee();
$application = $entity->getApplication();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/employment_contracts">Employment Contracts</a></li>
            <li class="breadcrumb-item active">Contract #<?= $entity->id ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-file-earmark-text"></i> Employment Contract Details</h1>
        <div>
            <a href="/employment_contracts/<?= $entity->id ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form method="POST" action="/employment_contracts/<?= $entity->id ?>/delete" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employment contract?')">
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
                    <h5 class="mb-0">Contract Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Contract ID:</strong><br>
                            <?= $entity->id ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
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
                            <?php if ($entity->isActive()): ?>
                                <span class="badge bg-success">Currently Active</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Employee:</strong><br>
                            <?php if ($employee): ?>
                                <a href="/persons/<?= $employee->id ?>">
                                    <?= htmlspecialchars(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')) ?>
                                </a>
                            <?php else: ?>
                                Employee ID: <?= $entity->employee_id ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Organization:</strong><br>
                            <?php if ($organization): ?>
                                <a href="/organizations/<?= $organization->id ?>">
                                    <?= htmlspecialchars($organization->name ?? '') ?>
                                </a>
                            <?php else: ?>
                                Organization ID: <?= $entity->organization_id ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Start Date:</strong><br>
                            <?= $entity->start_date ? date('F j, Y', strtotime($entity->start_date)) : '-' ?>
                        </div>
                        <div class="col-md-6">
                            <strong>End Date:</strong><br>
                            <?php if ($entity->end_date): ?>
                                <?= date('F j, Y', strtotime($entity->end_date)) ?>
                            <?php else: ?>
                                <span class="badge bg-info">Permanent Contract</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($jobOffer): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Related Job Offer:</strong><br>
                            <a href="/job_offers/<?= $jobOffer->id ?>">
                                Offer #<?= $jobOffer->id ?> - <?= htmlspecialchars($jobOffer->position_title ?? '') ?>
                            </a>
                            <span class="badge bg-<?= $jobOffer->status == 'accepted' ? 'success' : 'secondary' ?>">
                                <?= htmlspecialchars($jobOffer->status ?? '') ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($application): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Original Application:</strong><br>
                            <a href="/vacancy_applications/<?= $application->id ?>">Application #<?= $application->id ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($entity->contract_terms): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Contract Terms:</strong><br>
                            <div class="mt-2 p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($entity->contract_terms)) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Duration Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Duration</h5>
                </div>
                <div class="card-body">
                    <p><strong>Type:</strong> <?= $entity->isPermanent() ? 'Permanent' : 'Fixed Term' ?></p>
                    <?php if (!$entity->isPermanent()): ?>
                        <p><strong>Total Duration:</strong> <?= $entity->getDurationInDays() ?> days</p>
                        <p><strong>Days Remaining:</strong> <?= $entity->getDaysRemaining() ?? 'N/A' ?> days</p>
                    <?php endif; ?>
                    <p><strong>Days Since Start:</strong> <?= $entity->getDaysSinceStart() ?> days</p>
                    <?php if ($entity->hasExpired()): ?>
                        <div class="alert alert-warning mt-2 mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Contract has expired
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timestamps Card -->
            <div class="card">
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
