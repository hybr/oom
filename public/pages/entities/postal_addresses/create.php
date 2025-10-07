<?php
/**
 * Create Postal Address Page
 */

use Entities\Country;

$pageTitle = 'Add New Postal Address';

// Get all countries for the dropdown
$countries = Country::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/postal_addresses">Postal Addresses</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Postal Address
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/postal_addresses/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="first_street" class="form-label">First Street</label>
                            <input type="text"
                                   class="form-control <?= errors('first_street') ? 'is-invalid' : '' ?>"
                                   id="first_street"
                                   name="first_street"
                                   value="<?= old('first_street') ?>"
                                   autofocus>
                            <?php $field = 'first_street'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the first street address line</div>
                        </div>

                        <div class="mb-3">
                            <label for="second_street" class="form-label">Second Street</label>
                            <input type="text"
                                   class="form-control <?= errors('second_street') ? 'is-invalid' : '' ?>"
                                   id="second_street"
                                   name="second_street"
                                   value="<?= old('second_street') ?>">
                            <?php $field = 'second_street'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the second street address line (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="area" class="form-label">Area</label>
                            <input type="text"
                                   class="form-control <?= errors('area') ? 'is-invalid' : '' ?>"
                                   id="area"
                                   name="area"
                                   value="<?= old('area') ?>">
                            <?php $field = 'area'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the area/locality</div>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('city') ? 'is-invalid' : '' ?>"
                                   id="city"
                                   name="city"
                                   value="<?= old('city') ?>"
                                   required>
                            <?php $field = 'city'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the city name</div>
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text"
                                   class="form-control <?= errors('state') ? 'is-invalid' : '' ?>"
                                   id="state"
                                   name="state"
                                   value="<?= old('state') ?>">
                            <?php $field = 'state'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the state/province</div>
                        </div>

                        <div class="mb-3">
                            <label for="pin" class="form-label">PIN/Zip Code</label>
                            <input type="text"
                                   class="form-control <?= errors('pin') ? 'is-invalid' : '' ?>"
                                   id="pin"
                                   name="pin"
                                   value="<?= old('pin') ?>">
                            <?php $field = 'pin'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the postal/zip code</div>
                        </div>

                        <div class="mb-3">
                            <label for="country_id" class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('country_id') ? 'is-invalid' : '' ?>"
                                    id="country_id"
                                    name="country_id"
                                    required>
                                <option value="">Select a country...</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country->id ?>" <?= old('country_id') == $country->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($country->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'country_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the country for this address</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text"
                                       class="form-control <?= errors('latitude') ? 'is-invalid' : '' ?>"
                                       id="latitude"
                                       name="latitude"
                                       value="<?= old('latitude') ?>"
                                       placeholder="e.g., 40.7128">
                                <?php $field = 'latitude'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                                <div class="form-text">Enter the latitude coordinate</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text"
                                       class="form-control <?= errors('longitude') ? 'is-invalid' : '' ?>"
                                       id="longitude"
                                       name="longitude"
                                       value="<?= old('longitude') ?>"
                                       placeholder="e.g., -74.0060">
                                <?php $field = 'longitude'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                                <div class="form-text">Enter the longitude coordinate</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/postal_addresses" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Postal Address
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>