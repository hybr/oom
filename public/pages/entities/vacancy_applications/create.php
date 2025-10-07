<?php
/**
 * Create Vacancy Application Page
 */

use Entities\OrganizationVacancy;
use Entities\Person;

$pageTitle = 'Add New Application';

// Get all related data for dropdowns
$vacancies = OrganizationVacancy::all();
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
                    <li class="breadcrumb-item"><a href="/vacancy_applications">Applications</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Application
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/vacancy_applications/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="vacancy_id" class="form-label">Vacancy <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('vacancy_id') ? 'is-invalid' : '' ?>"
                                    id="vacancy_id"
                                    name="vacancy_id"
                                    required>
                                <option value="">Select a vacancy...</option>
                                <?php foreach ($vacancies as $vacancy): ?>
                                    <option value="<?= $vacancy->id ?>" <?= old('vacancy_id') == $vacancy->id ? 'selected' : '' ?>>
                                        Vacancy #<?= $vacancy->id ?> - <?php $pos = $vacancy->getPopularPosition(); echo $pos ? htmlspecialchars($pos->name) : 'N/A'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'vacancy_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="applicant_id" class="form-label">Applicant <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('applicant_id') ? 'is-invalid' : '' ?>"
                                    id="applicant_id"
                                    name="applicant_id"
                                    required>
                                <option value="">Select an applicant...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('applicant_id') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($person->first_name . ' ' . $person->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'applicant_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="application_date" class="form-label">Application Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control <?= errors('application_date') ? 'is-invalid' : '' ?>"
                                   id="application_date"
                                   name="application_date"
                                   value="<?= old('application_date') ?>"
                                   required>
                            <?php $field = 'application_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('status') ? 'is-invalid' : '' ?>"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="">Select status...</option>
                                <option value="Applied" <?= old('status') == 'Applied' ? 'selected' : '' ?>>Applied</option>
                                <option value="Shortlisted" <?= old('status') == 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                <option value="Rejected" <?= old('status') == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="Selected" <?= old('status') == 'Selected' ? 'selected' : '' ?>>Selected</option>
                                <option value="Withdrawn" <?= old('status') == 'Withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                            </select>
                            <?php $field = 'status'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="resume_url" class="form-label">Resume URL</label>
                            <input type="text"
                                   class="form-control <?= errors('resume_url') ? 'is-invalid' : '' ?>"
                                   id="resume_url"
                                   name="resume_url"
                                   value="<?= old('resume_url') ?>">
                            <?php $field = 'resume_url'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter URL to resume</div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Cover Letter</label>
                            <textarea class="form-control <?= errors('cover_letter') ? 'is-invalid' : '' ?>"
                                      id="cover_letter"
                                      name="cover_letter"
                                      rows="8"><?= old('cover_letter') ?></textarea>
                            <?php $field = 'cover_letter'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/vacancy_applications" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
