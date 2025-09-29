<?php
require_once '../includes/header.php';
require_once '../includes/sub_menu.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏢 Organization Legal Types</h1>
            <p class="text-muted">Manage legal entity types and corporate structures by jurisdiction</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newLegalTypeBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLegalTypeModal">
                ➕ Add Legal Type
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
                            <h6 class="card-subtitle mb-1 small">Total Types</h6>
                            <h4 class="card-title mb-0" id="totalLegalTypes">0</h4>
                        </div>
                        <div class="stats-icon">🏢</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Countries</h6>
                            <h4 class="card-title mb-0" id="totalCountries">0</h4>
                        </div>
                        <div class="stats-icon">🌍</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Active</h6>
                            <h4 class="card-title mb-0" id="activeLegalTypes">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Commonly Used</h6>
                            <h4 class="card-title mb-0" id="commonLegalTypes">0</h4>
                        </div>
                        <div class="stats-icon">⭐</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Public Types</h6>
                            <h4 class="card-title mb-0" id="publicLegalTypes">0</h4>
                        </div>
                        <div class="stats-icon">📈</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Foreign Allowed</h6>
                            <h4 class="card-title mb-0" id="foreignAllowed">0</h4>
                        </div>
                        <div class="stats-icon">🌐</div>
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
                <input type="text" id="searchLegalTypes" class="form-control" placeholder="Search legal types, abbreviations, descriptions...">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="countryFilter" class="form-select" style="max-width: 180px;">
                    <option value="">All Countries</option>
                </select>
                <select id="categoryFilter" class="form-select" style="max-width: 150px;">
                    <option value="">All Categories</option>
                    <option value="corporation">Corporation</option>
                    <option value="llc">LLC</option>
                    <option value="partnership">Partnership</option>
                    <option value="sole_proprietorship">Sole Proprietorship</option>
                    <option value="cooperative">Cooperative</option>
                    <option value="nonprofit">Nonprofit</option>
                    <option value="trust">Trust</option>
                    <option value="other">Other</option>
                </select>
                <select id="liabilityFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Liability</option>
                    <option value="limited">Limited</option>
                    <option value="unlimited">Unlimited</option>
                    <option value="mixed">Mixed</option>
                </select>
                <select id="statusFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="usageFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Usage</option>
                    <option value="common">Commonly Used</option>
                    <option value="uncommon">Uncommon</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Legal Types Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Organization Legal Types</h5>
                    <small class="text-muted" id="legalTypeCount">0 legal types</small>
                </div>
                <div class="card-body">
                    <div id="legalTypesDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading legal types...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Legal Type Modal -->
