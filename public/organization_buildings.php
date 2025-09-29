<?php
require_once '../includes/header.php';
require_once '../includes/sub_menu.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏗️ Organization Buildings</h1>
            <p class="text-muted">Manage organization buildings, properties, and real estate assets</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button id="newBuildingBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBuildingModal">
                    ➕ Add Building
                </button>
                <button id="exportBtn" class="btn btn-outline-secondary">
                    📊 Export
                </button>
                <button id="mapViewBtn" class="btn btn-outline-info">
                    🗺️ Map View
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Buildings</h6>
                            <h4 class="card-title mb-0" id="totalBuildings">0</h4>
                        </div>
                        <div class="stats-icon">🏗️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Occupied</h6>
                            <h4 class="card-title mb-0" id="occupiedBuildings">0</h4>
                        </div>
                        <div class="stats-icon">✅</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Available</h6>
                            <h4 class="card-title mb-0" id="availableBuildings">0</h4>
                        </div>
                        <div class="stats-icon">🏠</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Owned</h6>
                            <h4 class="card-title mb-0" id="ownedBuildings">0</h4>
                        </div>
                        <div class="stats-icon">🏛️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Green Certified</h6>
                            <h4 class="card-title mb-0" id="greenBuildings">0</h4>
                        </div>
                        <div class="stats-icon">🌿</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Area</h6>
                            <h4 class="card-title mb-0" id="totalArea">0</h4>
                        </div>
                        <div class="stats-icon">📐</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="row mb-3">
        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchBuildings" class="form-control" placeholder="Search buildings, addresses, codes...">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Status</option>
                    <option value="planning">Planning</option>
                    <option value="under_construction">Under Construction</option>
                    <option value="completed">Completed</option>
                    <option value="occupied">Occupied</option>
                    <option value="partially_occupied">Partially Occupied</option>
                    <option value="vacant">Vacant</option>
                    <option value="under_renovation">Under Renovation</option>
                    <option value="maintenance">Maintenance</option>
                </select>
                <select id="typeFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Types</option>
                    <option value="office">Office</option>
                    <option value="retail">Retail</option>
                    <option value="warehouse">Warehouse</option>
                    <option value="manufacturing">Manufacturing</option>
                    <option value="mixed_use">Mixed Use</option>
                    <option value="medical">Medical</option>
                    <option value="industrial">Industrial</option>
                </select>
                <select id="ownershipFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Ownership</option>
                    <option value="owned">Owned</option>
                    <option value="leased">Leased</option>
                    <option value="rented">Rented</option>
                    <option value="managed">Managed</option>
                </select>
                <select id="branchFilter" class="form-select" style="max-width: 180px;">
                    <option value="">All Branches</option>
                </select>
                <select id="conditionFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Conditions</option>
                    <option value="excellent">Excellent</option>
                    <option value="very_good">Very Good</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                </select>
                <div class="btn-group" role="group">
                    <input type="checkbox" class="btn-check" id="accessibleFilter" autocomplete="off">
                    <label class="btn btn-outline-info" for="accessibleFilter">♿ Accessible</label>

                    <input type="checkbox" class="btn-check" id="greenFilter" autocomplete="off">
                    <label class="btn btn-outline-success" for="greenFilter">🌿 Green</label>

                    <input type="checkbox" class="btn-check" id="featuredFilter" autocomplete="off">
                    <label class="btn btn-outline-warning" for="featuredFilter">⭐ Featured</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Buildings Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Buildings</h5>
                    <small class="text-muted" id="buildingCount">0 buildings</small>
                </div>
                <div class="card-body">
                    <div id="buildingsDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading buildings...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Building Modal -->
