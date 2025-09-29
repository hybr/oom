<?php
require_once '../includes/header.php';
require_once '../includes/sub_menu.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">📍 Postal Addresses</h1>
            <p class="text-muted">Manage postal addresses with geographical coordinates and map integration</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button id="newAddressBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    ➕ Add Address
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
                            <h6 class="card-subtitle mb-1 small">Total Addresses</h6>
                            <h4 class="card-title mb-0" id="totalAddresses">0</h4>
                        </div>
                        <div class="stats-icon">📍</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Active</h6>
                            <h4 class="card-title mb-0" id="activeAddresses">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Verified</h6>
                            <h4 class="card-title mb-0" id="verifiedAddresses">0</h4>
                        </div>
                        <div class="stats-icon">🎗️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">With Coordinates</h6>
                            <h4 class="card-title mb-0" id="geoLocatedAddresses">0</h4>
                        </div>
                        <div class="stats-icon">🌐</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Default</h6>
                            <h4 class="card-title mb-0" id="defaultAddresses">0</h4>
                        </div>
                        <div class="stats-icon">⭐</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Countries</h6>
                            <h4 class="card-title mb-0" id="totalCountries">0</h4>
                        </div>
                        <div class="stats-icon">🏴</div>
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
                <input type="text" id="searchAddresses" class="form-control" placeholder="Search addresses, cities, streets...">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                </select>
                <select id="typeFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Types</option>
                    <option value="home">Home</option>
                    <option value="work">Work</option>
                    <option value="business">Business</option>
                    <option value="shipping">Shipping</option>
                    <option value="billing">Billing</option>
                    <option value="mailing">Mailing</option>
                    <option value="other">Other</option>
                </select>
                <select id="countryFilter" class="form-select" style="max-width: 160px;">
                    <option value="">All Countries</option>
                </select>
                <select id="verificationFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Verification</option>
                    <option value="manual">Manual</option>
                    <option value="geocoding">Geocoding</option>
                    <option value="gps">GPS</option>
                    <option value="postal_service">Postal Service</option>
                </select>
                <div class="btn-group" role="group">
                    <input type="checkbox" class="btn-check" id="verifiedFilter" autocomplete="off">
                    <label class="btn btn-outline-success" for="verifiedFilter">🎗️ Verified</label>

                    <input type="checkbox" class="btn-check" id="defaultFilter" autocomplete="off">
                    <label class="btn btn-outline-warning" for="defaultFilter">⭐ Default</label>

                    <input type="checkbox" class="btn-check" id="coordinatesFilter" autocomplete="off">
                    <label class="btn btn-outline-info" for="coordinatesFilter">🌐 Has GPS</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Addresses Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Addresses</h5>
                    <small class="text-muted" id="addressCount">0 addresses</small>
                </div>
                <div class="card-body">
                    <div id="addressesDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading addresses...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAddressForm">
                <div class="modal-body">
                    <div class="row">
                        <!-- Address Form (Left Column) -->
                        <div class="col-md-6">
                            <!-- Basic Information -->
                            <h6 class="mb-3">Address Information</h6>

                            <div class="mb-3">
                                <label for="name" class="form-label">Address Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="e.g., Home, Office, Headquarters">
                                <div class="form-text">Optional friendly name for this address</div>
                            </div>

                            <div class="mb-3">
                                <label for="street_address_1" class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="street_address_1" name="street_address_1" required
                                       placeholder="123 Main Street">
                            </div>

                            <div class="mb-3">
                                <label for="street_address_2" class="form-label">Street Address Line 2</label>
                                <input type="text" class="form-control" id="street_address_2" name="street_address_2"
                                       placeholder="Apt 4B, Suite 200, etc.">
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="postal_code" name="postal_code">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="state_province" class="form-label">State/Province</label>
                                        <input type="text" class="form-control" id="state_province" name="state_province">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="addCountry" class="form-label">Country *</label>
                                        <select class="form-select" id="addCountry" name="country_id" required>
                                            <option value="">Select Country</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Coordinates -->
                            <h6 class="mb-3 mt-4">Geographic Coordinates *</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude *</label>
                                        <input type="number" class="form-control" id="latitude" name="latitude"
                                               step="0.000001" min="-90" max="90" required
                                               placeholder="e.g., 40.7128">
                                        <div class="form-text">Range: -90 to 90</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude *</label>
                                        <input type="number" class="form-control" id="longitude" name="longitude"
                                               step="0.000001" min="-180" max="180" required
                                               placeholder="e.g., -74.0060">
                                        <div class="form-text">Range: -180 to 180</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="selectOnMapBtn">
                                        🗺️ Select on Map
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="getCurrentLocationBtn">
                                        📍 Use Current Location
                                    </button>
                                </div>
                            </div>

                            <!-- Address Classification -->
                            <h6 class="mb-3 mt-4">Classification</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address_type" class="form-label">Address Type</label>
                                        <select class="form-select" id="address_type" name="address_type">
                                            <option value="home">Home</option>
                                            <option value="work">Work</option>
                                            <option value="business">Business</option>
                                            <option value="shipping">Shipping</option>
                                            <option value="billing">Billing</option>
                                            <option value="mailing">Mailing</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="timezone" class="form-label">Timezone</label>
                                        <input type="text" class="form-control" id="timezone" name="timezone"
                                               placeholder="e.g., America/New_York">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Details -->
                            <h6 class="mb-3 mt-4">Additional Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="building_name" class="form-label">Building Name</label>
                                        <input type="text" class="form-control" id="building_name" name="building_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="floor" class="form-label">Floor</label>
                                        <input type="text" class="form-control" id="floor" name="floor">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apartment_unit" class="form-label">Apartment/Unit</label>
                                        <input type="text" class="form-control" id="apartment_unit" name="apartment_unit">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="landmark" class="form-label">Landmark</label>
                                        <input type="text" class="form-control" id="landmark" name="landmark"
                                               placeholder="Near XYZ Mall">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="delivery_notes" class="form-label">Delivery Notes</label>
                                <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="2"
                                          placeholder="Special delivery instructions"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="access_instructions" class="form-label">Access Instructions</label>
                                <textarea class="form-control" id="access_instructions" name="access_instructions" rows="2"
                                          placeholder="How to access this location"></textarea>
                            </div>

                            <!-- Settings -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                        <label class="form-check-label" for="is_default">
                                            Default Address
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified">
                                        <label class="form-check-label" for="is_verified">
                                            Verified Address
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Map Column (Right Column) -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Map Location</h6>
                            <div class="map-container" style="height: 600px; border: 1px solid #ddd; border-radius: 0.375rem;">
                                <div id="addressMap" style="height: 100%; width: 100;"></div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Click on the map to set coordinates, or use the "Select on Map" button</small>
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-info" id="coordinateDisplay" style="display: none;">
                                    <strong>Selected Coordinates:</strong><br>
                                    <span id="displayLatitude">-</span>, <span id="displayLongitude">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm">
                <input type="hidden" id="editAddressId" name="id">
                <div class="modal-body">
                    <!-- Similar structure to add modal but with edit prefixes -->
                    <div class="row">
                        <!-- Address Form (Left Column) -->
                        <div class="col-md-6">
                            <!-- Basic Information -->
                            <h6 class="mb-3">Address Information</h6>

                            <div class="mb-3">
                                <label for="editName" class="form-label">Address Name</label>
                                <input type="text" class="form-control" id="editName" name="name">
                            </div>

                            <div class="mb-3">
                                <label for="editStreet_address_1" class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="editStreet_address_1" name="street_address_1" required>
                            </div>

                            <div class="mb-3">
                                <label for="editStreet_address_2" class="form-label">Street Address Line 2</label>
                                <input type="text" class="form-control" id="editStreet_address_2" name="street_address_2">
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="editCity" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="editCity" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editPostal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="editPostal_code" name="postal_code">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editState_province" class="form-label">State/Province</label>
                                        <input type="text" class="form-control" id="editState_province" name="state_province">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editCountry" class="form-label">Country *</label>
                                        <select class="form-select" id="editCountry" name="country_id" required>
                                            <option value="">Select Country</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Coordinates -->
                            <h6 class="mb-3 mt-4">Geographic Coordinates *</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editLatitude" class="form-label">Latitude *</label>
                                        <input type="number" class="form-control" id="editLatitude" name="latitude"
                                               step="0.000001" min="-90" max="90" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editLongitude" class="form-label">Longitude *</label>
                                        <input type="number" class="form-control" id="editLongitude" name="longitude"
                                               step="0.000001" min="-180" max="180" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="editSelectOnMapBtn">
                                        🗺️ Select on Map
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="editGetCurrentLocationBtn">
                                        📍 Use Current Location
                                    </button>
                                </div>
                            </div>

                            <!-- Status Management -->
                            <h6 class="mb-3 mt-4">Status & Classification</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editStatus" class="form-label">Status</label>
                                        <select class="form-select" id="editStatus" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editAddress_type" class="form-label">Type</label>
                                        <select class="form-select" id="editAddress_type" name="address_type">
                                            <option value="home">Home</option>
                                            <option value="work">Work</option>
                                            <option value="business">Business</option>
                                            <option value="shipping">Shipping</option>
                                            <option value="billing">Billing</option>
                                            <option value="mailing">Mailing</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editVerification_method" class="form-label">Verification</label>
                                        <select class="form-select" id="editVerification_method" name="verification_method">
                                            <option value="manual">Manual</option>
                                            <option value="geocoding">Geocoding</option>
                                            <option value="gps">GPS</option>
                                            <option value="postal_service">Postal Service</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="editIs_default" name="is_default">
                                        <label class="form-check-label" for="editIs_default">
                                            Default Address
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="editIs_verified" name="is_verified">
                                        <label class="form-check-label" for="editIs_verified">
                                            Verified Address
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Map Column (Right Column) -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Map Location</h6>
                            <div class="map-container" style="height: 500px; border: 1px solid #ddd; border-radius: 0.375rem;">
                                <div id="editAddressMap" style="height: 100%; width: 100%;"></div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Click on the map to update coordinates</small>
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-info" id="editCoordinateDisplay" style="display: none;">
                                    <strong>Current Coordinates:</strong><br>
                                    <span id="editDisplayLatitude">-</span>, <span id="editDisplayLongitude">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Address Modal -->