<div class="modal fade" id="addLegalTypeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Organization Legal Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLegalTypeForm">
                <div class="modal-body">
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="abbreviation" class="form-label">Abbreviation</label>
                                <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="e.g., LLC, Corp">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country_id" class="form-label">Country *</label>
                                <select class="form-select" id="country_id" name="country_id" required>
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jurisdiction" class="form-label">Jurisdiction</label>
                                <input type="text" class="form-control" id="jurisdiction" name="jurisdiction">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Classification -->
                    <h6 class="mb-3 mt-4">Classification</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="corporation">Corporation</option>
                                    <option value="llc">LLC</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="sole_proprietorship">Sole Proprietorship</option>
                                    <option value="cooperative">Cooperative</option>
                                    <option value="nonprofit">Nonprofit</option>
                                    <option value="trust">Trust</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="liability_type" class="form-label">Liability Type</label>
                                <select class="form-select" id="liability_type" name="liability_type">
                                    <option value="limited">Limited</option>
                                    <option value="unlimited">Unlimited</option>
                                    <option value="mixed">Mixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tax_structure" class="form-label">Tax Structure</label>
                                <select class="form-select" id="tax_structure" name="tax_structure">
                                    <option value="corporate">Corporate</option>
                                    <option value="pass_through">Pass Through</option>
                                    <option value="hybrid">Hybrid</option>
                                    <option value="exempt">Exempt</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Ownership Structure -->
                    <h6 class="mb-3 mt-4">Ownership Structure</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="min_shareholders" class="form-label">Min Shareholders</label>
                                <input type="number" class="form-control" id="min_shareholders" name="min_shareholders" value="1" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="max_shareholders" class="form-label">Max Shareholders</label>
                                <input type="number" class="form-control" id="max_shareholders" name="max_shareholders" placeholder="Leave empty for unlimited">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="min_directors" class="form-label">Min Directors</label>
                                <input type="number" class="form-control" id="min_directors" name="min_directors" value="1" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="max_directors" class="form-label">Max Directors</label>
                                <input type="number" class="form-control" id="max_directors" name="max_directors" placeholder="Leave empty for unlimited">
                            </div>
                        </div>
                    </div>

                    <!-- Capital Requirements -->
                    <h6 class="mb-3 mt-4">Capital Requirements</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_capital_required" class="form-label">Minimum Capital Required</label>
                                <input type="number" class="form-control" id="min_capital_required" name="min_capital_required" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="currency_code" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="currency_code" name="currency_code" placeholder="e.g., USD, EUR" maxlength="3">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="formation_time_days" class="form-label">Formation Time (Days)</label>
                                <input type="number" class="form-control" id="formation_time_days" name="formation_time_days" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="formation_cost_range" class="form-label">Formation Cost Range</label>
                        <input type="text" class="form-control" id="formation_cost_range" name="formation_cost_range" placeholder="e.g., $500-$1000">
                    </div>

                    <!-- Foreign Ownership -->
                    <h6 class="mb-3 mt-4">Foreign Ownership</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="allows_foreign_ownership" name="allows_foreign_ownership" checked>
                                <label class="form-check-label" for="allows_foreign_ownership">
                                    Allows Foreign Ownership
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foreign_ownership_limit" class="form-label">Foreign Ownership Limit (%)</label>
                                <input type="number" class="form-control" id="foreign_ownership_limit" name="foreign_ownership_limit" value="100" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <h6 class="mb-3 mt-4">Requirements</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="requires_local_director" name="requires_local_director">
                                <label class="form-check-label" for="requires_local_director">
                                    Requires Local Director
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="requires_company_secretary" name="requires_company_secretary">
                                <label class="form-check-label" for="requires_company_secretary">
                                    Requires Company Secretary
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="requires_registered_office" name="requires_registered_office" checked>
                                <label class="form-check-label" for="requires_registered_office">
                                    Requires Registered Office
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allows_single_director" name="allows_single_director" checked>
                                <label class="form-check-label" for="allows_single_director">
                                    Allows Single Director
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allows_nominee_directors" name="allows_nominee_directors" checked>
                                <label class="form-check-label" for="allows_nominee_directors">
                                    Allows Nominee Directors
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_public_company" name="is_public_company">
                                <label class="form-check-label" for="is_public_company">
                                    Public Company Type
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <h6 class="mb-3 mt-4">Status</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_commonly_used" name="is_commonly_used">
                                <label class="form-check-label" for="is_commonly_used">
                                    Commonly Used
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h6 class="mb-3 mt-4">Additional Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="regulatory_authority" class="form-label">Regulatory Authority</label>
                                <input type="text" class="form-control" id="regulatory_authority" name="regulatory_authority">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="legal_code" class="form-label">Legal Code</label>
                                <input type="text" class="form-control" id="legal_code" name="legal_code">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="advantages" class="form-label">Advantages</label>
                                <textarea class="form-control" id="advantages" name="advantages" rows="3" placeholder="List advantages, one per line"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="disadvantages" class="form-label">Disadvantages</label>
                                <textarea class="form-control" id="disadvantages" name="disadvantages" rows="3" placeholder="List disadvantages, one per line"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="common_usage" class="form-label">Common Usage</label>
                                <textarea class="form-control" id="common_usage" name="common_usage" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="examples" class="form-label">Examples</label>
                                <input type="text" class="form-control" id="examples" name="examples" placeholder="Company examples, separated by commas">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Legal Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Legal Type Modal -->
