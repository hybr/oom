<?php
/**
 * Create Person Education Subject Page
 */

use Entities\PersonEducation;
use Entities\PopularEducationSubject;

$pageTitle = 'Add New Person Education Subject';

// Get all person education records and subjects for dropdowns
$personEducations = PersonEducation::all();
$subjects = PopularEducationSubject::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/person_education_subjects">Person Education Subjects</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Person Education Subject
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/person_education_subjects/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="person_education_id" class="form-label">Person Education <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('person_education_id') ? 'is-invalid' : '' ?>"
                                    id="person_education_id"
                                    name="person_education_id"
                                    required
                                    autofocus>
                                <option value="">Select a person education record...</option>
                                <?php foreach ($personEducations as $education): ?>
                                    <option value="<?= $education->id ?>" <?= old('person_education_id') == $education->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($education->institution . ' - ' . $education->education_level) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'person_education_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the education record</div>
                        </div>

                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('subject_id') ? 'is-invalid' : '' ?>"
                                    id="subject_id"
                                    name="subject_id"
                                    required>
                                <option value="">Select a subject...</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= $subject->id ?>" <?= old('subject_id') == $subject->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subject->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'subject_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the subject studied</div>
                        </div>

                        <div class="mb-3">
                            <label for="marks_type" class="form-label">Marks Type</label>
                            <input type="text"
                                   class="form-control <?= errors('marks_type') ? 'is-invalid' : '' ?>"
                                   id="marks_type"
                                   name="marks_type"
                                   value="<?= old('marks_type') ?>"
                                   placeholder="e.g., Percentage, GPA, Grade">
                            <?php $field = 'marks_type'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the type of marks/grade system</div>
                        </div>

                        <div class="mb-3">
                            <label for="marks" class="form-label">Marks</label>
                            <input type="text"
                                   class="form-control <?= errors('marks') ? 'is-invalid' : '' ?>"
                                   id="marks"
                                   name="marks"
                                   value="<?= old('marks') ?>"
                                   placeholder="e.g., 85, 3.8, A">
                            <?php $field = 'marks'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the marks/grade received</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/person_education_subjects" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>