<div class="modal fade" id="viewAddressModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📍 Address Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Address Information</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Name:</dt>
                                    <dd class="col-sm-8" id="viewName">-</dd>

                                    <dt class="col-sm-4">Type:</dt>
                                    <dd class="col-sm-8" id="viewType">-</dd>

                                    <dt class="col-sm-4">Address:</dt>
                                    <dd class="col-sm-8" id="viewFormattedAddress">-</dd>

                                    <dt class="col-sm-4">Country:</dt>
                                    <dd class="col-sm-8" id="viewCountry">-</dd>

                                    <dt class="col-sm-4">Coordinates:</dt>
                                    <dd class="col-sm-8" id="viewCoordinates">-</dd>

                                    <dt class="col-sm-4">Timezone:</dt>
                                    <dd class="col-sm-8" id="viewTimezone">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Status & Actions</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Status:</dt>
                                    <dd class="col-6" id="viewStatus">-</dd>

                                    <dt class="col-6">Verified:</dt>
                                    <dd class="col-6" id="viewVerified">-</dd>

                                    <dt class="col-6">Default:</dt>
                                    <dd class="col-6" id="viewDefault">-</dd>

                                    <dt class="col-6">ID:</dt>
                                    <dd class="col-6" id="viewAddressId">-</dd>

                                    <dt class="col-6">Created:</dt>
                                    <dd class="col-6" id="viewCreatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Address
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="viewVerifyBtn">
                                            🎗️ Verify Address
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" id="viewDefaultBtn">
                                            ⭐ Toggle Default
                                        </button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-info btn-sm" id="viewGoogleMapsBtn">
                                                🗺️ Google Maps
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm" id="viewAppleMapsBtn">
                                                🍎 Apple Maps
                                            </button>
                                        </div>
                                        <hr>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Address
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Display -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Location Map</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 400px; border: 1px solid #ddd; border-radius: 0.375rem;">
                                    <div id="viewAddressMap" style="height: 100%; width: 100%;"></div>
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
                                <h6 class="card-title mb-0">Building Details</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Building:</dt>
                                    <dd class="col-6" id="viewBuildingName">-</dd>

                                    <dt class="col-6">Floor:</dt>
                                    <dd class="col-6" id="viewFloor">-</dd>

                                    <dt class="col-6">Apartment:</dt>
                                    <dd class="col-6" id="viewApartment">-</dd>

                                    <dt class="col-6">Landmark:</dt>
                                    <dd class="col-6" id="viewLandmark">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Delivery Information</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewDeliveryNotes">
                                    <dt>Delivery Notes:</dt>
                                    <dd class="mb-2">-</dd>
                                </div>
                                <div id="viewAccessInstructions">
                                    <dt>Access Instructions:</dt>
                                    <dd>-</dd>
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

