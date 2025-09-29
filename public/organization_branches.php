<?php
require_once '../includes/header.php';
require_once '../includes/sub_menu.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏢 Organization Branches</h1>
            <p class="text-muted">Manage organization branches, locations, and operational units</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button id="newBranchBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                    ➕ Add Branch
                </button>
                <button id="exportBtn" class="btn btn-outline-secondary">
                    📊 Export
                </button>
                <button id="performanceBtn" class="btn btn-outline-info">
                    📈 Performance
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
                            <h6 class="card-subtitle mb-1 small">Total Branches</h6>
                            <h4 class="card-title mb-0" id="totalBranches">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Active</h6>
                            <h4 class="card-title mb-0" id="activeBranches">0</h4>
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
                            <h4 class="card-title mb-0" id="verifiedBranches">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Headquarters</h6>
                            <h4 class="card-title mb-0" id="headquartersBranches">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Flagship</h6>
                            <h4 class="card-title mb-0" id="flagshipBranches">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Organizations</h6>
                            <h4 class="card-title mb-0" id="totalOrganizations">0</h4>
                        </div>
                        <div class="stats-icon">🏛️</div>
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
                <input type="text" id="searchBranches" class="form-control" placeholder="Search branches, names, codes...">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Status</option>
                    <option value="planning">Planning</option>
                    <option value="under_construction">Under Construction</option>
                    <option value="opening_soon">Opening Soon</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="temporarily_closed">Temporarily Closed</option>
                    <option value="under_renovation">Under Renovation</option>
                    <option value="suspended">Suspended</option>
                    <option value="closed">Closed</option>
                </select>
                <select id="typeFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Types</option>
                    <option value="main">Main</option>
                    <option value="regional">Regional</option>
                    <option value="flagship">Flagship</option>
                    <option value="outlet">Outlet</option>
                    <option value="kiosk">Kiosk</option>
                    <option value="warehouse">Warehouse</option>
                    <option value="office">Office</option>
                    <option value="franchise">Franchise</option>
                </select>
                <select id="organizationFilter" class="form-select" style="max-width: 180px;">
                    <option value="">All Organizations</option>
                </select>
                <select id="verificationFilter" class="form-select" style="max-width: 140px;">
                    <option value="">All Verification</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                    <option value="suspended">Suspended</option>
                </select>
                <select id="priorityFilter" class="form-select" style="max-width: 120px;">
                    <option value="">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
                <div class="btn-group" role="group">
                    <input type="checkbox" class="btn-check" id="headquartersFilter" autocomplete="off">
                    <label class="btn btn-outline-primary" for="headquartersFilter">🏛️ HQ</label>

                    <input type="checkbox" class="btn-check" id="flagshipFilter" autocomplete="off">
                    <label class="btn btn-outline-warning" for="flagshipFilter">⭐ Flagship</label>

                    <input type="checkbox" class="btn-check" id="featuredFilter" autocomplete="off">
                    <label class="btn btn-outline-success" for="featuredFilter">✨ Featured</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Branches</h5>
                    <small class="text-muted" id="branchCount">0 branches</small>
                </div>
                <div class="card-body">
                    <div id="branchesDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading branches...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBranchForm">
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="organization_id" class="form-label">Organization *</label>
                                <select class="form-select" id="organization_id" name="organization_id" required>
                                    <option value="">Select Organization</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_type" class="form-label">Branch Type *</label>
                                <select class="form-select" id="branch_type" name="branch_type" required>
                                    <option value="outlet">Outlet</option>
                                    <option value="main">Main Branch</option>
                                    <option value="regional">Regional Office</option>
                                    <option value="flagship">Flagship Store</option>
                                    <option value="kiosk">Kiosk</option>
                                    <option value="warehouse">Warehouse</option>
                                    <option value="distribution">Distribution Center</option>
                                    <option value="office">Office</option>
                                    <option value="showroom">Showroom</option>
                                    <option value="service_center">Service Center</option>
                                    <option value="franchise">Franchise</option>
                                    <option value="popup">Pop-up Store</option>
                                    <option value="mobile">Mobile Unit</option>
                                    <option value="virtual">Virtual Branch</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Branch Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">Friendly name for the branch</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Branch Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                       placeholder="Leave empty for auto-generation">
                                <div class="form-text">Unique identifier (auto-generated if empty)</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Detailed description of the branch"></textarea>
                    </div>

                    <!-- Management -->
                    <h6 class="mb-3 mt-4">Management</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="manager_person_id" class="form-label">Branch Manager</label>
                                <select class="form-select" id="manager_person_id" name="manager_person_id">
                                    <option value="">Select Manager</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assistant_manager_person_id" class="form-label">Assistant Manager</label>
                                <select class="form-select" id="assistant_manager_person_id" name="assistant_manager_person_id">
                                    <option value="">Select Assistant Manager</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Operational Details -->
                    <h6 class="mb-3 mt-4">Operational Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="opening_date" class="form-label">Opening Date</label>
                                <input type="date" class="form-control" id="opening_date" name="opening_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="employee_count" class="form-label">Employee Count</label>
                                <select class="form-select" id="employee_count" name="employee_count">
                                    <option value="1-5">1-5 employees</option>
                                    <option value="6-10">6-10 employees</option>
                                    <option value="11-25">11-25 employees</option>
                                    <option value="26-50">26-50 employees</option>
                                    <option value="51-100">51-100 employees</option>
                                    <option value="101-200">101-200 employees</option>
                                    <option value="201+">201+ employees</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority_level" class="form-label">Priority Level</label>
                                <select class="form-select" id="priority_level" name="priority_level">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="monthly_revenue" class="form-label">Monthly Revenue</label>
                                <input type="number" class="form-control" id="monthly_revenue" name="monthly_revenue"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="monthly_target" class="form-label">Monthly Target</label>
                                <input type="number" class="form-control" id="monthly_target" name="monthly_target"
                                       min="0" step="0.01" placeholder="0.00">
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
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="website_url" class="form-label">Website URL</label>
                                <input type="url" class="form-control" id="website_url" name="website_url">
                            </div>
                        </div>
                    </div>

                    <!-- Services & Features -->
                    <h6 class="mb-3 mt-4">Services & Features</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="services_offered" class="form-label">Services Offered</label>
                                <input type="text" class="form-control" id="services_offered" name="services_offered"
                                       placeholder="Comma-separated list of services">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="specializations" class="form-label">Specializations</label>
                                <input type="text" class="form-control" id="specializations" name="specializations"
                                       placeholder="Comma-separated list of specializations">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="facilities" class="form-label">Facilities</label>
                                <input type="text" class="form-control" id="facilities" name="facilities"
                                       placeholder="Comma-separated list of facilities">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_methods" class="form-label">Payment Methods</label>
                                <input type="text" class="form-control" id="payment_methods" name="payment_methods"
                                       placeholder="Cash, Credit Card, Digital, etc.">
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="mb-3">
                        <label for="operating_hours" class="form-label">Operating Hours</label>
                        <textarea class="form-control" id="operating_hours" name="operating_hours" rows="2"
                                  placeholder="Mon-Fri: 9:00 AM - 6:00 PM, Sat: 10:00 AM - 4:00 PM"></textarea>
                    </div>

                    <!-- Branch Flags -->
                    <h6 class="mb-3 mt-4">Branch Type & Features</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_headquarters" name="is_headquarters">
                                <label class="form-check-label" for="is_headquarters">
                                    Headquarters Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_flagship" name="is_flagship">
                                <label class="form-check-label" for="is_flagship">
                                    Flagship Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_franchise" name="is_franchise">
                                <label class="form-check-label" for="is_franchise">
                                    Franchise Branch
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_temporary" name="is_temporary">
                                <label class="form-check-label" for="is_temporary">
                                    Temporary Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_seasonal" name="is_seasonal">
                                <label class="form-check-label" for="is_seasonal">
                                    Seasonal Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="parking_available" name="parking_available">
                                <label class="form-check-label" for="parking_available">
                                    Parking Available
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="wifi_available" name="wifi_available" checked>
                                <label class="form-check-label" for="wifi_available">
                                    WiFi Available
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="appointment_required" name="appointment_required">
                                <label class="form-check-label" for="appointment_required">
                                    Appointment Required
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="walk_ins_welcome" name="walk_ins_welcome" checked>
                                <label class="form-check-label" for="walk_ins_welcome">
                                    Walk-ins Welcome
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Visibility Settings -->
                    <h6 class="mb-3 mt-4">Visibility & Features</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">
                                    Featured Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" checked>
                                <label class="form-check-label" for="is_public">
                                    Public Branch
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="allows_reviews" name="allows_reviews" checked>
                                <label class="form-check-label" for="allows_reviews">
                                    Allow Reviews
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="display_online" name="display_online" checked>
                                <label class="form-check-label" for="display_online">
                                    Display Online
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBranchForm">
                <input type="hidden" id="editBranchId" name="id">
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editOrganization_id" class="form-label">Organization *</label>
                                <select class="form-select" id="editOrganization_id" name="organization_id" required>
                                    <option value="">Select Organization</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editBranch_type" class="form-label">Branch Type *</label>
                                <select class="form-select" id="editBranch_type" name="branch_type" required>
                                    <option value="outlet">Outlet</option>
                                    <option value="main">Main Branch</option>
                                    <option value="regional">Regional Office</option>
                                    <option value="flagship">Flagship Store</option>
                                    <option value="kiosk">Kiosk</option>
                                    <option value="warehouse">Warehouse</option>
                                    <option value="distribution">Distribution Center</option>
                                    <option value="office">Office</option>
                                    <option value="showroom">Showroom</option>
                                    <option value="service_center">Service Center</option>
                                    <option value="franchise">Franchise</option>
                                    <option value="popup">Pop-up Store</option>
                                    <option value="mobile">Mobile Unit</option>
                                    <option value="virtual">Virtual Branch</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Branch Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editCode" class="form-label">Branch Code</label>
                                <input type="text" class="form-control" id="editCode" name="code">
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    <h6 class="mb-3 mt-4">Status Management</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Branch Status</label>
                                <select class="form-select" id="editStatus" name="status">
                                    <option value="planning">Planning</option>
                                    <option value="under_construction">Under Construction</option>
                                    <option value="opening_soon">Opening Soon</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="temporarily_closed">Temporarily Closed</option>
                                    <option value="under_renovation">Under Renovation</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="closing">Closing</option>
                                    <option value="closed">Closed</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editVerification_status" class="form-label">Verification Status</label>
                                <select class="form-select" id="editVerification_status" name="verification_status">
                                    <option value="pending">Pending</option>
                                    <option value="in_review">In Review</option>
                                    <option value="verified">Verified</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="expired">Expired</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editPriority_level" class="form-label">Priority Level</label>
                                <select class="form-select" id="editPriority_level" name="priority_level">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <h6 class="mb-3 mt-4">Performance Metrics</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editPerformance_score" class="form-label">Performance Score (%)</label>
                                <input type="number" class="form-control" id="editPerformance_score" name="performance_score"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editCustomer_satisfaction" class="form-label">Customer Satisfaction (%)</label>
                                <input type="number" class="form-control" id="editCustomer_satisfaction" name="customer_satisfaction"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editStaff_satisfaction" class="form-label">Staff Satisfaction (%)</label>
                                <input type="number" class="form-control" id="editStaff_satisfaction" name="staff_satisfaction"
                                       min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Branch Flags -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_headquarters" name="is_headquarters">
                                <label class="form-check-label" for="editIs_headquarters">
                                    Headquarters Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_flagship" name="is_flagship">
                                <label class="form-check-label" for="editIs_flagship">
                                    Flagship Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_featured" name="is_featured">
                                <label class="form-check-label" for="editIs_featured">
                                    Featured Branch
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editIs_public" name="is_public">
                                <label class="form-check-label" for="editIs_public">
                                    Public Branch
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editAllows_reviews" name="allows_reviews">
                                <label class="form-check-label" for="editAllows_reviews">
                                    Allow Reviews
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editDisplay_online" name="display_online">
                                <label class="form-check-label" for="editDisplay_online">
                                    Display Online
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Branch Modal -->
<div class="modal fade" id="viewBranchModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏢 Branch Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Branch Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Name:</dt>
                                            <dd class="col-sm-7" id="viewName">-</dd>

                                            <dt class="col-sm-5">Code:</dt>
                                            <dd class="col-sm-7" id="viewCode">-</dd>

                                            <dt class="col-sm-5">Organization:</dt>
                                            <dd class="col-sm-7" id="viewOrganization">-</dd>

                                            <dt class="col-sm-5">Type:</dt>
                                            <dd class="col-sm-7" id="viewType">-</dd>

                                            <dt class="col-sm-5">Manager:</dt>
                                            <dd class="col-sm-7" id="viewManager">-</dd>

                                            <dt class="col-sm-5">Opening Date:</dt>
                                            <dd class="col-sm-7" id="viewOpeningDate">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Employee Count:</dt>
                                            <dd class="col-sm-7" id="viewEmployeeCount">-</dd>

                                            <dt class="col-sm-5">Revenue:</dt>
                                            <dd class="col-sm-7" id="viewRevenue">-</dd>

                                            <dt class="col-sm-5">Target:</dt>
                                            <dd class="col-sm-7" id="viewTarget">-</dd>

                                            <dt class="col-sm-5">Performance:</dt>
                                            <dd class="col-sm-7" id="viewPerformance">-</dd>

                                            <dt class="col-sm-5">Phone:</dt>
                                            <dd class="col-sm-7" id="viewPhone">-</dd>

                                            <dt class="col-sm-5">Email:</dt>
                                            <dd class="col-sm-7" id="viewEmail">-</dd>
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
                                <h6 class="card-title mb-0">Status & Actions</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Status:</dt>
                                    <dd class="col-6" id="viewStatus">-</dd>

                                    <dt class="col-6">Verification:</dt>
                                    <dd class="col-6" id="viewVerification">-</dd>

                                    <dt class="col-6">Priority:</dt>
                                    <dd class="col-6" id="viewPriority">-</dd>

                                    <dt class="col-6">Headquarters:</dt>
                                    <dd class="col-6" id="viewHeadquarters">-</dd>

                                    <dt class="col-6">Flagship:</dt>
                                    <dd class="col-6" id="viewFlagship">-</dd>

                                    <dt class="col-6">Featured:</dt>
                                    <dd class="col-6" id="viewFeatured">-</dd>

                                    <dt class="col-6">ID:</dt>
                                    <dd class="col-6" id="viewBranchId">-</dd>

                                    <dt class="col-6">Created:</dt>
                                    <dd class="col-6" id="viewCreatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Branch
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="viewVerifyBtn">
                                            🎗️ Verify Branch
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" id="viewFeatureBtn">
                                            ⭐ Toggle Featured
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" id="viewHeadquartersBtn">
                                            🏛️ Toggle HQ
                                        </button>
                                        <hr>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Branch
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
                                <h6 class="card-title mb-0">Services & Features</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewServices">
                                    <dt>Services:</dt>
                                    <dd class="mb-2" id="viewServicesOffered">-</dd>
                                </div>
                                <div id="viewSpecializations">
                                    <dt>Specializations:</dt>
                                    <dd class="mb-2" id="viewSpecializationsText">-</dd>
                                </div>
                                <div id="viewFacilities">
                                    <dt>Facilities:</dt>
                                    <dd id="viewFacilitiesText">-</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Performance Metrics</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Performance:</dt>
                                    <dd class="col-6" id="viewPerformanceScore">-</dd>

                                    <dt class="col-6">Customer Sat.:</dt>
                                    <dd class="col-6" id="viewCustomerSatisfaction">-</dd>

                                    <dt class="col-6">Staff Sat.:</dt>
                                    <dd class="col-6" id="viewStaffSatisfaction">-</dd>

                                    <dt class="col-6">Achievement:</dt>
                                    <dd class="col-6" id="viewTargetAchievement">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operating Information -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Operating Hours</h6>
                            </div>
                            <div class="card-body">
                                <p id="viewOperatingHours" class="mb-0">-</p>
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
    let branches = [];
    let filteredBranches = [];
    let organizations = [];
    let persons = [];

    // Load initial data
    loadOrganizations();
    loadPersons();
    loadBranches();

    // Event listeners for search and filters
    document.getElementById('searchBranches').addEventListener('input', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('organizationFilter').addEventListener('change', applyFilters);
    document.getElementById('verificationFilter').addEventListener('change', applyFilters);
    document.getElementById('priorityFilter').addEventListener('change', applyFilters);
    document.getElementById('headquartersFilter').addEventListener('change', applyFilters);
    document.getElementById('flagshipFilter').addEventListener('change', applyFilters);
    document.getElementById('featuredFilter').addEventListener('change', applyFilters);

    // Form submissions
    document.getElementById('addBranchForm').addEventListener('submit', handleAddBranch);
    document.getElementById('editBranchForm').addEventListener('submit', handleEditBranch);

    function loadOrganizations() {
        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getAllOrganizations'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                organizations = data.data;
                populateOrganizationDropdowns();
                populateOrganizationFilter();
            }
        })
        .catch(error => {
            console.error('Error loading organizations:', error);
        });
    }

    function loadPersons() {
        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'getAllPersons'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                persons = data.data;
                populatePersonDropdowns();
            }
        })
        .catch(error => {
            console.error('Error loading persons:', error);
        });
    }

    function populateOrganizationDropdowns() {
        const addSelect = document.getElementById('organization_id');
        const editSelect = document.getElementById('editOrganization_id');

        [addSelect, editSelect].forEach(select => {
            select.innerHTML = '<option value="">Select Organization</option>';
            organizations.forEach(org => {
                const option = document.createElement('option');
                option.value = org.id;
                option.textContent = org.display_name || org.name;
                select.appendChild(option);
            });
        });
    }

    function populatePersonDropdowns() {
        const addManagerSelect = document.getElementById('manager_person_id');
        const addAssistantSelect = document.getElementById('assistant_manager_person_id');

        [addManagerSelect, addAssistantSelect].forEach(select => {
            select.innerHTML = '<option value="">Select Person</option>';
            persons.forEach(person => {
                const option = document.createElement('option');
                option.value = person.id;
                option.textContent = person.full_name || `${person.first_name || ''} ${person.last_name || ''}`.trim();
                select.appendChild(option);
            });
        });
    }

    function populateOrganizationFilter() {
        const filterSelect = document.getElementById('organizationFilter');
        filterSelect.innerHTML = '<option value="">All Organizations</option>';
        organizations.forEach(org => {
            const option = document.createElement('option');
            option.value = org.id;
            option.textContent = org.display_name || org.name;
            filterSelect.appendChild(option);
        });
    }

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
                applyFilters();
                updateStatistics();
            }
        })
        .catch(error => {
            console.error('Error loading branches:', error);
            document.getElementById('branchesDisplay').innerHTML = `
                <div class="text-center py-4">
                    <p class="text-danger">Error loading branches: ${error.message}</p>
                </div>
            `;
        });
    }

    function applyFilters() {
        const search = document.getElementById('searchBranches').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const organizationFilter = document.getElementById('organizationFilter').value;
        const verificationFilter = document.getElementById('verificationFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        const headquartersOnly = document.getElementById('headquartersFilter').checked;
        const flagshipOnly = document.getElementById('flagshipFilter').checked;
        const featuredOnly = document.getElementById('featuredFilter').checked;

        filteredBranches = branches.filter(branch => {
            // Search filter
            if (search && !branchMatchesSearch(branch, search)) return false;

            // Status filter
            if (statusFilter && branch.status !== statusFilter) return false;

            // Type filter
            if (typeFilter && branch.branch_type !== typeFilter) return false;

            // Organization filter
            if (organizationFilter && branch.organization_id != organizationFilter) return false;

            // Verification filter
            if (verificationFilter && branch.verification_status !== verificationFilter) return false;

            // Priority filter
            if (priorityFilter && branch.priority_level !== priorityFilter) return false;

            // Headquarters only
            if (headquartersOnly && !branch.is_headquarters) return false;

            // Flagship only
            if (flagshipOnly && !branch.is_flagship) return false;

            // Featured only
            if (featuredOnly && !branch.is_featured) return false;

            return true;
        });

        displayBranches();
    }

    function branchMatchesSearch(branch, search) {
        const searchFields = [
            branch.name,
            branch.code,
            branch.description,
            branch.organization_name,
            branch.services_offered,
            branch.specializations,
            branch.manager_name
        ];

        return searchFields.some(field =>
            field && field.toLowerCase().includes(search)
        );
    }

    function displayBranches() {
        const container = document.getElementById('branchesDisplay');
        const countElement = document.getElementById('branchCount');

        countElement.textContent = `${filteredBranches.length} branch${filteredBranches.length !== 1 ? 'es' : ''}`;

        if (filteredBranches.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-muted">No branches found matching your criteria.</p>
                </div>
            `;
            return;
        }

        // Responsive display: desktop table, mobile cards
        const isDesktop = window.innerWidth >= 992;

        if (isDesktop) {
            displayBranchesTable(container);
        } else {
            displayBranchesCards(container);
        }
    }

    function displayBranchesTable(container) {
        let html = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Branch Name</th>
                            <th>Organization</th>
                            <th>Type</th>
                            <th>Manager</th>
                            <th>Revenue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        filteredBranches.forEach(branch => {
            const statusBadge = getStatusBadge(branch.status);
            const typeBadge = getTypeBadge(branch.branch_type);
            const verifiedBadge = branch.verification_status === 'verified' ? '<span class="badge bg-success">🎗️</span>' : '';
            const headquartersBadge = branch.is_headquarters ? '<span class="badge bg-primary">🏛️ HQ</span>' : '';
            const flagshipBadge = branch.is_flagship ? '<span class="badge bg-warning">⭐ Flagship</span>' : '';

            html += `
                <tr>
                    <td>
                        <div class="fw-bold">${branch.display_name || 'Unnamed Branch'}</div>
                        <div class="small text-muted">${branch.code || 'No Code'}</div>
                        ${headquartersBadge} ${flagshipBadge}
                    </td>
                    <td>${branch.organization_name || '-'}</td>
                    <td>${typeBadge}</td>
                    <td>${branch.manager_name || 'No Manager'}</td>
                    <td>${branch.formatted_revenue || '-'}</td>
                    <td>
                        ${statusBadge}
                        ${verifiedBadge}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" onclick="viewBranch(${branch.id})" title="View Details">
                                👁️
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="editBranch(${branch.id})" title="Edit">
                                ✏️
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteBranch(${branch.id})" title="Delete">
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

    function displayBranchesCards(container) {
        let html = '<div class="row">';

        filteredBranches.forEach(branch => {
            const statusBadge = getStatusBadge(branch.status);
            const typeBadge = getTypeBadge(branch.branch_type);
            const verifiedBadge = branch.verification_status === 'verified' ? '<span class="badge bg-success">🎗️</span>' : '';
            const headquartersBadge = branch.is_headquarters ? '<span class="badge bg-primary">🏛️</span>' : '';
            const flagshipBadge = branch.is_flagship ? '<span class="badge bg-warning">⭐</span>' : '';

            html += `
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">${branch.display_name || 'Unnamed Branch'}</h6>
                                <div>
                                    ${verifiedBadge} ${headquartersBadge} ${flagshipBadge}
                                </div>
                            </div>
                            <p class="card-text text-muted small">${branch.organization_name || '-'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    ${typeBadge} ${statusBadge}
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary btn-sm" onclick="viewBranch(${branch.id})">
                                        View Details
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editBranch(${branch.id})">
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
            opening_soon: 'bg-primary',
            active: 'bg-success',
            inactive: 'bg-secondary',
            temporarily_closed: 'bg-warning',
            under_renovation: 'bg-info',
            suspended: 'bg-danger',
            closing: 'bg-warning',
            closed: 'bg-dark',
            archived: 'bg-secondary'
        };
        return `<span class="badge ${statusConfig[status] || 'bg-secondary'}">${status.replace('_', ' ')}</span>`;
    }

    function getTypeBadge(type) {
        const typeConfig = {
            main: '🏛️',
            regional: '🏢',
            flagship: '⭐',
            outlet: '🏪',
            kiosk: '🏬',
            warehouse: '📦',
            distribution: '🚚',
            office: '🏢',
            showroom: '🪟',
            service_center: '🔧',
            franchise: '🤝',
            popup: '⏰',
            mobile: '🚐',
            virtual: '💻'
        };
        return `<span class="badge bg-primary">${typeConfig[type] || '🏢'} ${type.replace('_', ' ')}</span>`;
    }

    function updateStatistics() {
        document.getElementById('totalBranches').textContent = branches.length;
        document.getElementById('activeBranches').textContent = branches.filter(b => b.status === 'active').length;
        document.getElementById('verifiedBranches').textContent = branches.filter(b => b.verification_status === 'verified').length;
        document.getElementById('headquartersBranches').textContent = branches.filter(b => b.is_headquarters).length;
        document.getElementById('flagshipBranches').textContent = branches.filter(b => b.is_flagship).length;

        const uniqueOrganizations = new Set(branches.map(b => b.organization_id).filter(Boolean));
        document.getElementById('totalOrganizations').textContent = uniqueOrganizations.size;
    }

    function handleAddBranch(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Convert checkboxes
        const checkboxes = ['is_headquarters', 'is_flagship', 'is_franchise', 'is_temporary', 'is_seasonal',
                           'is_featured', 'is_public', 'allows_reviews', 'display_online', 'parking_available',
                           'wifi_available', 'appointment_required', 'walk_ins_welcome'];

        checkboxes.forEach(checkbox => {
            data[checkbox] = document.getElementById(checkbox).checked ? 1 : 0;
        });

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'createOrganizationBranch',
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('addBranchModal')).hide();
                document.getElementById('addBranchForm').reset();
                loadBranches();
                alert('Branch created successfully!');
            } else {
                alert('Error: ' + (result.error || 'Failed to create branch'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating branch');
        });
    }

    function handleEditBranch(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        // Convert checkboxes
        const checkboxes = ['is_headquarters', 'is_flagship', 'is_featured', 'is_public', 'allows_reviews', 'display_online'];

        checkboxes.forEach(checkbox => {
            data[checkbox] = document.getElementById('edit' + checkbox.charAt(0).toUpperCase() + checkbox.slice(1)).checked ? 1 : 0;
        });

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'updateOrganizationBranch',
                data: data
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('editBranchModal')).hide();
                loadBranches();
                alert('Branch updated successfully!');
            } else {
                alert('Error: ' + (result.error || 'Failed to update branch'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating branch');
        });
    }

    // Global functions for button actions
    window.viewBranch = function(id) {
        const branch = branches.find(b => b.id === id);
        if (!branch) return;

        // Populate view modal
        document.getElementById('viewName').textContent = branch.display_name || 'Unnamed Branch';
        document.getElementById('viewCode').textContent = branch.code || '-';
        document.getElementById('viewOrganization').textContent = branch.organization_name || '-';
        document.getElementById('viewType').innerHTML = getTypeBadge(branch.branch_type);
        document.getElementById('viewManager').textContent = branch.manager_name || 'No Manager';
        document.getElementById('viewOpeningDate').textContent = branch.opening_date || '-';
        document.getElementById('viewEmployeeCount').textContent = branch.employee_count || '-';
        document.getElementById('viewRevenue').textContent = branch.formatted_revenue || '-';
        document.getElementById('viewTarget').textContent = branch.formatted_target || '-';
        document.getElementById('viewPerformance').textContent = branch.performance_score ? branch.performance_score + '%' : '-';
        document.getElementById('viewPhone').textContent = branch.phone || '-';
        document.getElementById('viewEmail').textContent = branch.email || '-';
        document.getElementById('viewDescription').textContent = branch.description || '-';

        document.getElementById('viewStatus').innerHTML = getStatusBadge(branch.status);
        document.getElementById('viewVerification').innerHTML = getVerificationBadge(branch.verification_status);
        document.getElementById('viewPriority').innerHTML = getPriorityBadge(branch.priority_level);
        document.getElementById('viewHeadquarters').innerHTML = branch.is_headquarters ?
            '<span class="badge bg-primary">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewFlagship').innerHTML = branch.is_flagship ?
            '<span class="badge bg-warning">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        document.getElementById('viewFeatured').innerHTML = branch.is_featured ?
            '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';

        document.getElementById('viewBranchId').textContent = branch.id;
        document.getElementById('viewCreatedAt').textContent = branch.created_at || '-';

        // Services & Features
        document.getElementById('viewServicesOffered').textContent = branch.services_offered || '-';
        document.getElementById('viewSpecializationsText').textContent = branch.specializations || '-';
        document.getElementById('viewFacilitiesText').textContent = branch.facilities || '-';

        // Performance metrics
        document.getElementById('viewPerformanceScore').textContent = branch.performance_score ? branch.performance_score + '%' : '-';
        document.getElementById('viewCustomerSatisfaction').textContent = branch.customer_satisfaction ? branch.customer_satisfaction + '%' : '-';
        document.getElementById('viewStaffSatisfaction').textContent = branch.staff_satisfaction ? branch.staff_satisfaction + '%' : '-';
        document.getElementById('viewTargetAchievement').textContent = branch.target_achievement_percentage ? branch.target_achievement_percentage + '%' : '-';

        document.getElementById('viewOperatingHours').textContent = branch.operating_hours || '-';

        // Set up action buttons
        document.getElementById('viewEditBtn').onclick = () => editBranch(id);
        document.getElementById('viewDeleteBtn').onclick = () => deleteBranch(id);

        const modal = new bootstrap.Modal(document.getElementById('viewBranchModal'));
        modal.show();
    };

    window.editBranch = function(id) {
        const branch = branches.find(b => b.id === id);
        if (!branch) return;

        // Populate edit form
        document.getElementById('editBranchId').value = branch.id;
        document.getElementById('editOrganization_id').value = branch.organization_id || '';
        document.getElementById('editBranch_type').value = branch.branch_type || '';
        document.getElementById('editName').value = branch.name || '';
        document.getElementById('editCode').value = branch.code || '';
        document.getElementById('editStatus').value = branch.status || '';
        document.getElementById('editVerification_status').value = branch.verification_status || '';
        document.getElementById('editPriority_level').value = branch.priority_level || '';
        document.getElementById('editPerformance_score').value = branch.performance_score || '';
        document.getElementById('editCustomer_satisfaction').value = branch.customer_satisfaction || '';
        document.getElementById('editStaff_satisfaction').value = branch.staff_satisfaction || '';
        document.getElementById('editIs_headquarters').checked = branch.is_headquarters;
        document.getElementById('editIs_flagship').checked = branch.is_flagship;
        document.getElementById('editIs_featured').checked = branch.is_featured;
        document.getElementById('editIs_public').checked = branch.is_public;
        document.getElementById('editAllows_reviews').checked = branch.allows_reviews;
        document.getElementById('editDisplay_online').checked = branch.display_online;

        const modal = new bootstrap.Modal(document.getElementById('editBranchModal'));
        modal.show();
    };

    window.deleteBranch = function(id) {
        if (!confirm('Are you sure you want to delete this branch?')) return;

        fetch('../api/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'deleteOrganizationBranch',
                id: id
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                loadBranches();
                alert('Branch deleted successfully!');

                // Close any open modals
                ['viewBranchModal', 'editBranchModal'].forEach(modalId => {
                    const modalEl = document.getElementById(modalId);
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                });
            } else {
                alert('Error: ' + (result.error || 'Failed to delete branch'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting branch');
        });
    };

    function getVerificationBadge(status) {
        const statusConfig = {
            pending: 'bg-warning',
            in_review: 'bg-info',
            verified: 'bg-success',
            rejected: 'bg-danger',
            expired: 'bg-secondary',
            suspended: 'bg-danger'
        };
        return `<span class="badge ${statusConfig[status] || 'bg-secondary'}">${status.replace('_', ' ')}</span>`;
    }

    function getPriorityBadge(priority) {
        const priorityConfig = {
            low: 'bg-secondary',
            medium: 'bg-primary',
            high: 'bg-warning',
            critical: 'bg-danger'
        };
        return `<span class="badge ${priorityConfig[priority] || 'bg-secondary'}">${priority}</span>`;
    }

    // Handle window resize for responsive display
    window.addEventListener('resize', () => {
        displayBranches();
    });
});
</script>