<div class="modal fade" id="addBuildingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Building</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBuildingForm">
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="organization_branch_id" class="form-label">Organization Branch *</label>
                                <select class="form-select" id="organization_branch_id" name="organization_branch_id" required>
                                    <option value="">Select Branch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="postal_address_id" class="form-label">Postal Address *</label>
                                <select class="form-select" id="postal_address_id" name="postal_address_id" required>
                                    <option value="">Select Address</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Building Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">Friendly name for the building</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="building_type" class="form-label">Building Type *</label>
                                <select class="form-select" id="building_type" name="building_type" required>
                                    <option value="office">Office Building</option>
                                    <option value="retail">Retail Space</option>
                                    <option value="warehouse">Warehouse</option>
                                    <option value="manufacturing">Manufacturing Facility</option>
                                    <option value="mixed_use">Mixed Use</option>
                                    <option value="medical">Medical Facility</option>
                                    <option value="educational">Educational Facility</option>
                                    <option value="hospitality">Hospitality</option>
                                    <option value="residential">Residential</option>
                                    <option value="industrial">Industrial</option>
                                    <option value="laboratory">Laboratory</option>
                                    <option value="data_center">Data Center</option>
                                    <option value="parking">Parking Structure</option>
                                    <option value="storage">Storage Facility</option>
                                    <option value="recreational">Recreational</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Building Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                       placeholder="Leave empty for auto-generation">
                                <div class="form-text">Unique identifier (auto-generated if empty)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ownership_type" class="form-label">Ownership Type</label>
                                <select class="form-select" id="ownership_type" name="ownership_type">
                                    <option value="leased" selected>Leased</option>
                                    <option value="owned">Owned</option>
                                    <option value="rented">Rented</option>
                                    <option value="subleased">Subleased</option>
                                    <option value="managed">Managed</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="franchise">Franchise</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Detailed description of the building"></textarea>
                    </div>

                    <!-- Physical Details -->
                    <h6 class="mb-3 mt-4">Physical Details</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="construction_year" class="form-label">Construction Year</label>
                                <input type="number" class="form-control" id="construction_year" name="construction_year"
                                       min="1800" max="2050">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="renovation_year" class="form-label">Last Renovation</label>
                                <input type="number" class="form-control" id="renovation_year" name="renovation_year"
                                       min="1800" max="2050">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="total_floors" class="form-label">Total Floors</label>
                                <input type="number" class="form-control" id="total_floors" name="total_floors"
                                       min="1" max="200">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="basement_levels" class="form-label">Basement Levels</label>
                                <input type="number" class="form-control" id="basement_levels" name="basement_levels"
                                       min="0" max="10">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="building_condition" class="form-label">Building Condition</label>
                                <select class="form-select" id="building_condition" name="building_condition">
                                    <option value="excellent">Excellent</option>
                                    <option value="very_good">Very Good</option>
                                    <option value="good" selected>Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                    <option value="needs_repair">Needs Repair</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="architectural_style" class="form-label">Architectural Style</label>
                                <input type="text" class="form-control" id="architectural_style" name="architectural_style"
                                       placeholder="e.g., Modern, Contemporary, Colonial">
                            </div>
                        </div>
                    </div>

                    <!-- Area Details -->
                    <h6 class="mb-3 mt-4">Area Details (Square Feet)</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="total_area_sqft" class="form-label">Total Area</label>
                                <input type="number" class="form-control" id="total_area_sqft" name="total_area_sqft"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="usable_area_sqft" class="form-label">Usable Area</label>
                                <input type="number" class="form-control" id="usable_area_sqft" name="usable_area_sqft"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="rentable_area_sqft" class="form-label">Rentable Area</label>
                                <input type="number" class="form-control" id="rentable_area_sqft" name="rentable_area_sqft"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="storage_area_sqft" class="form-label">Storage Area</label>
                                <input type="number" class="form-control" id="storage_area_sqft" name="storage_area_sqft"
                                       min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Facilities -->
                    <h6 class="mb-3 mt-4">Facilities & Amenities</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="parking_spaces" class="form-label">Parking Spaces</label>
                                <input type="number" class="form-control" id="parking_spaces" name="parking_spaces"
                                       min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="parking_type" class="form-label">Parking Type</label>
                                <select class="form-select" id="parking_type" name="parking_type">
                                    <option value="surface">Surface Parking</option>
                                    <option value="garage">Parking Garage</option>
                                    <option value="underground">Underground</option>
                                    <option value="street">Street Parking</option>
                                    <option value="valet">Valet</option>
                                    <option value="none">No Parking</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="elevator_count" class="form-label">Elevators</label>
                                <input type="number" class="form-control" id="elevator_count" name="elevator_count"
                                       min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="restroom_count" class="form-label">Restrooms</label>
                                <input type="number" class="form-control" id="restroom_count" name="restroom_count"
                                       min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <h6 class="mb-3 mt-4">Financial Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="monthly_rent" class="form-label">Monthly Rent</label>
                                <input type="number" class="form-control" id="monthly_rent" name="monthly_rent"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="annual_rent" class="form-label">Annual Rent</label>
                                <input type="number" class="form-control" id="annual_rent" name="annual_rent"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="rent_currency" class="form-label">Currency</label>
                                <select class="form-select" id="rent_currency" name="rent_currency">
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="CAD">CAD - Canadian Dollar</option>
                                    <option value="AUD">AUD - Australian Dollar</option>
                                    <option value="INR">INR - Indian Rupee</option>
                                    <option value="CNY">CNY - Chinese Yuan</option>
                                    <option value="JPY">JPY - Japanese Yen</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lease_start_date" class="form-label">Lease Start Date</label>
                                <input type="date" class="form-control" id="lease_start_date" name="lease_start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lease_end_date" class="form-label">Lease End Date</label>
                                <input type="date" class="form-control" id="lease_end_date" name="lease_end_date">
                            </div>
                        </div>
                    </div>

                    <!-- Building Features -->
                    <h6 class="mb-3 mt-4">Building Features</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="accessibility_compliant" name="accessibility_compliant" checked>
                                <label class="form-check-label" for="accessibility_compliant">
                                    ADA Compliant
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_energy_efficient" name="is_energy_efficient">
                                <label class="form-check-label" for="is_energy_efficient">
                                    Energy Efficient
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_green_certified" name="is_green_certified">
                                <label class="form-check-label" for="is_green_certified">
                                    Green Certified
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="backup_generator" name="backup_generator">
                                <label class="form-check-label" for="backup_generator">
                                    Backup Generator
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="wifi_coverage" name="wifi_coverage" checked>
                                <label class="form-check-label" for="wifi_coverage">
                                    WiFi Coverage
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="conference_facilities" name="conference_facilities">
                                <label class="form-check-label" for="conference_facilities">
                                    Conference Facilities
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_headquarters" name="is_headquarters">
                                <label class="form-check-label" for="is_headquarters">
                                    Headquarters Building
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_flagship" name="is_flagship">
                                <label class="form-check-label" for="is_flagship">
                                    Flagship Building
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_historic" name="is_historic">
                                <label class="form-check-label" for="is_historic">
                                    Historic Building
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Visibility Settings -->
                    <h6 class="mb-3 mt-4">Visibility & Access</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">
                                    Featured Building
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" checked>
                                <label class="form-check-label" for="is_public">
                                    Public Building
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="display_online" name="display_online" checked>
                                <label class="form-check-label" for="display_online">
                                    Display Online
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allows_tours" name="allows_tours" checked>
                                <label class="form-check-label" for="allows_tours">
                                    Allow Tours
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Building</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Building Modal -->
<div class="modal fade" id="editBuildingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Building</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBuildingForm">
                <input type="hidden" id="editBuildingId" name="id">
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editOrganization_branch_id" class="form-label">Organization Branch *</label>
                                <select class="form-select" id="editOrganization_branch_id" name="organization_branch_id" required>
                                    <option value="">Select Branch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPostal_address_id" class="form-label">Postal Address *</label>
                                <select class="form-select" id="editPostal_address_id" name="postal_address_id" required>
                                    <option value="">Select Address</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Building Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editBuilding_type" class="form-label">Building Type *</label>
                                <select class="form-select" id="editBuilding_type" name="building_type" required>
                                    <option value="office">Office Building</option>
                                    <option value="retail">Retail Space</option>
                                    <option value="warehouse">Warehouse</option>
                                    <option value="manufacturing">Manufacturing Facility</option>
                                    <option value="mixed_use">Mixed Use</option>
                                    <option value="medical">Medical Facility</option>
                                    <option value="educational">Educational Facility</option>
                                    <option value="hospitality">Hospitality</option>
                                    <option value="residential">Residential</option>
                                    <option value="industrial">Industrial</option>
                                    <option value="laboratory">Laboratory</option>
                                    <option value="data_center">Data Center</option>
                                    <option value="parking">Parking Structure</option>
                                    <option value="storage">Storage Facility</option>
                                    <option value="recreational">Recreational</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    <h6 class="mb-3 mt-4">Status Management</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Building Status</label>
                                <select class="form-select" id="editStatus" name="status">
                                    <option value="planning">Planning</option>
                                    <option value="under_construction">Under Construction</option>
                                    <option value="completed">Completed</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="partially_occupied">Partially Occupied</option>
                                    <option value="vacant">Vacant</option>
                                    <option value="under_renovation">Under Renovation</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="condemned">Condemned</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editOwnership_type" class="form-label">Ownership Type</label>
                                <select class="form-select" id="editOwnership_type" name="ownership_type">
                                    <option value="owned">Owned</option>
                                    <option value="leased">Leased</option>
                                    <option value="rented">Rented</option>
                                    <option value="subleased">Subleased</option>
                                    <option value="managed">Managed</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="franchise">Franchise</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editBuilding_condition" class="form-label">Building Condition</label>
                                <select class="form-select" id="editBuilding_condition" name="building_condition">
                                    <option value="excellent">Excellent</option>
                                    <option value="very_good">Very Good</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                    <option value="needs_repair">Needs Repair</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Scores -->
                    <h6 class="mb-3 mt-4">Performance Scores (%)</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editCondition_score" class="form-label">Condition Score</label>
                                <input type="number" class="form-control" id="editCondition_score" name="condition_score"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editEnergy_efficiency_score" class="form-label">Energy Efficiency</label>
                                <input type="number" class="form-control" id="editEnergy_efficiency_score" name="energy_efficiency_score"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editAccessibility_score" class="form-label">Accessibility</label>
                                <input type="number" class="form-control" id="editAccessibility_score" name="accessibility_score"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editOccupancy_rate" class="form-label">Occupancy Rate</label>
                                <input type="number" class="form-control" id="editOccupancy_rate" name="occupancy_rate"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Building Flags -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_headquarters" name="is_headquarters">
                                <label class="form-check-label" for="editIs_headquarters">
                                    Headquarters Building
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_flagship" name="is_flagship">
                                <label class="form-check-label" for="editIs_flagship">
                                    Flagship Building
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_featured" name="is_featured">
                                <label class="form-check-label" for="editIs_featured">
                                    Featured Building
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_available" name="is_available">
                                <label class="form-check-label" for="editIs_available">
                                    Available
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_accessible" name="is_accessible">
                                <label class="form-check-label" for="editIs_accessible">
                                    Accessible
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_green_certified" name="is_green_certified">
                                <label class="form-check-label" for="editIs_green_certified">
                                    Green Certified
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Building</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Building Modal -->
<div class="modal fade" id="viewBuildingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏗️ Building Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Building Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Name:</dt>
                                            <dd class="col-sm-7" id="viewName">-</dd>

                                            <dt class="col-sm-5">Code:</dt>
                                            <dd class="col-sm-7" id="viewCode">-</dd>

                                            <dt class="col-sm-5">Branch:</dt>
                                            <dd class="col-sm-7" id="viewBranch">-</dd>

                                            <dt class="col-sm-5">Type:</dt>
                                            <dd class="col-sm-7" id="viewType">-</dd>

                                            <dt class="col-sm-5">Ownership:</dt>
                                            <dd class="col-sm-7" id="viewOwnership">-</dd>

                                            <dt class="col-sm-5">Condition:</dt>
                                            <dd class="col-sm-7" id="viewCondition">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Built:</dt>
                                            <dd class="col-sm-7" id="viewBuiltYear">-</dd>

                                            <dt class="col-sm-5">Floors:</dt>
                                            <dd class="col-sm-7" id="viewFloors">-</dd>

                                            <dt class="col-sm-5">Total Area:</dt>
                                            <dd class="col-sm-7" id="viewTotalArea">-</dd>

                                            <dt class="col-sm-5">Rentable:</dt>
                                            <dd class="col-sm-7" id="viewRentableArea">-</dd>

                                            <dt class="col-sm-5">Monthly Rent:</dt>
                                            <dd class="col-sm-7" id="viewMonthlyRent">-</dd>

                                            <dt class="col-sm-5">Occupancy:</dt>
                                            <dd class="col-sm-7" id="viewOccupancy">-</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <dt>Address:</dt>
                                    <dd id="viewAddress">-</dd>
                                </div>
                                <div class="mt-3">
                                    <dt>Description:</dt>
                                    <dd id="viewDescription">-</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Status & Actions</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Status:</dt>
                                    <dd class="col-6" id="viewStatus">-</dd>

                                    <dt class="col-6">Available:</dt>
                                    <dd class="col-6" id="viewAvailable">-</dd>

                                    <dt class="col-6">Occupied:</dt>
                                    <dd class="col-6" id="viewOccupied">-</dd>

                                    <dt class="col-6">Headquarters:</dt>
                                    <dd class="col-6" id="viewHeadquarters">-</dd>

                                    <dt class="col-6">Flagship:</dt>
                                    <dd class="col-6" id="viewFlagship">-</dd>

                                    <dt class="col-6">Green:</dt>
                                    <dd class="col-6" id="viewGreen">-</dd>

                                    <dt class="col-6">ID:</dt>
                                    <dd class="col-6" id="viewBuildingId">-</dd>

                                    <dt class="col-6">Created:</dt>
                                    <dd class="col-6" id="viewCreatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Building
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="viewOccupyBtn">
                                            🏢 Toggle Occupancy
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" id="viewFeatureBtn">
                                            ⭐ Toggle Featured
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" id="viewMapBtn">
                                            🗺️ View on Map
                                        </button>
                                        <hr>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Building
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Facilities</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Parking:</dt>
                                    <dd class="col-6" id="viewParking">-</dd>

                                    <dt class="col-6">Elevators:</dt>
                                    <dd class="col-6" id="viewElevators">-</dd>

                                    <dt class="col-6">Restrooms:</dt>
                                    <dd class="col-6" id="viewRestrooms">-</dd>

                                    <dt class="col-6">Accessible:</dt>
                                    <dd class="col-6" id="viewAccessible">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Performance Scores</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Condition:</dt>
                                    <dd class="col-6" id="viewConditionScore">-</dd>

                                    <dt class="col-6">Energy Eff.:</dt>
                                    <dd class="col-6" id="viewEnergyScore">-</dd>

                                    <dt class="col-6">Accessibility:</dt>
                                    <dd class="col-6" id="viewAccessibilityScore">-</dd>

                                    <dt class="col-6">Security:</dt>
                                    <dd class="col-6" id="viewSecurityScore">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lease Information -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Lease Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <dt>Lease Start:</dt>
                                        <dd id="viewLeaseStart">-</dd>
                                    </div>
                                    <div class="col-md-3">
                                        <dt>Lease End:</dt>
                                        <dd id="viewLeaseEnd">-</dd>
                                    </div>
                                    <div class="col-md-3">
                                        <dt>Annual Rent:</dt>
                                        <dd id="viewAnnualRent">-</dd>
                                    </div>
                                    <div class="col-md-3">
                                        <dt>Rent per Sq Ft:</dt>
                                        <dd id="viewRentPerSqFt">-</dd>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let buildings = [];
    let filteredBuildings = [];
    let branches = [];
    let addresses = [];

    // Load initial data
    loadBranches();
    loadAddresses();
    loadBuildings();

    // Event listeners for search and filters
    document.getElementById('searchBuildings').addEventListener('input', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('ownershipFilter').addEventListener('change', applyFilters);
    document.getElementById('branchFilter').addEventListener('change', applyFilters);
    document.getElementById('conditionFilter').addEventListener('change', applyFilters);
    document.getElementById('accessibleFilter').addEventListener('change', applyFilters);
    document.getElementById('greenFilter').addEventListener('change', applyFilters);
    document.getElementById('featuredFilter').addEventListener('change', applyFilters);

    // Form submissions
    document.getElementById('addBuildingForm').addEventListener('submit', handleAddBuilding);
    document.getElementById('editBuildingForm').addEventListener('submit', handleEditBuilding);

    function loadBranches() {
        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getAllOrganizationBranches'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                branches = data.data;
                populateBranchDropdowns();
                populateBranchFilter();
            }
        })
        .catch(error => {
            console.error('Error loading branches:', error);
        });
    }

    function loadAddresses() {
        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getAllPostalAddresses'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addresses = data.data;
                populateAddressDropdowns();
            }
        })
        .catch(error => {
            console.error('Error loading addresses:', error);
        });
    }

    function populateBranchDropdowns() {
        const addSelect = document.getElementById('organization_branch_id');
        const editSelect = document.getElementById('editOrganization_branch_id');

        [addSelect, editSelect].forEach(select => {
            select.innerHTML = '<option value="">Select Branch</option>';
            branches.forEach(branch => {
                const option = document.createElement('option');
                option.value = branch.id;
                option.textContent = branch.full_name || branch.display_name || branch.name;
                select.appendChild(option);
            });
        });
    }

    function populateAddressDropdowns() {
        const addSelect = document.getElementById('postal_address_id');
        const editSelect = document.getElementById('editPostal_address_id');

        [addSelect, editSelect].forEach(select => {
            select.innerHTML = '<option value="">Select Address</option>';
            addresses.forEach(address => {
                const option = document.createElement('option');
                option.value = address.id;
                option.textContent = address.formatted_address || address.short_address;
                select.appendChild(option);
            });
        });
    }

    function populateBranchFilter() {
        const filterSelect = document.getElementById('branchFilter');
        filterSelect.innerHTML = '<option value="">All Branches</option>';
        branches.forEach(branch => {
            const option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.full_name || branch.display_name || branch.name;
            filterSelect.appendChild(option);
        });
    }

    function loadBuildings() {
        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getAllOrganizationBuildings'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                buildings = data.data;
                applyFilters();
                updateStatistics();
            }
        })
        .catch(error => {
            console.error('Error loading buildings:', error);
            document.getElementById('buildingsDisplay').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-danger">Error loading buildings: ${error.message}</p>
                </div>
            `;
        });
    }

    function applyFilters() {
        const search = document.getElementById('searchBuildings').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const ownershipFilter = document.getElementById('ownershipFilter').value;
        const branchFilter = document.getElementById('branchFilter').value;
        const conditionFilter = document.getElementById('conditionFilter').value;
        const accessibleOnly = document.getElementById('accessibleFilter').checked;
        const greenOnly = document.getElementById('greenFilter').checked;
        const featuredOnly = document.getElementById('featuredFilter').checked;

        filteredBuildings = buildings.filter(building => {
            // Search filter
            if (search && !buildingMatchesSearch(building, search)) return false;

            // Status filter
            if (statusFilter && building.status !== statusFilter) return false;

            // Type filter
            if (typeFilter && building.building_type !== typeFilter) return false;

            // Ownership filter
            if (ownershipFilter && building.ownership_type !== ownershipFilter) return false;

            // Branch filter
            if (branchFilter && building.organization_branch_id != branchFilter) return false;

            // Condition filter
            if (conditionFilter && building.building_condition !== conditionFilter) return false;

            // Accessible only
            if (accessibleOnly && !building.is_accessible) return false;

            // Green only
            if (greenOnly && !building.is_green_certified) return false;

            // Featured only
            if (featuredOnly && !building.is_featured) return false;

            return true;
        });

        displayBuildings();
    }

    function buildingMatchesSearch(building, search) {
        const searchFields = [
            building.name,
            building.code,
            building.description,
            building.branch_name,
            building.organization_name,
            building.address_text,
            building.short_address
        ];

        return searchFields.some(field =>
            field && field.toLowerCase().includes(search)
        );
    }

    function displayBuildings() {
        const container = document.getElementById('buildingsDisplay');
        const countElement = document.getElementById('buildingCount');

        countElement.textContent = `${filteredBuildings.length} building${filteredBuildings.length !== 1 ? 's' : ''}`;

        if (filteredBuildings.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-muted">No buildings found matching your criteria.</p>
                </div>
            `;
            return;
        }

        // Responsive display: desktop table, mobile cards
        const isDesktop = window.innerWidth >= 992;

        if (isDesktop) {
            displayBuildingsTable(container);
        } else {
            displayBuildingsCards(container);
        }
    }

    function displayBuildingsTable(container) {
        let html = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Building Name</th>
                            <th>Branch</th>
                            <th>Type</th>
                            <th>Address</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        filteredBuildings.forEach(building => {
            const statusBadge = getStatusBadge(building.status);
            const typeBadge = getTypeBadge(building.building_type);
            const headquartersBadge = building.is_headquarters ? '<span class="badge bg-primary">🏛️ HQ</span>' : '';
            const flagshipBadge = building.is_flagship ? '<span class="badge bg-warning">⭐ Flagship</span>' : '';
            const greenBadge = building.is_green_certified ? '<span class="badge bg-success">🌿</span>' : '';
            const accessibleBadge = building.is_accessible ? '<span class="badge bg-info">♿</span>' : '';

            html += `
                <tr>
                    <td>
                        <div class="fw-bold">${building.display_name || 'Unnamed Building'}</div>
                        <div class="small text-muted">${building.code || 'No Code'}</div>
                        ${headquartersBadge} ${flagshipBadge} ${greenBadge} ${accessibleBadge}
                    </td>
                    <td>${building.branch_name || '-'}</td>
                    <td>${typeBadge}</td>
                    <td>${building.short_address || '-'}</td>
                    <td>${building.formatted_total_area || '-'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" onclick="viewBuilding(${building.id})" title="View Details">
                                👁️
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="editBuilding(${building.id})" title="Edit">
                                ✏️
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteBuilding(${building.id})" title="Delete">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        container.innerHTML = html;
    }

    function displayBuildingsCards(container) {
        let html = '<div class="row">';

        filteredBuildings.forEach(building => {
            const statusBadge = getStatusBadge(building.status);
            const typeBadge = getTypeBadge(building.building_type);
            const headquartersBadge = building.is_headquarters ? '<span class="badge bg-primary">🏛️</span>' : '';
            const flagshipBadge = building.is_flagship ? '<span class="badge bg-warning">⭐</span>' : '';
            const greenBadge = building.is_green_certified ? '<span class="badge bg-success">🌿</span>' : '';

            html += `
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">${building.display_name || 'Unnamed Building'}</h6>
                                <div>
                                    ${headquartersBadge} ${flagshipBadge} ${greenBadge}
                                </div>
                            </div>
                            <p class="card-text text-muted small">${building.branch_name || '-'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    ${typeBadge} ${statusBadge}
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary btn-sm" onclick="viewBuilding(${building.id})">
                                        View Details
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editBuilding(${building.id})">
                                        ✏️
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;
    }

    function getStatusBadge(status) {
        const statusConfig = {
            planning: 'bg-info',
            under_construction: 'bg-warning',
            completed: 'bg-primary',
            occupied: 'bg-success',
            partially_occupied: 'bg-warning',
            vacant: 'bg-secondary',
            under_renovation: 'bg-info',
            maintenance: 'bg-warning',
            condemned: 'bg-danger',
            demolished: 'bg-dark',
            archived: 'bg-secondary'
        };
        return `<span class="badge ${statusConfig[status] || 'bg-secondary'}">${status.replace('_', ' ')}</span>`;
    }

    function getTypeBadge(type) {
        const typeConfig = {
            office: '🏢',
            retail: '🏪',
            warehouse: '📦',
            manufacturing: '🏭',
            mixed_use: '🏬',
            medical: '🏥',
            educational: '🎓',
            hospitality: '🏨',
            residential: '🏠',
            industrial: '🏭',
            laboratory: '🔬',
            data_center: '💻',
            parking: '🅿️',
            storage: '📦',
            recreational: '🏊'
        };
        return `<span class="badge bg-primary">${typeConfig[type] || '🏢'} ${type.replace('_', ' ')}</span>`;
    }

    function updateStatistics() {
        document.getElementById('totalBuildings').textContent = buildings.length;
        document.getElementById('occupiedBuildings').textContent = buildings.filter(b => b.status === 'occupied' || b.status === 'partially_occupied').length;
        document.getElementById('availableBuildings').textContent = buildings.filter(b => b.is_available).length;
        document.getElementById('ownedBuildings').textContent = buildings.filter(b => b.ownership_type === 'owned').length;
        document.getElementById('greenBuildings').textContent = buildings.filter(b => b.is_green_certified).length;

        // Calculate total area
        const totalAreaSqft = buildings.reduce((sum, building) => {
            return sum + (parseFloat(building.total_area_sqft) || 0);
        }, 0);

        let totalAreaDisplay = '0 sq ft';
        if (totalAreaSqft >= 1000000) {
            totalAreaDisplay = (totalAreaSqft / 1000000).toFixed(1) + 'M sq ft';
        } else if (totalAreaSqft >= 1000) {
            totalAreaDisplay = (totalAreaSqft / 1000).toFixed(1) + 'K sq ft';
        } else if (totalAreaSqft > 0) {
            totalAreaDisplay = Math.round(totalAreaSqft).toLocaleString() + ' sq ft';
        }

        document.getElementById('totalArea').textContent = totalAreaDisplay;
    }

    function handleAddBuilding(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Convert checkboxes
        const checkboxes = ['accessibility_compliant', 'is_energy_efficient', 'is_green_certified', 'backup_generator',
                           'wifi_coverage', 'conference_facilities', 'is_headquarters', 'is_flagship', 'is_historic',
                           'is_featured', 'is_public', 'display_online', 'allows_tours'];

        checkboxes.forEach(checkbox => {
            data[checkbox] = document.getElementById(checkbox).checked ? 1 : 0;
        });

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'createOrganizationBuilding',
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('addBuildingModal')).hide();
                document.getElementById('addBuildingForm').reset();
                loadBuildings();
                alert('Building created successfully!');
            } else {
                alert('Error: ' + (result.error || 'Failed to create building'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating building');
        });
    }

    function handleEditBuilding(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Convert checkboxes
        const checkboxes = ['is_headquarters', 'is_flagship', 'is_featured', 'is_available', 'is_accessible', 'is_green_certified'];

        checkboxes.forEach(checkbox => {
            data[checkbox] = document.getElementById('edit' + checkbox.charAt(0).toUpperCase() + checkbox.slice(1)).checked ? 1 : 0;
        });

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'updateOrganizationBuilding',
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('editBuildingModal')).hide();
                loadBuildings();
                alert('Building updated successfully!');
            } else {
                alert('Error: ' + (result.error || 'Failed to update building'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating building');
        });
    }

    // Global functions for button actions
    window.viewBuilding = function(id) {
        const building = buildings.find(b => b.id === id);
        if (!building) return;

        // Populate view modal
        document.getElementById('viewName').textContent = building.display_name || 'Unnamed Building';
        document.getElementById('viewCode').textContent = building.code || '-';
        document.getElementById('viewBranch').textContent = building.branch_name || '-';
        document.getElementById('viewType').innerHTML = getTypeBadge(building.building_type);
        document.getElementById('viewOwnership').textContent = building.ownership_type || '-';
        document.getElementById('viewCondition').textContent = building.building_condition || '-';
        document.getElementById('viewBuiltYear').textContent = building.construction_year ? building.construction_year + (building.age_description ? ' (' + building.age_description + ')' : '') : '-';
        document.getElementById('viewFloors').textContent = building.total_floors || '-';
        document.getElementById('viewTotalArea').textContent = building.formatted_total_area || '-';
        document.getElementById('viewRentableArea').textContent = building.formatted_rentable_area || '-';
        document.getElementById('viewMonthlyRent').textContent = building.formatted_monthly_rent || '-';
        document.getElementById('viewOccupancy').textContent = building.occupancy_rate ? building.occupancy_rate + '%' : '-';
        document.getElementById('viewAddress').textContent = building.address_text || '-';
        document.getElementById('viewDescription').textContent = building.description || '-';

        document.getElementById('viewStatus').innerHTML = getStatusBadge(building.status);
        document.getElementById('viewAvailable').innerHTML = building.is_available ?
            '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewOccupied').innerHTML = building.is_occupied ?
            '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewHeadquarters').innerHTML = building.is_headquarters ?
            '<span class="badge bg-primary">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewFlagship').innerHTML = building.is_flagship ?
            '<span class="badge bg-warning">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewGreen').innerHTML = building.is_green_certified ?
            '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';

        document.getElementById('viewBuildingId').textContent = building.id;
        document.getElementById('viewCreatedAt').textContent = building.created_at || '-';

        // Facilities
        document.getElementById('viewParking').textContent = building.parking_spaces ?
            building.parking_spaces + ' spaces (' + building.parking_type + ')' : '-';
        document.getElementById('viewElevators').textContent = building.elevator_count || '0';
        document.getElementById('viewRestrooms').textContent = building.restroom_count || '-';
        document.getElementById('viewAccessible').innerHTML = building.is_accessible ?
            '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';

        // Performance scores
        document.getElementById('viewConditionScore').textContent = building.condition_score ? building.condition_score + '%' : '-';
        document.getElementById('viewEnergyScore').textContent = building.energy_efficiency_score ? building.energy_efficiency_score + '%' : '-';
        document.getElementById('viewAccessibilityScore').textContent = building.accessibility_score ? building.accessibility_score + '%' : '-';
        document.getElementById('viewSecurityScore').textContent = building.security_score ? building.security_score + '%' : '-';

        // Lease information
        document.getElementById('viewLeaseStart').textContent = building.lease_start_date || '-';
        document.getElementById('viewLeaseEnd').textContent = building.lease_end_date || '-';
        document.getElementById('viewAnnualRent').textContent = building.formatted_annual_rent || '-';
        document.getElementById('viewRentPerSqFt').textContent = building.rent_per_sqft ? '$' + building.rent_per_sqft + '/sq ft' : '-';

        // Set up action buttons
        document.getElementById('viewEditBtn').onclick = () => editBuilding(id);
        document.getElementById('viewDeleteBtn').onclick = () => deleteBuilding(id);

        const modal = new bootstrap.Modal(document.getElementById('viewBuildingModal'));
        modal.show();
    };

    window.editBuilding = function(id) {
        const building = buildings.find(b => b.id === id);
        if (!building) return;

        // Populate edit form
        document.getElementById('editBuildingId').value = building.id;
        document.getElementById('editOrganization_branch_id').value = building.organization_branch_id || '';
        document.getElementById('editPostal_address_id').value = building.postal_address_id || '';
        document.getElementById('editName').value = building.name || '';
        document.getElementById('editBuilding_type').value = building.building_type || '';
        document.getElementById('editStatus').value = building.status || '';
        document.getElementById('editOwnership_type').value = building.ownership_type || '';
        document.getElementById('editBuilding_condition').value = building.building_condition || '';
        document.getElementById('editCondition_score').value = building.condition_score || '';
        document.getElementById('editEnergy_efficiency_score').value = building.energy_efficiency_score || '';
        document.getElementById('editAccessibility_score').value = building.accessibility_score || '';
        document.getElementById('editOccupancy_rate').value = building.occupancy_rate || '';
        document.getElementById('editIs_headquarters').checked = building.is_headquarters;
        document.getElementById('editIs_flagship').checked = building.is_flagship;
        document.getElementById('editIs_featured').checked = building.is_featured;
        document.getElementById('editIs_available').checked = building.is_available;
        document.getElementById('editIs_accessible').checked = building.is_accessible;
        document.getElementById('editIs_green_certified').checked = building.is_green_certified;

        const modal = new bootstrap.Modal(document.getElementById('editBuildingModal'));
        modal.show();
    };

    window.deleteBuilding = function(id) {
        if (!confirm('Are you sure you want to delete this building?')) return;

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'deleteOrganizationBuilding',
                id: id
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                loadBuildings();
                alert('Building deleted successfully!');

                // Close any open modals
                ['viewBuildingModal', 'editBuildingModal'].forEach(modalId => {
                    const modalEl = document.getElementById(modalId);
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                });
            } else {
                alert('Error: ' + (result.error || 'Failed to delete building'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting building');
        });
    };

    // Handle window resize for responsive display
    window.addEventListener('resize', () => {
        displayBuildings();
    });
});
</script>