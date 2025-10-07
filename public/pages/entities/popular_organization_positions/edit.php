<?php
/**
 * Edit Popular Organization Position Page
 */

use Entities\PopularOrganizationPosition;
use Entities\PopularOrganizationDepartment;
use Entities\PopularOrganizationTeam;
use Entities\PopularOrganizationDesignation;
use Entities\PopularEducationSubject;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/popular_organization_positions');
    exit;
}

$position = PopularOrganizationPosition::find($id);

if (!$position) {
    $_SESSION['error'] = 'Position not found';
    redirect('/popular_organization_positions');
    exit;
}

$pageTitle = 'Edit ' . $position->name;

// Get all related data for dropdowns
$departments = PopularOrganizationDepartment::all();
$teams = PopularOrganizationTeam::all();
$designations = PopularOrganizationDesignation::all();
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
                    <li class="breadcrumb-item"><a href="/popular_organization_positions">Positions</a></li>
                    <li class="breadcrumb-item"><a href="/popular_organization_positions/<?= $position->id ?>"><?= htmlspecialchars($position->name) ?></a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-pencil"></i> Edit Position
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/popular_organization_positions/<?= $position->id ?>/update" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="version" value="<?= $position->version ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Position Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name', $position->name) ?>"
                                   required
                                   autofocus>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select <?= errors('department_id') ? 'is-invalid' : '' ?>"
                                    id="department_id"
                                    name="department_id">
                                <option value="">Select a department...</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department->id ?>" <?= old('department_id', $position->department_id) == $department->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($department->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'department_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="team_id" class="form-label">Team</label>
                            <select class="form-select <?= errors('team_id') ? 'is-invalid' : '' ?>"
                                    id="team_id"
                                    name="team_id">
                                <option value="">Select a team...</option>
                                <?php foreach ($teams as $team): ?>
                                    <option value="<?= $team->id ?>" <?= old('team_id', $position->team_id) == $team->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($team->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'team_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="designation_id" class="form-label">Designation</label>
                            <select class="form-select <?= errors('designation_id') ? 'is-invalid' : '' ?>"
                                    id="designation_id"
                                    name="designation_id">
                                <option value="">Select a designation...</option>
                                <?php foreach ($designations as $designation): ?>
                                    <option value="<?= $designation->id ?>" <?= old('designation_id', $position->designation_id) == $designation->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($designation->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'designation_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="minimum_education_level" class="form-label">Minimum Education Level</label>
                            <input type="text"
                                   class="form-control <?= errors('minimum_education_level') ? 'is-invalid' : '' ?>"
                                   id="minimum_education_level"
                                   name="minimum_education_level"
                                   value="<?= old('minimum_education_level', $position->minimum_education_level) ?>">
                            <?php $field = 'minimum_education_level'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="minimum_subject_id" class="form-label">Minimum Subject</label>
                            <select class="form-select <?= errors('minimum_subject_id') ? 'is-invalid' : '' ?>"
                                    id="minimum_subject_id"
                                    name="minimum_subject_id">
                                <option value="">Select a subject...</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= $subject->id ?>" <?= old('minimum_subject_id', $position->minimum_subject_id) == $subject->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subject->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'minimum_subject_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?= errors('description') ? 'is-invalid' : '' ?>"
                                      id="description"
                                      name="description"
                                      rows="5"><?= old('description', $position->description) ?></textarea>
                            <?php $field = 'description'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                Last updated: <?= date('F d, Y h:i A', strtotime($position->updated_at)) ?>
                                (Version: <?= $position->version ?>)
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/popular_organization_positions/<?= $position->id ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Position
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
