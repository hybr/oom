<?php
/**
 * Edit Employment Contract Page
 */

use Entities\EmploymentContract;
use Entities\JobOffer;
use Entities\Organization;
use Entities\Person;

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('/employment_contracts');
    exit;
}

$entity = Entities\EmploymentContract::find($id);
if (!$entity) {
    redirect('/employment_contracts');
    exit;
}

$pageTitle = 'Edit Employment Contract #' . $entity->id;

// Get all job offers for the dropdown
$jobOffers = JobOffer::all();

// Get all organizations for the dropdown
$organizations = Organization::all();

// Get all persons for the employee dropdown
$persons = Person::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/employment_contracts">Employment Contracts</a></li>
                    <li class="breadcrumb-item"><a href="/employment_contracts/<?= $entity->id ?>">Contract #<?= $entity->id ?></a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-pencil"></i> Edit Employment Contract
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/employment_contracts/<?= $entity->id ?>/update" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="job_offer_id" class="form-label">Job Offer <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('job_offer_id') ? 'is-invalid' : '' ?>"
                                    id="job_offer_id"
                                    name="job_offer_id"
                                    required>
                                <option value="">Select a job offer...</option>
                                <?php foreach ($jobOffers as $offer): ?>
                                    <option value="<?= $offer->id ?>"
                                        <?= (old('job_offer_id') ?? $entity->job_offer_id) == $offer->id ? 'selected' : '' ?>>
                                        Offer #<?= $offer->id ?> - <?= htmlspecialchars($offer->position_title ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'job_offer_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_id') ? 'is-invalid' : '' ?>"
                                    id="organization_id"
                                    name="organization_id"
                                    required>
                                <option value="">Select an organization...</option>
                                <?php foreach ($organizations as $org): ?>
                                    <option value="<?= $org->id ?>"
                                        <?= (old('organization_id') ?? $entity->organization_id) == $org->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($org->name ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('employee_id') ? 'is-invalid' : '' ?>"
                                    id="employee_id"
                                    name="employee_id"
                                    required>
                                <option value="">Select an employee...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>"
                                        <?= (old('employee_id') ?? $entity->employee_id) == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'employee_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control <?= errors('start_date') ? 'is-invalid' : '' ?>"
                                   id="start_date"
                                   name="start_date"
                                   value="<?= old('start_date') ?? ($entity->start_date ? date('Y-m-d', strtotime($entity->start_date)) : '') ?>"
                                   required>
                            <?php $field = 'start_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date"
                                   class="form-control <?= errors('end_date') ? 'is-invalid' : '' ?>"
                                   id="end_date"
                                   name="end_date"
                                   value="<?= old('end_date') ?? ($entity->end_date ? date('Y-m-d', strtotime($entity->end_date)) : '') ?>">
                            <?php $field = 'end_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Leave blank for permanent contract</div>
                        </div>

                        <div class="mb-3">
                            <label for="contract_terms" class="form-label">Contract Terms</label>
                            <textarea class="form-control <?= errors('contract_terms') ? 'is-invalid' : '' ?>"
                                      id="contract_terms"
                                      name="contract_terms"
                                      rows="5"><?= old('contract_terms') ?? $entity->contract_terms ?></textarea>
                            <?php $field = 'contract_terms'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('status') ? 'is-invalid' : '' ?>"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="">Select status...</option>
                                <option value="draft" <?= (old('status') ?? $entity->status) == 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="active" <?= (old('status') ?? $entity->status) == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="completed" <?= (old('status') ?? $entity->status) == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="terminated" <?= (old('status') ?? $entity->status) == 'terminated' ? 'selected' : '' ?>>Terminated</option>
                                <option value="expired" <?= (old('status') ?? $entity->status) == 'expired' ? 'selected' : '' ?>>Expired</option>
                            </select>
                            <?php $field = 'status'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/employment_contracts/<?= $entity->id ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Employment Contract
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
