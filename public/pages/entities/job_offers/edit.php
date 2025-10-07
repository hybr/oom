<?php
/**
 * Edit Job Offer Page
 */

use Entities\JobOffer;
use Entities\VacancyApplication;
use Entities\Person;

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('/job_offers');
    exit;
}

$entity = Entities\JobOffer::find($id);
if (!$entity) {
    redirect('/job_offers');
    exit;
}

$pageTitle = 'Edit Job Offer #' . $entity->id;

// Get all vacancy applications for the dropdown
$applications = VacancyApplication::all();

// Get all persons for the offered_by dropdown
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
                    <li class="breadcrumb-item"><a href="/job_offers">Job Offers</a></li>
                    <li class="breadcrumb-item"><a href="/job_offers/<?= $entity->id ?>">Job Offer #<?= $entity->id ?></a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-pencil"></i> Edit Job Offer
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/job_offers/<?= $entity->id ?>/update" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="application_id" class="form-label">Vacancy Application <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('application_id') ? 'is-invalid' : '' ?>"
                                    id="application_id"
                                    name="application_id"
                                    required>
                                <option value="">Select an application...</option>
                                <?php foreach ($applications as $application): ?>
                                    <option value="<?= $application->id ?>"
                                        <?= (old('application_id') ?? $entity->application_id) == $application->id ? 'selected' : '' ?>>
                                        ID: <?= $application->id ?> - <?= htmlspecialchars($application->id ?? 'Application') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'application_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="offered_by" class="form-label">Offered By <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('offered_by') ? 'is-invalid' : '' ?>"
                                    id="offered_by"
                                    name="offered_by"
                                    required>
                                <option value="">Select a person...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>"
                                        <?= (old('offered_by') ?? $entity->offered_by) == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'offered_by'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="position_title" class="form-label">Position Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('position_title') ? 'is-invalid' : '' ?>"
                                   id="position_title"
                                   name="position_title"
                                   value="<?= old('position_title') ?? $entity->position_title ?>"
                                   required>
                            <?php $field = 'position_title'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="salary_offered" class="form-label">Salary Offered</label>
                            <input type="number"
                                   step="0.01"
                                   class="form-control <?= errors('salary_offered') ? 'is-invalid' : '' ?>"
                                   id="salary_offered"
                                   name="salary_offered"
                                   value="<?= old('salary_offered') ?? $entity->salary_offered ?>">
                            <?php $field = 'salary_offered'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="offer_date" class="form-label">Offer Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control <?= errors('offer_date') ? 'is-invalid' : '' ?>"
                                   id="offer_date"
                                   name="offer_date"
                                   value="<?= old('offer_date') ?? ($entity->offer_date ? date('Y-m-d', strtotime($entity->offer_date)) : '') ?>"
                                   required>
                            <?php $field = 'offer_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="joining_date" class="form-label">Joining Date</label>
                            <input type="date"
                                   class="form-control <?= errors('joining_date') ? 'is-invalid' : '' ?>"
                                   id="joining_date"
                                   name="joining_date"
                                   value="<?= old('joining_date') ?? ($entity->joining_date ? date('Y-m-d', strtotime($entity->joining_date)) : '') ?>">
                            <?php $field = 'joining_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('status') ? 'is-invalid' : '' ?>"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="">Select status...</option>
                                <option value="pending" <?= (old('status') ?? $entity->status) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="accepted" <?= (old('status') ?? $entity->status) == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="declined" <?= (old('status') ?? $entity->status) == 'declined' ? 'selected' : '' ?>>Declined</option>
                                <option value="expired" <?= (old('status') ?? $entity->status) == 'expired' ? 'selected' : '' ?>>Expired</option>
                                <option value="withdrawn" <?= (old('status') ?? $entity->status) == 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                            </select>
                            <?php $field = 'status'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/job_offers/<?= $entity->id ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Job Offer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
