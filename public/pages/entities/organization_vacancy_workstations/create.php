<?php
/**
 * Create Organization Vacancy Workstation Page
 */

use Entities\OrganizationVacancy;
use Entities\Workstation;

$pageTitle = 'Add New Vacancy Workstation';

// Get all related data for dropdowns
$vacancies = OrganizationVacancy::all();
$workstations = Workstation::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/organization_vacancy_workstations">Vacancy Workstations</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Vacancy Workstation
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/organization_vacancy_workstations/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="organization_vacancy_id" class="form-label">Vacancy <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_vacancy_id') ? 'is-invalid' : '' ?>"
                                    id="organization_vacancy_id"
                                    name="organization_vacancy_id"
                                    required>
                                <option value="">Select a vacancy...</option>
                                <?php foreach ($vacancies as $vacancy): ?>
                                    <option value="<?= $vacancy->id ?>" <?= old('organization_vacancy_id') == $vacancy->id ? 'selected' : '' ?>>
                                        Vacancy #<?= $vacancy->id ?> - <?php $pos = $vacancy->getPopularPosition(); echo $pos ? htmlspecialchars($pos->name) : 'N/A'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_vacancy_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="mb-3">
                            <label for="organization_workstation_id" class="form-label">Workstation <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_workstation_id') ? 'is-invalid' : '' ?>"
                                    id="organization_workstation_id"
                                    name="organization_workstation_id"
                                    required>
                                <option value="">Select a workstation...</option>
                                <?php foreach ($workstations as $workstation): ?>
                                    <option value="<?= $workstation->id ?>" <?= old('organization_workstation_id') == $workstation->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($workstation->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_workstation_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/organization_vacancy_workstations" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
