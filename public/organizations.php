<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏛️ Organizations</h1>
            <p class="text-muted">Manage organization profiles, verify businesses, and track industry presence</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button id="newOrganizationBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrganizationModal">
                    ➕ Add Organization
                </button>
                <button id="exportBtn" class="btn btn-outline-secondary">
                    📊 Export
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
                            <h6 class="card-subtitle mb-1 small">Total Organizations</h6>
                            <h4 class="card-title mb-0" id="totalOrganizations">0</h4>
                        </div>
                        <div class="stats-icon">🏛️</div>
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
                            <h4 class="card-title mb-0" id="activeOrganizations">0</h4>
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
                            <h4 class="card-title mb-0" id="verifiedOrganizations">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Featured</h6>
                            <h4 class="card-title mb-0" id="featuredOrganizations">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Hiring</h6>
                            <h4 class="card-title mb-0" id="hiringOrganizations">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Industries</h6>
                            <h4 class="card-title mb-0" id="totalIndustries">0</h4>
                        </div>
                        <div class="stats-icon">🏭</div>
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
                <input type="text" id="searchOrganizations" class="form-control" placeholder="Search organizations, names, descriptions...">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                    <option value="archived">Archived</option>
                </select>
                <select id="verificationFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Verification</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                    <option value="expired">Expired</option>
                </select>
                <select id="industryFilter" class="form-select" style="max-width: 160px;">
                    <option value="">All Industries</option>
                </select>
                <select id="legalTypeFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Legal Types</option>
                </select>
                <select id="businessModelFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Models</option>
                    <option value="b2b">B2B</option>
                    <option value="b2c">B2C</option>
                    <option value="b2b2c">B2B2C</option>
                    <option value="c2c">C2C</option>
                    <option value="nonprofit">Nonprofit</option>
                    <option value="government">Government</option>
                </select>
                <select id="employeeFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Sizes</option>
                    <option value="1-10">1-10</option>
                    <option value="11-50">11-50</option>
                    <option value="51-200">51-200</option>
                    <option value="201-500">201-500</option>
                    <option value="501-1000">501-1000</option>
                    <option value="1001-5000">1001-5000</option>
                    <option value="5001+">5001+</option>
                </select>
                <div class="btn-group" role="group">
                    <input type="checkbox" class="btn-check" id="featuredFilter" autocomplete="off">
                    <label class="btn btn-outline-warning" for="featuredFilter">⭐ Featured</label>

                    <input type="checkbox" class="btn-check" id="hiringFilter" autocomplete="off">
                    <label class="btn btn-outline-success" for="hiringFilter">👥 Hiring</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizations Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Organizations</h5>
                    <small class="text-muted" id="organizationCount">0 organizations</small>
                </div>
                <div class="card-body">
                    <div id="organizationsDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading organizations...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Organization Modal -->
