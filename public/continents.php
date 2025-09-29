<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🌍 Continent Management</h1>
            <p class="text-muted">Manage continents with real-time updates</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newContinentBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContinentModal">
                ➕ Add Continent
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Continents</h6>
                            <h4 class="card-title mb-0" id="totalContinents">0</h4>
                        </div>
                        <div class="stats-icon">🌍</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Active</h6>
                            <h4 class="card-title mb-0" id="activeContinents">0</h4>
                        </div>
                        <div class="stats-icon">✅</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Inactive</h6>
                            <h4 class="card-title mb-0" id="inactiveContinents">0</h4>
                        </div>
                        <div class="stats-icon">❌</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Population</h6>
                            <h4 class="card-title mb-0" id="totalPopulation">0</h4>
                        </div>
                        <div class="stats-icon">👥</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-lg-6 col-12 mb-2 mb-lg-0">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchContinents" class="form-control" placeholder="Search continents...">
            </div>
        </div>
        <div class="col-lg-6 col-12 text-lg-end">
            <select id="statusFilter" class="form-select" style="max-width: 200px; margin-left: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Continents Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Continent List</h5>
                </div>
                <div class="card-body">
                    <div id="continentsTable">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading continents...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Continent Modal -->
<div class="modal fade" id="addContinentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Continent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addContinentForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code *</label>
                                <input type="text" class="form-control" id="code" name="code" required maxlength="3" placeholder="e.g., AS, EU">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="area_km2" class="form-label">Area (km²)</label>
                                <input type="number" class="form-control" id="area_km2" name="area_km2" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="population" class="form-label">Population</label>
                                <input type="number" class="form-control" id="population" name="population" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="countries_count" class="form-label">Countries Count</label>
                                <input type="number" class="form-control" id="countries_count" name="countries_count" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="largest_country" class="form-label">Largest Country</label>
                                <input type="text" class="form-control" id="largest_country" name="largest_country">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Continent</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Continent Modal -->
<div class="modal fade" id="editContinentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Continent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editContinentForm">
                <input type="hidden" id="editContinentId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editCode" class="form-label">Code *</label>
                                <input type="text" class="form-control" id="editCode" name="code" required maxlength="3">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editArea_km2" class="form-label">Area (km²)</label>
                                <input type="number" class="form-control" id="editArea_km2" name="area_km2" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPopulation" class="form-label">Population</label>
                                <input type="number" class="form-control" id="editPopulation" name="population" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editCountries_count" class="form-label">Countries Count</label>
                                <input type="number" class="form-control" id="editCountries_count" name="countries_count" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editLargest_country" class="form-label">Largest Country</label>
                                <input type="text" class="form-control" id="editLargest_country" name="largest_country">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editIs_active" name="is_active">
                            <label class="form-check-label" for="editIs_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Continent</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Continent Detail Modal -->
<div class="modal fade" id="viewContinentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🌍 Continent Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Name:</dt>
                                    <dd class="col-sm-8" id="viewName">-</dd>

                                    <dt class="col-sm-4">Code:</dt>
                                    <dd class="col-sm-8" id="viewCode">-</dd>

                                    <dt class="col-sm-4">Area:</dt>
                                    <dd class="col-sm-8" id="viewArea">-</dd>

                                    <dt class="col-sm-4">Population:</dt>
                                    <dd class="col-sm-8" id="viewPopulation">-</dd>

                                    <dt class="col-sm-4">Pop. Density:</dt>
                                    <dd class="col-sm-8" id="viewDensity">-</dd>

                                    <dt class="col-sm-4">Countries:</dt>
                                    <dd class="col-sm-8" id="viewCountriesCount">-</dd>

                                    <dt class="col-sm-4">Largest Country:</dt>
                                    <dd class="col-sm-8" id="viewLargestCountry">-</dd>

                                    <dt class="col-sm-4">Description:</dt>
                                    <dd class="col-sm-8" id="viewDescription">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Status & Metadata</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6 col-md-5">Status:</dt>
                                    <dd class="col-6 col-md-7" id="viewStatus">-</dd>

                                    <dt class="col-6 col-md-5">ID:</dt>
                                    <dd class="col-6 col-md-7" id="viewContinentId">-</dd>

                                    <dt class="col-6 col-md-5">Created:</dt>
                                    <dd class="col-6 col-md-7" id="viewCreatedAt">-</dd>

                                    <dt class="col-6 col-md-5">Updated:</dt>
                                    <dd class="col-6 col-md-7" id="viewUpdatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Continent
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Continent
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>