<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏴 Country Management</h1>
            <p class="text-muted">Manage countries with comprehensive data and real-time updates</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newCountryBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCountryModal">
                ➕ Add Country
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Countries</h6>
                            <h4 class="card-title mb-0" id="totalCountries">0</h4>
                        </div>
                        <div class="stats-icon">🏴</div>
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
                            <h4 class="card-title mb-0" id="activeCountries">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Developed</h6>
                            <h4 class="card-title mb-0" id="developedCountries">0</h4>
                        </div>
                        <div class="stats-icon">🏭</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Landlocked</h6>
                            <h4 class="card-title mb-0" id="landlockedCountries">0</h4>
                        </div>
                        <div class="stats-icon">🏔️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
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
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total GDP</h6>
                            <h4 class="card-title mb-0" id="totalGDP">0</h4>
                        </div>
                        <div class="stats-icon">💰</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchCountries" class="form-control" placeholder="Search countries...">
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
            <select id="continentFilter" class="form-select">
                <option value="">All Continents</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
            <select id="developmentFilter" class="form-select">
                <option value="">All Development</option>
                <option value="developed">Developed</option>
                <option value="developing">Developing</option>
            </select>
        </div>
        <div class="col-lg-2 col-12">
            <select id="sortFilter" class="form-select">
                <option value="">Sort by...</option>
                <option value="name">Name A-Z</option>
                <option value="population">Population ↓</option>
                <option value="area">Area ↓</option>
                <option value="gdp">GDP ↓</option>
            </select>
        </div>
    </div>

    <!-- Countries Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Country List</h5>
                </div>
                <div class="card-body">
                    <div id="countriesTable">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading countries...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Country Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCountryForm">
                <div class="modal-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🏴 Basic Information</h6>
                            <div class="mb-3">
                                <label for="name" class="form-label">Country Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="official_name" class="form-label">Official Name</label>
                                <input type="text" class="form-control" id="official_name" name="official_name">
                            </div>
                            <div class="mb-3">
                                <label for="continent_id" class="form-label">Continent *</label>
                                <select class="form-select" id="continent_id" name="continent_id" required>
                                    <option value="">Select Continent</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="capital" class="form-label">Capital City</label>
                                <input type="text" class="form-control" id="capital" name="capital">
                            </div>
                            <div class="mb-3">
                                <label for="flag_emoji" class="form-label">Flag Emoji</label>
                                <input type="text" class="form-control" id="flag_emoji" name="flag_emoji" placeholder="🏴">
                            </div>
                        </div>

                        <!-- ISO Codes & Location -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🗺️ Codes & Location</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iso_alpha_2" class="form-label">ISO Alpha-2</label>
                                        <input type="text" class="form-control" id="iso_alpha_2" name="iso_alpha_2" maxlength="2" placeholder="US">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iso_alpha_3" class="form-label">ISO Alpha-3</label>
                                        <input type="text" class="form-control" id="iso_alpha_3" name="iso_alpha_3" maxlength="3" placeholder="USA">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iso_numeric" class="form-label">ISO Numeric</label>
                                        <input type="text" class="form-control" id="iso_numeric" name="iso_numeric" placeholder="840">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="latitude" name="latitude">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="longitude" name="longitude">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="region" class="form-label">Region</label>
                                        <input type="text" class="form-control" id="region" name="region">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subregion" class="form-label">Subregion</label>
                                        <input type="text" class="form-control" id="subregion" name="subregion">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Demographics & Economy -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">👥 Demographics & Economy</h6>
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
                                        <label for="gdp_usd" class="form-label">GDP (USD)</label>
                                        <input type="number" class="form-control" id="gdp_usd" name="gdp_usd" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gdp_per_capita" class="form-label">GDP per Capita</label>
                                        <input type="number" step="any" class="form-control" id="gdp_per_capita" name="gdp_per_capita" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currency_code" class="form-label">Currency Code</label>
                                        <input type="text" class="form-control" id="currency_code" name="currency_code" placeholder="USD">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currency_name" class="form-label">Currency Name</label>
                                        <input type="text" class="form-control" id="currency_name" name="currency_name" placeholder="US Dollar">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Government & Contact -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🏛️ Government & Contact</h6>
                            <div class="mb-3">
                                <label for="government_type" class="form-label">Government Type</label>
                                <select class="form-select" id="government_type" name="government_type">
                                    <option value="">Select Type</option>
                                    <option value="republic">Republic</option>
                                    <option value="monarchy">Monarchy</option>
                                    <option value="federation">Federation</option>
                                    <option value="parliamentary">Parliamentary</option>
                                    <option value="presidential">Presidential</option>
                                    <option value="dictatorship">Dictatorship</option>
                                    <option value="communist">Communist</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="head_of_state" class="form-label">Head of State</label>
                                <input type="text" class="form-control" id="head_of_state" name="head_of_state">
                            </div>
                            <div class="mb-3">
                                <label for="head_of_government" class="form-label">Head of Government</label>
                                <input type="text" class="form-control" id="head_of_government" name="head_of_government">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="calling_code" class="form-label">Calling Code</label>
                                        <input type="text" class="form-control" id="calling_code" name="calling_code" placeholder="+1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="internet_tld" class="form-label">Internet TLD</label>
                                        <input type="text" class="form-control" id="internet_tld" name="internet_tld" placeholder=".us">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="official_languages" class="form-label">Official Languages</label>
                                <input type="text" class="form-control" id="official_languages" name="official_languages" placeholder="English, Spanish">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="independence_date" class="form-label">Independence Date</label>
                                <input type="date" class="form-control" id="independence_date" name="independence_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <input type="text" class="form-control" id="timezone" name="timezone" placeholder="UTC-5">
                            </div>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_landlocked" name="is_landlocked">
                                <label class="form-check-label" for="is_landlocked">
                                    Landlocked
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_developed" name="is_developed">
                                <label class="form-check-label" for="is_developed">
                                    Developed Country
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Country Modal -->
<div class="modal fade" id="editCountryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCountryForm">
                <input type="hidden" id="editCountryId" name="id">
                <div class="modal-body">
                    <!-- Same fields as add modal with edit prefixes -->
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🏴 Basic Information</h6>
                            <div class="mb-3">
                                <label for="editName" class="form-label">Country Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editOfficial_name" class="form-label">Official Name</label>
                                <input type="text" class="form-control" id="editOfficial_name" name="official_name">
                            </div>
                            <div class="mb-3">
                                <label for="editContinent_id" class="form-label">Continent *</label>
                                <select class="form-select" id="editContinent_id" name="continent_id" required>
                                    <option value="">Select Continent</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editCapital" class="form-label">Capital City</label>
                                <input type="text" class="form-control" id="editCapital" name="capital">
                            </div>
                            <div class="mb-3">
                                <label for="editFlag_emoji" class="form-label">Flag Emoji</label>
                                <input type="text" class="form-control" id="editFlag_emoji" name="flag_emoji">
                            </div>
                        </div>

                        <!-- ISO Codes & Location -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🗺️ Codes & Location</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editIso_alpha_2" class="form-label">ISO Alpha-2</label>
                                        <input type="text" class="form-control" id="editIso_alpha_2" name="iso_alpha_2" maxlength="2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editIso_alpha_3" class="form-label">ISO Alpha-3</label>
                                        <input type="text" class="form-control" id="editIso_alpha_3" name="iso_alpha_3" maxlength="3">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editIso_numeric" class="form-label">ISO Numeric</label>
                                        <input type="text" class="form-control" id="editIso_numeric" name="iso_numeric">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editLatitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="editLatitude" name="latitude">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editLongitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="editLongitude" name="longitude">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editRegion" class="form-label">Region</label>
                                        <input type="text" class="form-control" id="editRegion" name="region">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editSubregion" class="form-label">Subregion</label>
                                        <input type="text" class="form-control" id="editSubregion" name="subregion">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Demographics & Economy -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">👥 Demographics & Economy</h6>
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
                                        <label for="editGdp_usd" class="form-label">GDP (USD)</label>
                                        <input type="number" class="form-control" id="editGdp_usd" name="gdp_usd" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editGdp_per_capita" class="form-label">GDP per Capita</label>
                                        <input type="number" step="any" class="form-control" id="editGdp_per_capita" name="gdp_per_capita" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editCurrency_code" class="form-label">Currency Code</label>
                                        <input type="text" class="form-control" id="editCurrency_code" name="currency_code">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editCurrency_name" class="form-label">Currency Name</label>
                                        <input type="text" class="form-control" id="editCurrency_name" name="currency_name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Government & Contact -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🏛️ Government & Contact</h6>
                            <div class="mb-3">
                                <label for="editGovernment_type" class="form-label">Government Type</label>
                                <select class="form-select" id="editGovernment_type" name="government_type">
                                    <option value="">Select Type</option>
                                    <option value="republic">Republic</option>
                                    <option value="monarchy">Monarchy</option>
                                    <option value="federation">Federation</option>
                                    <option value="parliamentary">Parliamentary</option>
                                    <option value="presidential">Presidential</option>
                                    <option value="dictatorship">Dictatorship</option>
                                    <option value="communist">Communist</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editHead_of_state" class="form-label">Head of State</label>
                                <input type="text" class="form-control" id="editHead_of_state" name="head_of_state">
                            </div>
                            <div class="mb-3">
                                <label for="editHead_of_government" class="form-label">Head of Government</label>
                                <input type="text" class="form-control" id="editHead_of_government" name="head_of_government">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editCalling_code" class="form-label">Calling Code</label>
                                        <input type="text" class="form-control" id="editCalling_code" name="calling_code">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editInternet_tld" class="form-label">Internet TLD</label>
                                        <input type="text" class="form-control" id="editInternet_tld" name="internet_tld">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editOfficial_languages" class="form-label">Official Languages</label>
                                <input type="text" class="form-control" id="editOfficial_languages" name="official_languages">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editIndependence_date" class="form-label">Independence Date</label>
                                <input type="date" class="form-control" id="editIndependence_date" name="independence_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editTimezone" class="form-label">Timezone</label>
                                <input type="text" class="form-control" id="editTimezone" name="timezone">
                            </div>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="editIs_landlocked" name="is_landlocked">
                                <label class="form-check-label" for="editIs_landlocked">
                                    Landlocked
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="editIs_developed" name="is_developed">
                                <label class="form-check-label" for="editIs_developed">
                                    Developed Country
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="editIs_active" name="is_active">
                                <label class="form-check-label" for="editIs_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Country Detail Modal -->