<div class="modal fade" id="editLegalTypeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Organization Legal Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLegalTypeForm">
                <input type="hidden" id="editLegalTypeId" name="id">
                <div class="modal-body">
                    <!-- Same structure as add modal but with edit prefixes -->
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editAbbreviation" class="form-label">Abbreviation</label>
                                <input type="text" class="form-control" id="editAbbreviation" name="abbreviation">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editSort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="editSort_order" name="sort_order" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editCountry_id" class="form-label">Country *</label>
                                <select class="form-select" id="editCountry_id" name="country_id" required>
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editJurisdiction" class="form-label">Jurisdiction</label>
                                <input type="text" class="form-control" id="editJurisdiction" name="jurisdiction">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>

                    <!-- Classification -->
                    <h6 class="mb-3 mt-4">Classification</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editCategory" class="form-label">Category</label>
                                <select class="form-select" id="editCategory" name="category">
                                    <option value="corporation">Corporation</option>
                                    <option value="llc">LLC</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="sole_proprietorship">Sole Proprietorship</option>
                                    <option value="cooperative">Cooperative</option>
                                    <option value="nonprofit">Nonprofit</option>
                                    <option value="trust">Trust</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editLiability_type" class="form-label">Liability Type</label>
                                <select class="form-select" id="editLiability_type" name="liability_type">
                                    <option value="limited">Limited</option>
                                    <option value="unlimited">Unlimited</option>
                                    <option value="mixed">Mixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editTax_structure" class="form-label">Tax Structure</label>
                                <select class="form-select" id="editTax_structure" name="tax_structure">
                                    <option value="corporate">Corporate</option>
                                    <option value="pass_through">Pass Through</option>
                                    <option value="hybrid">Hybrid</option>
                                    <option value="exempt">Exempt</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Ownership Structure -->
                    <h6 class="mb-3 mt-4">Ownership Structure</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editMin_shareholders" class="form-label">Min Shareholders</label>
                                <input type="number" class="form-control" id="editMin_shareholders" name="min_shareholders" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editMax_shareholders" class="form-label">Max Shareholders</label>
                                <input type="number" class="form-control" id="editMax_shareholders" name="max_shareholders">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editMin_directors" class="form-label">Min Directors</label>
                                <input type="number" class="form-control" id="editMin_directors" name="min_directors" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editMax_directors" class="form-label">Max Directors</label>
                                <input type="number" class="form-control" id="editMax_directors" name="max_directors">
                            </div>
                        </div>
                    </div>

                    <!-- Capital Requirements -->
                    <h6 class="mb-3 mt-4">Capital Requirements</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editMin_capital_required" class="form-label">Minimum Capital Required</label>
                                <input type="number" class="form-control" id="editMin_capital_required" name="min_capital_required" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editCurrency_code" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="editCurrency_code" name="currency_code" maxlength="3">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editFormation_time_days" class="form-label">Formation Time (Days)</label>
                                <input type="number" class="form-control" id="editFormation_time_days" name="formation_time_days" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editFormation_cost_range" class="form-label">Formation Cost Range</label>
                        <input type="text" class="form-control" id="editFormation_cost_range" name="formation_cost_range">
                    </div>

                    <!-- Foreign Ownership -->
                    <h6 class="mb-3 mt-4">Foreign Ownership</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="editAllows_foreign_ownership" name="allows_foreign_ownership">
                                <label class="form-check-label" for="editAllows_foreign_ownership">
                                    Allows Foreign Ownership
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editForeign_ownership_limit" class="form-label">Foreign Ownership Limit (%)</label>
                                <input type="number" class="form-control" id="editForeign_ownership_limit" name="foreign_ownership_limit" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <h6 class="mb-3 mt-4">Requirements</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editRequires_local_director" name="requires_local_director">
                                <label class="form-check-label" for="editRequires_local_director">
                                    Requires Local Director
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editRequires_company_secretary" name="requires_company_secretary">
                                <label class="form-check-label" for="editRequires_company_secretary">
                                    Requires Company Secretary
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editRequires_registered_office" name="requires_registered_office">
                                <label class="form-check-label" for="editRequires_registered_office">
                                    Requires Registered Office
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editAllows_single_director" name="allows_single_director">
                                <label class="form-check-label" for="editAllows_single_director">
                                    Allows Single Director
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editAllows_nominee_directors" name="allows_nominee_directors">
                                <label class="form-check-label" for="editAllows_nominee_directors">
                                    Allows Nominee Directors
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_public_company" name="is_public_company">
                                <label class="form-check-label" for="editIs_public_company">
                                    Public Company Type
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <h6 class="mb-3 mt-4">Status</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIs_active" name="is_active">
                                <label class="form-check-label" for="editIs_active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIs_commonly_used" name="is_commonly_used">
                                <label class="form-check-label" for="editIs_commonly_used">
                                    Commonly Used
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h6 class="mb-3 mt-4">Additional Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editRegulatory_authority" class="form-label">Regulatory Authority</label>
                                <input type="text" class="form-control" id="editRegulatory_authority" name="regulatory_authority">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editLegal_code" class="form-label">Legal Code</label>
                                <input type="text" class="form-control" id="editLegal_code" name="legal_code">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editAdvantages" class="form-label">Advantages</label>
                                <textarea class="form-control" id="editAdvantages" name="advantages" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDisadvantages" class="form-label">Disadvantages</label>
                                <textarea class="form-control" id="editDisadvantages" name="disadvantages" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editCommon_usage" class="form-label">Common Usage</label>
                                <textarea class="form-control" id="editCommon_usage" name="common_usage" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editExamples" class="form-label">Examples</label>
                                <input type="text" class="form-control" id="editExamples" name="examples">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Legal Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Legal Type Modal -->
