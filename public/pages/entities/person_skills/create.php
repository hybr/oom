<?php
/**
 * Create Person Skill Page
 */

use Entities\Person;
use Entities\PopularSkill;

$pageTitle = 'Add New Person Skill';

// Get all persons and skills for dropdowns
$persons = Person::all();
$skills = PopularSkill::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/person_skills">Person Skills</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Person Skill
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/person_skills/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <?php
                            $fk_label = 'Person';
                            $fk_for = 'person_id';
                            $fk_entity = 'persons';
                            $fk_required = true;
                            $fk_icon = 'bi-person';
                            include __DIR__ . '/../../../../views/components/fk-label.php';
                            ?>
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
                            <div class="form-text">Select the person</div>
                        </div>

                        <div class="mb-3">
                            <?php
                            $fk_label = 'Skill';
                            $fk_for = 'subject_id';
                            $fk_entity = 'popular_skills';
                            $fk_required = true;
                            $fk_icon = 'bi-lightbulb';
                            include __DIR__ . '/../../../../views/components/fk-label.php';
                            ?>
                            <select class="form-select <?= errors('subject_id') ? 'is-invalid' : '' ?>"
                                    id="subject_id"
                                    name="subject_id"
                                    required>
                                <option value="">Select a skill...</option>
                                <?php foreach ($skills as $skill): ?>
                                    <option value="<?= $skill->id ?>" <?= old('subject_id') == $skill->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($skill->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'subject_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the skill</div>
                        </div>

                        <div class="mb-3">
                            <label for="institution" class="form-label">Institution</label>
                            <input type="text"
                                   class="form-control <?= errors('institution') ? 'is-invalid' : '' ?>"
                                   id="institution"
                                   name="institution"
                                   value="<?= old('institution') ?>">
                            <?php $field = 'institution'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the institution where skill was acquired (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <input type="text"
                                   class="form-control <?= errors('level') ? 'is-invalid' : '' ?>"
                                   id="level"
                                   name="level"
                                   value="<?= old('level') ?>"
                                   placeholder="e.g., Beginner, Intermediate, Advanced">
                            <?php $field = 'level'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the proficiency level</div>
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

                        <div class="mb-3">
                            <label for="marks_type" class="form-label">Marks Type</label>
                            <input type="text"
                                   class="form-control <?= errors('marks_type') ? 'is-invalid' : '' ?>"
                                   id="marks_type"
                                   name="marks_type"
                                   value="<?= old('marks_type') ?>"
                                   placeholder="e.g., Percentage, Score, Grade">
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
                                   placeholder="e.g., 90, Pass, A+">
                            <?php $field = 'marks'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the marks/grade received</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/person_skills" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Skill
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>