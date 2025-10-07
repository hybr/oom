<?php
/**
 * Create Person Education Page
 */

use Entities\Person;

$pageTitle = 'Add New Person Education';

// Get all persons for the dropdown
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
                    <li class="breadcrumb-item"><a href="/person_education">Person Education</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Person Education
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/person_education/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="person_id" class="form-label">Person <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('person_id') ? 'is-invalid' : '' ?>"
                                    id="person_id"
                                    name="person_id"
                                    required
                                    autofocus>
                                <option value="">Select a person...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('person_id') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($person->first_name . ' ' . $person->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'person_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the person for this education record</div>
                        </div>

                        <div class="mb-3">
                            <label for="institution" class="form-label">Institution <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('institution') ? 'is-invalid' : '' ?>"
                                   id="institution"
                                   name="institution"
                                   value="<?= old('institution') ?>"
                                   required>
                            <?php $field = 'institution'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the name of the educational institution</div>
                        </div>

                        <div class="mb-3">
                            <label for="education_level" class="form-label">Education Level <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('education_level') ? 'is-invalid' : '' ?>"
                                   id="education_level"
                                   name="education_level"
                                   value="<?= old('education_level') ?>"
                                   required
                                   placeholder="e.g., Bachelor's, Master's, PhD">
                            <?php $field = 'education_level'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the level of education</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date"
                                       class="form-control <?= errors('start_date') ? 'is-invalid' : '' ?>"
                                       id="start_date"
                                       name="start_date"
                                       value="<?= old('start_date') ?>">
                                <?php $field = 'start_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                                <div class="form-text">Enter the start date</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="complete_date" class="form-label">Complete Date</label>
                                <input type="date"
                                       class="form-control <?= errors('complete_date') ? 'is-invalid' : '' ?>"
                                       id="complete_date"
                                       name="complete_date"
                                       value="<?= old('complete_date') ?>">
                                <?php $field = 'complete_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                                <div class="form-text">Enter the completion date (leave blank if ongoing)</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/person_education" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Education
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>