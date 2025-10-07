<?php
/**
 * Create Popular Organization Team Page
 */

use Entities\PopularOrganizationDepartment;

$pageTitle = 'Add New Popular Organization Team';

// Get all departments for the dropdown
$departments = PopularOrganizationDepartment::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/popular_organization_teams">Popular Organization Teams</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Popular Organization Team
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/popular_organization_teams/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Team Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name') ?>"
                                   required
                                   autofocus>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the name of the team (e.g., "Backend Development", "Marketing")</div>
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select <?= errors('department_id') ? 'is-invalid' : '' ?>"
                                    id="department_id"
                                    name="department_id">
                                <option value="">Select a department...</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department->id ?>" <?= old('department_id') == $department->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($department->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'department_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the department this team belongs to (optional)</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/popular_organization_teams" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>