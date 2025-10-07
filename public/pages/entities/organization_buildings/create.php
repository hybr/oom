<?php
/**
 * Create Organization Building Page
 */

use Entities\OrganizationBranch;
use Entities\PostalAddress;

$pageTitle = 'Add New Organization Building';

// Get all organization branches and postal addresses for dropdowns
$branches = OrganizationBranch::all();
$postalAddresses = PostalAddress::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/organization_buildings">Organization Buildings</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Organization Building
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/organization_buildings/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="organization_branch_id" class="form-label">Organization Branch <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_branch_id') ? 'is-invalid' : '' ?>"
                                    id="organization_branch_id"
                                    name="organization_branch_id"
                                    required
                                    autofocus>
                                <option value="">Select a branch...</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch->id ?>" <?= old('organization_branch_id') == $branch->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($branch->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_branch_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the branch this building belongs to</div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Building Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name') ?>"
                                   required>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the building name</div>
                        </div>

                        <div class="mb-3">
                            <label for="postal_address_id" class="form-label">Postal Address</label>
                            <select class="form-select <?= errors('postal_address_id') ? 'is-invalid' : '' ?>"
                                    id="postal_address_id"
                                    name="postal_address_id">
                                <option value="">Select a postal address...</option>
                                <?php foreach ($postalAddresses as $address): ?>
                                    <option value="<?= $address->id ?>" <?= old('postal_address_id') == $address->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($address->city . ', ' . $address->state) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'postal_address_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the postal address for this building (optional)</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/organization_buildings" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Building
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>