<div class="modal fade" id="addOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addOrganizationForm">
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Organization Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="legal_name" class="form-label">Legal Name</label>
                                <input type="text" class="form-control" id="legal_name" name="legal_name">
                                <div class="form-text">Official registered business name</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="addSubdomain" class="form-label">Subdomain *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="addSubdomain" name="subdomain" required
                                           pattern="[a-z0-9-]+" maxlength="30">
                                    <span class="input-group-text">.v4l.app</span>
                                </div>
                                <div class="form-text">3-30 characters, lowercase letters, numbers, and hyphens only</div>
                                <div id="addSubdomainFeedback" class="form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="website_url" class="form-label">Website URL</label>
                                <input type="url" class="form-control" id="website_url" name="website_url"
                                       placeholder="https://example.com">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tagline" class="form-label">Tagline</label>
                                <input type="text" class="form-control" id="tagline" name="tagline"
                                       maxlength="100" placeholder="Brief description or slogan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="founded_date" class="form-label">Founded Date</label>
                                <input type="date" class="form-control" id="founded_date" name="founded_date">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Detailed description of the organization"></textarea>
                    </div>

                    <!-- Classifications -->
                    <h6 class="mb-3 mt-4">Classifications</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="addIndustryCategory" class="form-label">Industry Category</label>
                                <select class="form-select" id="addIndustryCategory" name="industry_category_id">
                                    <option value="">Select Industry</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="addLegalType" class="form-label">Legal Type</label>
                                <select class="form-select" id="addLegalType" name="organization_legal_type_id">
                                    <option value="">Select Legal Type</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="business_model" class="form-label">Business Model</label>
                                <select class="form-select" id="business_model" name="business_model">
                                    <option value="b2c">B2C - Business to Consumer</option>
                                    <option value="b2b">B2B - Business to Business</option>
                                    <option value="b2b2c">B2B2C - Business to Business to Consumer</option>
                                    <option value="c2c">C2C - Consumer to Consumer</option>
                                    <option value="nonprofit">Nonprofit</option>
                                    <option value="government">Government</option>
                                    <option value="cooperative">Cooperative</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Organization Details -->
                    <h6 class="mb-3 mt-4">Organization Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="employee_count" class="form-label">Employee Count</label>
                                <select class="form-select" id="employee_count" name="employee_count">
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50">11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-500">201-500 employees</option>
                                    <option value="501-1000">501-1000 employees</option>
                                    <option value="1001-5000">1001-5000 employees</option>
                                    <option value="5001+">5001+ employees</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="annual_revenue" class="form-label">Annual Revenue</label>
                                <input type="number" class="form-control" id="annual_revenue" name="annual_revenue"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="revenue_currency" class="form-label">Currency</label>
                                <select class="form-select" id="revenue_currency" name="revenue_currency">
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

                    <!-- Contact Information -->
                    <h6 class="mb-3 mt-4">Contact Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="addWebsiteAdmin" class="form-label">Website Admin *</label>
                                <select class="form-select" id="addWebsiteAdmin" name="admin_person_id" required>
                                    <option value="">Select Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Organization Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>

                    <!-- Registration Information -->
                    <h6 class="mb-3 mt-4">Registration Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_id" class="form-label">Tax ID</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="registration_number" name="registration_number">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h6 class="mb-3 mt-4">Additional Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="specializations" class="form-label">Specializations</label>
                                <input type="text" class="form-control" id="specializations" name="specializations"
                                       placeholder="Comma-separated list of specializations">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="target_markets" class="form-label">Target Markets</label>
                                <input type="text" class="form-control" id="target_markets" name="target_markets"
                                       placeholder="Comma-separated list of target markets">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mission_statement" class="form-label">Mission Statement</label>
                                <textarea class="form-control" id="mission_statement" name="mission_statement" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vision_statement" class="form-label">Vision Statement</label>
                                <textarea class="form-control" id="vision_statement" name="vision_statement" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Settings -->
                    <h6 class="mb-3 mt-4">Status & Settings</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" checked>
                                <label class="form-check-label" for="is_public">
                                    Public Organization
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">
                                    Featured Organization
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_hiring" name="is_hiring">
                                <label class="form-check-label" for="is_hiring">
                                    Currently Hiring
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allows_reviews" name="allows_reviews" checked>
                                <label class="form-check-label" for="allows_reviews">
                                    Allow Reviews
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Organization</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Organization Modal -->
<div class="modal fade" id="editOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editOrganizationForm">
                <input type="hidden" id="editOrganizationId" name="id">
                <div class="modal-body">
                    <!-- Same structure as add modal but with edit prefixes -->
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Organization Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editLegal_name" class="form-label">Legal Name</label>
                                <input type="text" class="form-control" id="editLegal_name" name="legal_name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editSubdomain" class="form-label">Subdomain *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="editSubdomain" name="subdomain" required
                                           pattern="[a-z0-9-]+" maxlength="30">
                                    <span class="input-group-text">.v4l.app</span>
                                </div>
                                <div id="editSubdomainFeedback" class="form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editWebsite_url" class="form-label">Website URL</label>
                                <input type="url" class="form-control" id="editWebsite_url" name="website_url">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editTagline" class="form-label">Tagline</label>
                                <input type="text" class="form-control" id="editTagline" name="tagline" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editFounded_date" class="form-label">Founded Date</label>
                                <input type="date" class="form-control" id="editFounded_date" name="founded_date">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>

                    <!-- Classifications -->
                    <h6 class="mb-3 mt-4">Classifications</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editIndustryCategory" class="form-label">Industry Category</label>
                                <select class="form-select" id="editIndustryCategory" name="industry_category_id">
                                    <option value="">Select Industry</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editLegalType" class="form-label">Legal Type</label>
                                <select class="form-select" id="editLegalType" name="organization_legal_type_id">
                                    <option value="">Select Legal Type</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editBusiness_model" class="form-label">Business Model</label>
                                <select class="form-select" id="editBusiness_model" name="business_model">
                                    <option value="b2c">B2C - Business to Consumer</option>
                                    <option value="b2b">B2B - Business to Business</option>
                                    <option value="b2b2c">B2B2C - Business to Business to Consumer</option>
                                    <option value="c2c">C2C - Consumer to Consumer</option>
                                    <option value="nonprofit">Nonprofit</option>
                                    <option value="government">Government</option>
                                    <option value="cooperative">Cooperative</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Organization Details -->
                    <h6 class="mb-3 mt-4">Organization Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editEmployee_count" class="form-label">Employee Count</label>
                                <select class="form-select" id="editEmployee_count" name="employee_count">
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50">11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-500">201-500 employees</option>
                                    <option value="501-1000">501-1000 employees</option>
                                    <option value="1001-5000">1001-5000 employees</option>
                                    <option value="5001+">5001+ employees</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editAnnual_revenue" class="form-label">Annual Revenue</label>
                                <input type="number" class="form-control" id="editAnnual_revenue" name="annual_revenue" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editRevenue_currency" class="form-label">Currency</label>
                                <select class="form-select" id="editRevenue_currency" name="revenue_currency">
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

                    <!-- Contact Information -->
                    <h6 class="mb-3 mt-4">Contact Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editWebsiteAdmin" class="form-label">Website Admin *</label>
                                <select class="form-select" id="editWebsiteAdmin" name="admin_person_id" required>
                                    <option value="">Select Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Organization Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="editPhone" name="phone">
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    <h6 class="mb-3 mt-4">Status Management</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Organization Status</label>
                                <select class="form-select" id="editStatus" name="status">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editVerification_status" class="form-label">Verification Status</label>
                                <select class="form-select" id="editVerification_status" name="verification_status">
                                    <option value="pending">Pending</option>
                                    <option value="verified">Verified</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editSort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="editSort_order" name="sort_order" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_public" name="is_public">
                                <label class="form-check-label" for="editIs_public">
                                    Public Organization
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_featured" name="is_featured">
                                <label class="form-check-label" for="editIs_featured">
                                    Featured Organization
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_hiring" name="is_hiring">
                                <label class="form-check-label" for="editIs_hiring">
                                    Currently Hiring
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editAllows_reviews" name="allows_reviews">
                                <label class="form-check-label" for="editAllows_reviews">
                                    Allow Reviews
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Organization</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Organization Modal -->
<div class="modal fade" id="viewOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏛️ Organization Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Organization Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Name:</dt>
                                            <dd class="col-sm-7" id="viewName">-</dd>

                                            <dt class="col-sm-5">Legal Name:</dt>
                                            <dd class="col-sm-7" id="viewLegalName">-</dd>

                                            <dt class="col-sm-5">Subdomain:</dt>
                                            <dd class="col-sm-7" id="viewSubdomain">-</dd>

                                            <dt class="col-sm-5">Website:</dt>
                                            <dd class="col-sm-7" id="viewWebsite">-</dd>

                                            <dt class="col-sm-5">Industry:</dt>
                                            <dd class="col-sm-7" id="viewIndustry">-</dd>

                                            <dt class="col-sm-5">Legal Type:</dt>
                                            <dd class="col-sm-7" id="viewLegalType">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Business Model:</dt>
                                            <dd class="col-sm-7" id="viewBusinessModel">-</dd>

                                            <dt class="col-sm-5">Employees:</dt>
                                            <dd class="col-sm-7" id="viewEmployeeCount">-</dd>

                                            <dt class="col-sm-5">Revenue:</dt>
                                            <dd class="col-sm-7" id="viewRevenue">-</dd>

                                            <dt class="col-sm-5">Founded:</dt>
                                            <dd class="col-sm-7" id="viewFounded">-</dd>

                                            <dt class="col-sm-5">Admin:</dt>
                                            <dd class="col-sm-7" id="viewAdmin">-</dd>

                                            <dt class="col-sm-5">Email:</dt>
                                            <dd class="col-sm-7" id="viewEmail">-</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <dt>Description:</dt>
                                    <dd id="viewDescription">-</dd>
                                </div>
                                <div class="mt-3">
                                    <dt>Tagline:</dt>
                                    <dd id="viewTagline">-</dd>
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

                                    <dt class="col-6">Verification:</dt>
                                    <dd class="col-6" id="viewVerification">-</dd>

                                    <dt class="col-6">Featured:</dt>
                                    <dd class="col-6" id="viewFeatured">-</dd>

                                    <dt class="col-6">Public:</dt>
                                    <dd class="col-6" id="viewPublic">-</dd>

                                    <dt class="col-6">Hiring:</dt>
                                    <dd class="col-6" id="viewHiring">-</dd>

                                    <dt class="col-6">ID:</dt>
                                    <dd class="col-6" id="viewOrganizationId">-</dd>

                                    <dt class="col-6">Created:</dt>
                                    <dd class="col-6" id="viewCreatedAt">-</dd>

                                    <dt class="col-6">Updated:</dt>
                                    <dd class="col-6" id="viewUpdatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Organization
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="viewVerifyBtn">
                                            🎗️ Verify Organization
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" id="viewFeatureBtn">
                                            ⭐ Toggle Featured
                                        </button>
                                        <hr>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Organization
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
                                <h6 class="card-title mb-0">Registration Information</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Tax ID:</dt>
                                    <dd class="col-6" id="viewTaxId">-</dd>

                                    <dt class="col-6">Registration #:</dt>
                                    <dd class="col-6" id="viewRegistrationNumber">-</dd>

                                    <dt class="col-6">Phone:</dt>
                                    <dd class="col-6" id="viewPhone">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Business Focus</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewSpecializations">
                                    <dt>Specializations:</dt>
                                    <dd class="mb-2">-</dd>
                                </div>
                                <div id="viewTargetMarkets">
                                    <dt>Target Markets:</dt>
                                    <dd>-</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission & Vision -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Mission Statement</h6>
                            </div>
                            <div class="card-body">
                                <p id="viewMission" class="mb-0">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Vision Statement</h6>
                            </div>
                            <div class="card-body">
                                <p id="viewVision" class="mb-0">-</p>
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