<div class="modal fade" id="viewLegalTypeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏢 Legal Type Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Name:</dt>
                                            <dd class="col-sm-7" id="viewName">-</dd>

                                            <dt class="col-sm-5">Abbreviation:</dt>
                                            <dd class="col-sm-7" id="viewAbbreviation">-</dd>

                                            <dt class="col-sm-5">Country:</dt>
                                            <dd class="col-sm-7" id="viewCountry">-</dd>

                                            <dt class="col-sm-5">Jurisdiction:</dt>
                                            <dd class="col-sm-7" id="viewJurisdiction">-</dd>

                                            <dt class="col-sm-5">Category:</dt>
                                            <dd class="col-sm-7" id="viewCategory">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Liability:</dt>
                                            <dd class="col-sm-7" id="viewLiabilityType">-</dd>

                                            <dt class="col-sm-5">Tax Structure:</dt>
                                            <dd class="col-sm-7" id="viewTaxStructure">-</dd>

                                            <dt class="col-sm-5">Formation Time:</dt>
                                            <dd class="col-sm-7" id="viewFormationTime">-</dd>

                                            <dt class="col-sm-5">Formation Cost:</dt>
                                            <dd class="col-sm-7" id="viewFormationCost">-</dd>

                                            <dt class="col-sm-5">Min Capital:</dt>
                                            <dd class="col-sm-7" id="viewMinCapital">-</dd>
                                        </dl>
                                    </div>
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
                                <h6 class="card-title mb-0">Status & Ownership</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Status:</dt>
                                    <dd class="col-6" id="viewStatus">-</dd>

                                    <dt class="col-6">Usage:</dt>
                                    <dd class="col-6" id="viewUsage">-</dd>

                                    <dt class="col-6">Company Type:</dt>
                                    <dd class="col-6" id="viewCompanyType">-</dd>

                                    <dt class="col-6">Foreign:</dt>
                                    <dd class="col-6" id="viewForeignOwnership">-</dd>

                                    <dt class="col-6">ID:</dt>
                                    <dd class="col-6" id="viewLegalTypeId">-</dd>

                                    <dt class="col-6">Created:</dt>
                                    <dd class="col-6" id="viewCreatedAt">-</dd>

                                    <dt class="col-6">Updated:</dt>
                                    <dd class="col-6" id="viewUpdatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Legal Type
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Legal Type
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ownership Structure -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Ownership Structure</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Shareholders:</dt>
                                    <dd class="col-6" id="viewShareholderRange">-</dd>

                                    <dt class="col-6">Directors:</dt>
                                    <dd class="col-6" id="viewDirectorRange">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Requirements</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewRequirements">
                                    <p class="text-muted">No specific requirements</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advantages & Disadvantages -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Advantages</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewAdvantages">
                                    <p class="text-muted">No advantages listed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Disadvantages</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewDisadvantages">
                                    <p class="text-muted">No disadvantages listed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage & Examples -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Common Usage</h6>
                            </div>
                            <div class="card-body">
                                <p id="viewCommonUsage" class="mb-0">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Examples</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewExamples">
                                    <p class="text-muted">No examples provided</p>
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