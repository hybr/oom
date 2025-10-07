<?php
/**
 * Create Organization Branch Page
 */

use Entities\Organization;

$pageTitle = 'Add New Organization Branch';

// Get all organizations for the dropdown
$organizations = Organization::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/organization_branches">Organization Branches</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Organization Branch
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/organization_branches/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="organization_id" class="form-label">Organization <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_id') ? 'is-invalid' : '' ?>"
                                    id="organization_id"
                                    name="organization_id"
                                    required
                                    autofocus>
                                <option value="">Select an organization...</option>
                                <?php foreach ($organizations as $organization): ?>
                                    <option value="<?= $organization->id ?>" <?= old('organization_id') == $organization->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($organization->short_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the organization this branch belongs to</div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name') ?>"
                                   required>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the branch name</div>
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Branch Code</label>
                            <input type="text"
                                   class="form-control <?= errors('code') ? 'is-invalid' : '' ?>"
                                   id="code"
                                   name="code"
                                   value="<?= old('code') ?>"
                                   placeholder="e.g., BR001">
                            <?php $field = 'code'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter a unique branch code (optional)</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/organization_branches" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Branch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>