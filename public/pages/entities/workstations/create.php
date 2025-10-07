<?php
/**
 * Create Workstation Page
 */

use Entities\OrganizationBuilding;

$pageTitle = 'Add New Workstation';

// Get all organization buildings for the dropdown
$buildings = OrganizationBuilding::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/workstations">Workstations</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Workstation
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/workstations/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="organization_building_id" class="form-label">Organization Building <span class="text-danger">*</span></label>
                            <select class="form-select <?= errors('organization_building_id') ? 'is-invalid' : '' ?>"
                                    id="organization_building_id"
                                    name="organization_building_id"
                                    required
                                    autofocus>
                                <option value="">Select a building...</option>
                                <?php foreach ($buildings as $building): ?>
                                    <option value="<?= $building->id ?>" <?= old('organization_building_id') == $building->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($building->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'organization_building_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the building this workstation is located in</div>
                        </div>

                        <div class="mb-3">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="text"
                                   class="form-control <?= errors('floor') ? 'is-invalid' : '' ?>"
                                   id="floor"
                                   name="floor"
                                   value="<?= old('floor') ?>"
                                   placeholder="e.g., 1, 2, G, B1">
                            <?php $field = 'floor'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the floor number or level</div>
                        </div>

                        <div class="mb-3">
                            <label for="room" class="form-label">Room</label>
                            <input type="text"
                                   class="form-control <?= errors('room') ? 'is-invalid' : '' ?>"
                                   id="room"
                                   name="room"
                                   value="<?= old('room') ?>"
                                   placeholder="e.g., 101, A-12">
                            <?php $field = 'room'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the room number or identifier</div>
                        </div>

                        <div class="mb-3">
                            <label for="workstation_number" class="form-label">Workstation Number</label>
                            <input type="text"
                                   class="form-control <?= errors('workstation_number') ? 'is-invalid' : '' ?>"
                                   id="workstation_number"
                                   name="workstation_number"
                                   value="<?= old('workstation_number') ?>"
                                   placeholder="e.g., WS001, Desk-5">
                            <?php $field = 'workstation_number'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the workstation number or identifier</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/workstations" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Workstation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>