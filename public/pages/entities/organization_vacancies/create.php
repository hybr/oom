<?php
/**
 * Create Organization Vacancy Page
 */

use Entities\Organization;
use Entities\PopularOrganizationPosition;
use Entities\Person;

$pageTitle = 'Add New Vacancy';

// Get all related data for dropdowns
$organizations = Organization::all();
$positions = PopularOrganizationPosition::all();
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
                    <li class="breadcrumb-item"><a href="/organization_vacancies">Vacancies</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Vacancy
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/organization_vacancies/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_id') ? 'is-invalid' : '' ?>"
                                    id="organization_id"
                                    name="organization_id"
                                    required>
                                <option value="">Select an organization...</option>
                                <?php foreach ($organizations as $organization): ?>
                                    <option value="<?= $organization->id ?>" <?= old('organization_id') == $organization->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($organization->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="popular_position_id" class="form-label">Position <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('popular_position_id') ? 'is-invalid' : '' ?>"
                                    id="popular_position_id"
                                    name="popular_position_id"
                                    required>
                                <option value="">Select a position...</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?= $position->id ?>" <?= old('popular_position_id') == $position->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($position->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'popular_position_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="opening_date" class="form-label">Opening Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control <?= errors('opening_date') ? 'is-invalid' : '' ?>"
                                   id="opening_date"
                                   name="opening_date"
                                   value="<?= old('opening_date') ?>"
                                   required>
                            <?php $field = 'opening_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="closing_date" class="form-label">Closing Date</label>
                            <input type="date"
                                   class="form-control <?= errors('closing_date') ? 'is-invalid' : '' ?>"
                                   id="closing_date"
                                   name="closing_date"
                                   value="<?= old('closing_date') ?>">
                            <?php $field = 'closing_date'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('status') ? 'is-invalid' : '' ?>"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="">Select status...</option>
                                <option value="Open" <?= old('status') == 'Open' ? 'selected' : '' ?>>Open</option>
                                <option value="Closed" <?= old('status') == 'Closed' ? 'selected' : '' ?>>Closed</option>
                                <option value="On Hold" <?= old('status') == 'On Hold' ? 'selected' : '' ?>>On Hold</option>
                            </select>
                            <?php $field = 'status'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <select class="form-select <?= errors('created_by') ? 'is-invalid' : '' ?>"
                                    id="created_by"
                                    name="created_by">
                                <option value="">Select person...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('created_by') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($person->first_name . ' ' . $person->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'created_by'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/organization_vacancies" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Vacancy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
