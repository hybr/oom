<?php
/**
 * Create Application Review Page
 */

use Entities\VacancyApplication;
use Entities\Person;

$pageTitle = 'Add New Review';

// Get all related data for dropdowns
$applications = VacancyApplication::all();
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
                    <li class="breadcrumb-item"><a href="/application_reviews">Reviews</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Review
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/application_reviews/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="application_id" class="form-label">Application <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('application_id') ? 'is-invalid' : '' ?>"
                                    id="application_id"
                                    name="application_id"
                                    required>
                                <option value="">Select an application...</option>
                                <?php foreach ($applications as $app): ?>
                                    <option value="<?= $app->id ?>" <?= old('application_id') == $app->id ? 'selected' : '' ?>>
                                        Application #<?= $app->id ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'application_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="reviewed_by" class="form-label">Reviewed By <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('reviewed_by') ? 'is-invalid' : '' ?>"
                                    id="reviewed_by"
                                    name="reviewed_by"
                                    required>
                                <option value="">Select reviewer...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('reviewed_by') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($person->first_name . ' ' . $person->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'reviewed_by'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="review_date" class="form-label">Review Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control <?= errors('review_date') ? 'is-invalid' : '' ?>"
                                   id="review_date"
                                   name="review_date"
                                   value="<?= old('review_date') ?>"
                                   required>
                            <?php $field = 'review_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="review_notes" class="form-label">Review Notes</label>
                            <textarea class="form-control <?= errors('review_notes') ? 'is-invalid' : '' ?>"
                                      id="review_notes"
                                      name="review_notes"
                                      rows="6"><?= old('review_notes') ?></textarea>
                            <?php $field = 'review_notes'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('status') ? 'is-invalid' : '' ?>"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="">Select status...</option>
                                <option value="Pending" <?= old('status') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Approved" <?= old('status') == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="Rejected" <?= old('status') == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <?php $field = 'status'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/application_reviews" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