<!-- Map View Modal -->
<div class="modal fade" id="mapViewModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🗺️ All Addresses - Map View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="allAddressesMap" style="height: 100vh;"></div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>

<!-- Leaflet CSS and JS for Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let addresses = [];
    let filteredAddresses = [];
    let countries = [];

    // Map instances
    let addressMap = null;
    let editAddressMap = null;
    let viewAddressMap = null;
    let allAddressesMap = null;

    // Map markers
    let currentMarker = null;
    let editCurrentMarker = null;

    // Load initial data
    loadCountries();
    loadAddresses();

    // Event listeners for search and filters
    document.getElementById('searchAddresses').addEventListener('input', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('countryFilter').addEventListener('change', applyFilters);
    document.getElementById('verificationFilter').addEventListener('change', applyFilters);
    document.getElementById('verifiedFilter').addEventListener('change', applyFilters);
    document.getElementById('defaultFilter').addEventListener('change', applyFilters);
    document.getElementById('coordinatesFilter').addEventListener('change', applyFilters);

    // Modal event listeners
    document.getElementById('addAddressModal').addEventListener('shown.bs.modal', function() {
        initializeAddMap();
    });

    document.getElementById('editAddressModal').addEventListener('shown.bs.modal', function() {
        initializeEditMap();
    });

    document.getElementById('viewAddressModal').addEventListener('shown.bs.modal', function() {
        setTimeout(() => initializeViewMap(), 100);
    });

    document.getElementById('mapViewModal').addEventListener('shown.bs.modal', function() {
        initializeAllAddressesMap();
    });

    // Form submissions
    document.getElementById('addAddressForm').addEventListener('submit', handleAddAddress);
    document.getElementById('editAddressForm').addEventListener('submit', handleEditAddress);

    // Map selection buttons
    document.getElementById('selectOnMapBtn').addEventListener('click', function() {
        alert('Click directly on the map to set coordinates');
    });

    document.getElementById('getCurrentLocationBtn').addEventListener('click', getCurrentLocation);
    document.getElementById('editSelectOnMapBtn').addEventListener('click', function() {
        alert('Click directly on the map to update coordinates');
    });
    document.getElementById('editGetCurrentLocationBtn').addEventListener('click', getCurrentLocationForEdit);
    document.getElementById('mapViewBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('mapViewModal'));
        modal.show();
    });

    function loadCountries() {
        // Load countries for dropdowns
        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getAllCountries'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                countries = data.data;
                populateCountryDropdowns();
                populateCountryFilter();
            }
        })
        .catch(error => {
            console.error('Error loading countries:', error);
        });
    }

    function populateCountryDropdowns() {
        const addSelect = document.getElementById('addCountry');
        const editSelect = document.getElementById('editCountry');

        [addSelect, editSelect].forEach(select => {
            select.innerHTML = '<option value="">Select Country</option>';
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.id;
                option.textContent = country.name;
                select.appendChild(option);
            });
        });
    }

    function populateCountryFilter() {
        const filterSelect = document.getElementById('countryFilter');
        filterSelect.innerHTML = '<option value="">All Countries</option>';
        countries.forEach(country => {
            const option = document.createElement('option');
            option.value = country.id;
            option.textContent = country.name;
            filterSelect.appendChild(option);
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
                applyFilters();
                updateStatistics();
            }
        })
        .catch(error => {
            console.error('Error loading addresses:', error);
            document.getElementById('addressesDisplay').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-danger">Error loading addresses: ${error.message}</p>
                </div>
            `;
        });
    }

    function applyFilters() {
        const search = document.getElementById('searchAddresses').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const countryFilter = document.getElementById('countryFilter').value;
        const verificationFilter = document.getElementById('verificationFilter').value;
        const verifiedOnly = document.getElementById('verifiedFilter').checked;
        const defaultOnly = document.getElementById('defaultFilter').checked;
        const coordinatesOnly = document.getElementById('coordinatesFilter').checked;

        filteredAddresses = addresses.filter(address => {
            // Search filter
            if (search && !addressMatchesSearch(address, search)) return false;

            // Status filter
            if (statusFilter && address.status !== statusFilter) return false;

            // Type filter
            if (typeFilter && address.address_type !== typeFilter) return false;

            // Country filter
            if (countryFilter && address.country_id != countryFilter) return false;

            // Verification method filter
            if (verificationFilter && address.verification_method !== verificationFilter) return false;

            // Verified only
            if (verifiedOnly && !address.is_verified) return false;

            // Default only
            if (defaultOnly && !address.is_default) return false;

            // Coordinates only
            if (coordinatesOnly && (!address.latitude || !address.longitude)) return false;

            return true;
        });

        displayAddresses();
    }

    function addressMatchesSearch(address, search) {
        const searchFields = [
            address.name,
            address.street_address_1,
            address.street_address_2,
            address.city,
            address.state_province,
            address.postal_code,
            address.country_name,
            address.formatted_address
        ];

        return searchFields.some(field =>
            field && field.toLowerCase().includes(search)
        );
    }

    function displayAddresses() {
        const container = document.getElementById('addressesDisplay');
        const countElement = document.getElementById('addressCount');

        countElement.textContent = `${filteredAddresses.length} address${filteredAddresses.length !== 1 ? 'es' : ''}`;

        if (filteredAddresses.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-muted">No addresses found matching your criteria.</p>
                </div>
            `;
            return;
        }

        // Responsive display: desktop table, mobile cards
        const isDesktop = window.innerWidth >= 992;

        if (isDesktop) {
            displayAddressesTable(container);
        } else {
            displayAddressesCards(container);
        }
    }

    function displayAddressesTable(container) {
        let html = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name/Address</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Type</th>
                            <th>Coordinates</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        filteredAddresses.forEach(address => {
            const statusBadge = getStatusBadge(address.status);
            const typeBadge = getTypeBadge(address.address_type);
            const verifiedBadge = address.is_verified ? '<span class="badge bg-success">🎗️ Verified</span>' : '';
            const defaultBadge = address.is_default ? '<span class="badge bg-warning">⭐ Default</span>' : '';
            const coordinatesDisplay = address.latitude && address.longitude ?
                `${parseFloat(address.latitude).toFixed(4)}, ${parseFloat(address.longitude).toFixed(4)}` : '-';

            html += `
                <tr>
                    <td>
                        <div class="fw-bold">${address.display_name || 'Unnamed Address'}</div>
                        <div class="small text-muted">${address.formatted_address || '-'}</div>
                        ${defaultBadge}
                    </td>
                    <td>${address.city || '-'}</td>
                    <td>${address.country_name || '-'}</td>
                    <td>${typeBadge}</td>
                    <td class="font-monospace small">${coordinatesDisplay}</td>
                    <td>
                        ${statusBadge}
                        ${verifiedBadge}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" onclick="viewAddress(${address.id})" title="View Details">
                                👁️
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="editAddress(${address.id})" title="Edit">
                                ✏️
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteAddress(${address.id})" title="Delete">
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

    function displayAddressesCards(container) {
        let html = '<div class="row">';

        filteredAddresses.forEach(address => {
            const statusBadge = getStatusBadge(address.status);
            const typeBadge = getTypeBadge(address.address_type);
            const verifiedBadge = address.is_verified ? '<span class="badge bg-success">🎗️</span>' : '';
            const defaultBadge = address.is_default ? '<span class="badge bg-warning">⭐</span>' : '';

            html += `
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">${address.display_name || 'Unnamed Address'}</h6>
                                <div>
                                    ${defaultBadge} ${verifiedBadge}
                                </div>
                            </div>
                            <p class="card-text text-muted small">${address.formatted_address || '-'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    ${typeBadge} ${statusBadge}
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary btn-sm" onclick="viewAddress(${address.id})">
                                        View Details
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editAddress(${address.id})">
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
            active: 'bg-success',
            inactive: 'bg-secondary',
            archived: 'bg-dark'
        };
        return `<span class="badge ${statusConfig[status] || 'bg-secondary'}">${status}</span>`;
    }

    function getTypeBadge(type) {
        const typeConfig = {
            home: '🏠',
            work: '🏢',
            business: '🏪',
            shipping: '📦',
            billing: '💰',
            mailing: '📮',
            other: '📍'
        };
        return `<span class="badge bg-primary">${typeConfig[type] || '📍'} ${type}</span>`;
    }

    function updateStatistics() {
        document.getElementById('totalAddresses').textContent = addresses.length;
        document.getElementById('activeAddresses').textContent = addresses.filter(a => a.status === 'active').length;
        document.getElementById('verifiedAddresses').textContent = addresses.filter(a => a.is_verified).length;
        document.getElementById('geoLocatedAddresses').textContent = addresses.filter(a => a.latitude && a.longitude).length;
        document.getElementById('defaultAddresses').textContent = addresses.filter(a => a.is_default).length;

        const uniqueCountries = new Set(addresses.map(a => a.country_id).filter(Boolean));
        document.getElementById('totalCountries').textContent = uniqueCountries.size;
    }

    // Map initialization functions
    function initializeAddMap() {
        if (addressMap) {
            addressMap.remove();
        }

        addressMap = L.map('addressMap').setView([40.7128, -74.0060], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(addressMap);

        addressMap.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            updateAddMapMarker(lat, lng);
            showCoordinateDisplay(lat, lng);
        });
    }

    function initializeEditMap() {
        if (editAddressMap) {
            editAddressMap.remove();
        }

        const lat = parseFloat(document.getElementById('editLatitude').value) || 40.7128;
        const lng = parseFloat(document.getElementById('editLongitude').value) || -74.0060;

        editAddressMap = L.map('editAddressMap').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(editAddressMap);

        updateEditMapMarker(lat, lng);
        showEditCoordinateDisplay(lat, lng);

        editAddressMap.on('click', function(e) {
            const newLat = e.latlng.lat;
            const newLng = e.latlng.lng;

            document.getElementById('editLatitude').value = newLat.toFixed(8);
            document.getElementById('editLongitude').value = newLng.toFixed(8);

            updateEditMapMarker(newLat, newLng);
            showEditCoordinateDisplay(newLat, newLng);
        });
    }

    function initializeViewMap() {
        if (viewAddressMap) {
            viewAddressMap.remove();
        }

        const addressId = document.getElementById('viewAddressId').textContent;
        const address = addresses.find(a => a.id == addressId);

        if (address && address.latitude && address.longitude) {
            const lat = parseFloat(address.latitude);
            const lng = parseFloat(address.longitude);

            viewAddressMap = L.map('viewAddressMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(viewAddressMap);

            L.marker([lat, lng])
                .addTo(viewAddressMap)
                .bindPopup(`<strong>${address.display_name}</strong><br>${address.formatted_address}`)
                .openPopup();
        }
    }

    function initializeAllAddressesMap() {
        if (allAddressesMap) {
            allAddressesMap.remove();
        }

        allAddressesMap = L.map('allAddressesMap').setView([40.7128, -74.0060], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(allAddressesMap);

        // Add markers for all addresses with coordinates
        const validAddresses = addresses.filter(a => a.latitude && a.longitude);
        if (validAddresses.length > 0) {
            const group = new L.featureGroup();

            validAddresses.forEach(address => {
                const marker = L.marker([parseFloat(address.latitude), parseFloat(address.longitude)])
                    .bindPopup(`
                        <strong>${address.display_name || 'Unnamed Address'}</strong><br>
                        ${address.formatted_address}<br>
                        <small>Type: ${address.address_type} | Status: ${address.status}</small>
                    `);
                group.addLayer(marker);
                allAddressesMap.addLayer(marker);
            });

            allAddressesMap.fitBounds(group.getBounds().pad(0.1));
        }
    }

    function updateAddMapMarker(lat, lng) {
        if (currentMarker) {
            addressMap.removeLayer(currentMarker);
        }
        currentMarker = L.marker([lat, lng]).addTo(addressMap);
    }

    function updateEditMapMarker(lat, lng) {
        if (editCurrentMarker) {
            editAddressMap.removeLayer(editCurrentMarker);
        }
        editCurrentMarker = L.marker([lat, lng]).addTo(editAddressMap);
    }

    function showCoordinateDisplay(lat, lng) {
        document.getElementById('displayLatitude').textContent = lat.toFixed(8);
        document.getElementById('displayLongitude').textContent = lng.toFixed(8);
        document.getElementById('coordinateDisplay').style.display = 'block';
    }

    function showEditCoordinateDisplay(lat, lng) {
        document.getElementById('editDisplayLatitude').textContent = lat.toFixed(8);
        document.getElementById('editDisplayLongitude').textContent = lng.toFixed(8);
        document.getElementById('editCoordinateDisplay').style.display = 'block';
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                document.getElementById('latitude').value = lat.toFixed(8);
                document.getElementById('longitude').value = lng.toFixed(8);

                if (addressMap) {
                    addressMap.setView([lat, lng], 15);
                    updateAddMapMarker(lat, lng);
                    showCoordinateDisplay(lat, lng);
                }
            }, function(error) {
                alert('Unable to get current location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by this browser');
        }
    }

    function getCurrentLocationForEdit() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                document.getElementById('editLatitude').value = lat.toFixed(8);
                document.getElementById('editLongitude').value = lng.toFixed(8);

                if (editAddressMap) {
                    editAddressMap.setView([lat, lng], 15);
                    updateEditMapMarker(lat, lng);
                    showEditCoordinateDisplay(lat, lng);
                }
            }, function(error) {
                alert('Unable to get current location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by this browser');
        }
    }

    function handleAddAddress(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Convert checkboxes
        data.is_default = document.getElementById('is_default').checked ? 1 : 0;
        data.is_verified = document.getElementById('is_verified').checked ? 1 : 0;

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'createPostalAddress',
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('addAddressModal')).hide();
                document.getElementById('addAddressForm').reset();
                loadAddresses();
                alert('Address created successfully!');
            } else {
                alert('Error: ' + (result.error || 'Failed to create address'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating address');
        });
    }

    function handleEditAddress(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Convert checkboxes
        data.is_default = document.getElementById('editIs_default').checked ? 1 : 0;
        data.is_verified = document.getElementById('editIs_verified').checked ? 1 : 0;

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'updatePostalAddress',
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('editAddressModal')).hide();
                loadAddresses();
                alert('Address updated successfully!');
            } else {
                alert('Error: ' + (result.error || 'Failed to update address'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating address');
        });
    }

    // Global functions for button actions
    window.viewAddress = function(id) {
        const address = addresses.find(a => a.id === id);
        if (!address) return;

        // Populate view modal
        document.getElementById('viewName').textContent = address.display_name || 'Unnamed Address';
        document.getElementById('viewType').innerHTML = getTypeBadge(address.address_type);
        document.getElementById('viewFormattedAddress').textContent = address.formatted_address || '-';
        document.getElementById('viewCountry').textContent = address.country_name || '-';
        document.getElementById('viewCoordinates').textContent =
            address.latitude && address.longitude ? `${address.latitude}, ${address.longitude}` : '-';
        document.getElementById('viewTimezone').textContent = address.timezone || '-';
        document.getElementById('viewStatus').innerHTML = getStatusBadge(address.status);
        document.getElementById('viewVerified').innerHTML = address.is_verified ?
            '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewDefault').innerHTML = address.is_default ?
            '<span class="badge bg-warning">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewAddressId').textContent = address.id;
        document.getElementById('viewCreatedAt').textContent = address.created_at || '-';

        // Building details
        document.getElementById('viewBuildingName').textContent = address.building_name || '-';
        document.getElementById('viewFloor').textContent = address.floor || '-';
        document.getElementById('viewApartment').textContent = address.apartment_unit || '-';
        document.getElementById('viewLandmark').textContent = address.landmark || '-';

        // Delivery information
        document.getElementById('viewDeliveryNotes').innerHTML = address.delivery_notes ?
            `<dt>Delivery Notes:</dt><dd>${address.delivery_notes}</dd>` :
            '<dt>Delivery Notes:</dt><dd>-</dd>';
        document.getElementById('viewAccessInstructions').innerHTML = address.access_instructions ?
            `<dt>Access Instructions:</dt><dd>${address.access_instructions}</dd>` :
            '<dt>Access Instructions:</dt><dd>-</dd>';

        // Set up action buttons
        document.getElementById('viewEditBtn').onclick = () => editAddress(id);
        document.getElementById('viewDeleteBtn').onclick = () => deleteAddress(id);
        document.getElementById('viewGoogleMapsBtn').onclick = () => {
            window.open(address.google_maps_url, '_blank');
        };
        document.getElementById('viewAppleMapsBtn').onclick = () => {
            window.open(address.apple_maps_url, '_blank');
        };

        const modal = new bootstrap.Modal(document.getElementById('viewAddressModal'));
        modal.show();
    };

    window.editAddress = function(id) {
        const address = addresses.find(a => a.id === id);
        if (!address) return;

        // Populate edit form
        document.getElementById('editAddressId').value = address.id;
        document.getElementById('editName').value = address.name || '';
        document.getElementById('editStreet_address_1').value = address.street_address_1 || '';
        document.getElementById('editStreet_address_2').value = address.street_address_2 || '';
        document.getElementById('editCity').value = address.city || '';
        document.getElementById('editState_province').value = address.state_province || '';
        document.getElementById('editPostal_code').value = address.postal_code || '';
        document.getElementById('editCountry').value = address.country_id || '';
        document.getElementById('editLatitude').value = address.latitude || '';
        document.getElementById('editLongitude').value = address.longitude || '';
        document.getElementById('editAddress_type').value = address.address_type || '';
        document.getElementById('editStatus').value = address.status || '';
        document.getElementById('editVerification_method').value = address.verification_method || '';
        document.getElementById('editIs_default').checked = address.is_default;
        document.getElementById('editIs_verified').checked = address.is_verified;

        const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));
        modal.show();
    };

    window.deleteAddress = function(id) {
        if (!confirm('Are you sure you want to delete this address?')) return;

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'deletePostalAddress',
                id: id
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                loadAddresses();
                alert('Address deleted successfully!');

                // Close any open modals
                ['viewAddressModal', 'editAddressModal'].forEach(modalId => {
                    const modalEl = document.getElementById(modalId);
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                });
            } else {
                alert('Error: ' + (result.error || 'Failed to delete address'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting address');
        });
    };

    // Handle window resize for responsive display
    window.addEventListener('resize', () => {
        displayAddresses();
    });
});
</script>