<div class="modal fade" id="viewCountryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏴 Country Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Basic Info -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">🏴 Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Name:</dt>
                                    <dd class="col-sm-8" id="viewCountryName">-</dd>

                                    <dt class="col-sm-4">Official Name:</dt>
                                    <dd class="col-sm-8" id="viewOfficialName">-</dd>

                                    <dt class="col-sm-4">Continent:</dt>
                                    <dd class="col-sm-8" id="viewContinent">-</dd>

                                    <dt class="col-sm-4">Capital:</dt>
                                    <dd class="col-sm-8" id="viewCapital">-</dd>

                                    <dt class="col-sm-4">ISO Codes:</dt>
                                    <dd class="col-sm-8" id="viewIsoCodes">-</dd>

                                    <dt class="col-sm-4">Region:</dt>
                                    <dd class="col-sm-8" id="viewRegion">-</dd>

                                    <dt class="col-sm-4">Languages:</dt>
                                    <dd class="col-sm-8" id="viewLanguages">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Demographics -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">👥 Demographics</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Population:</dt>
                                    <dd class="col-sm-8" id="viewPopulation">-</dd>

                                    <dt class="col-sm-4">Area:</dt>
                                    <dd class="col-sm-8" id="viewArea">-</dd>

                                    <dt class="col-sm-4">Density:</dt>
                                    <dd class="col-sm-8" id="viewDensity">-</dd>

                                    <dt class="col-sm-4">GDP:</dt>
                                    <dd class="col-sm-8" id="viewGDP">-</dd>

                                    <dt class="col-sm-4">GDP per Capita:</dt>
                                    <dd class="col-sm-8" id="viewGDPPerCapita">-</dd>

                                    <dt class="col-sm-4">Currency:</dt>
                                    <dd class="col-sm-8" id="viewCurrency">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Government -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">🏛️ Government</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-5">Type:</dt>
                                    <dd class="col-sm-7" id="viewGovernmentType">-</dd>

                                    <dt class="col-sm-5">Head of State:</dt>
                                    <dd class="col-sm-7" id="viewHeadOfState">-</dd>

                                    <dt class="col-sm-5">Head of Govt:</dt>
                                    <dd class="col-sm-7" id="viewHeadOfGovernment">-</dd>

                                    <dt class="col-sm-5">Independence:</dt>
                                    <dd class="col-sm-7" id="viewIndependence">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Metadata -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">📊 Status & Contact</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-5">Status:</dt>
                                    <dd class="col-sm-7" id="viewStatus">-</dd>

                                    <dt class="col-sm-5">Development:</dt>
                                    <dd class="col-sm-7" id="viewDevelopment">-</dd>

                                    <dt class="col-sm-5">Landlocked:</dt>
                                    <dd class="col-sm-7" id="viewLandlocked">-</dd>

                                    <dt class="col-sm-5">Calling Code:</dt>
                                    <dd class="col-sm-7" id="viewCallingCode">-</dd>

                                    <dt class="col-sm-5">Internet TLD:</dt>
                                    <dd class="col-sm-7" id="viewInternetTLD">-</dd>

                                    <dt class="col-sm-5">Timezone:</dt>
                                    <dd class="col-sm-7" id="viewTimezone">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Country
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Country
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