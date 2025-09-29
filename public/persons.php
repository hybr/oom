<?php
require_once '../includes/header.php';
require_once '../includes/sub_menu.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">👤 Person Management</h1>
            <p class="text-muted">Manage persons with real-time updates</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newPersonBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPersonModal">
                ➕ Add Person
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
                            <h6 class="card-subtitle mb-1 small">Total Persons</h6>
                            <h4 class="card-title mb-0" id="totalPersons">0</h4>
                        </div>
                        <div class="stats-icon">👥</div>
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
                            <h4 class="card-title mb-0" id="activePersons">0</h4>
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
                            <h4 class="card-title mb-0" id="inactivePersons">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Recent</h6>
                            <h4 class="card-title mb-0" id="recentPersons">0</h4>
                        </div>
                        <div class="stats-icon">🕐</div>
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
                <input type="text" id="searchPersons" class="form-control" placeholder="Search persons...">
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

    <!-- Persons Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Person List</h5>
                </div>
                <div class="card-body">
                    <div id="personsTable">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading persons...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Person Modal -->
<div class="modal fade" id="addPersonModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Person</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPersonForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Person</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Person Modal -->
<div class="modal fade" id="editPersonModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Person</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPersonForm">
                <input type="hidden" id="editPersonId" name="id">
                <div class="modal-body">
                    <!-- Same form fields as add modal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editFirstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editLastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPhone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="editPhone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDateOfBirth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="editDateOfBirth" name="date_of_birth">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editGender" class="form-label">Gender</label>
                                <select class="form-select" id="editGender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Person</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Person Detail Modal -->
<div class="modal fade" id="viewPersonModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">👤 Person Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Full Name:</dt>
                                    <dd class="col-sm-8" id="viewFullName">-</dd>

                                    <dt class="col-sm-4">Email:</dt>
                                    <dd class="col-sm-8" id="viewEmail">-</dd>

                                    <dt class="col-sm-4">Phone:</dt>
                                    <dd class="col-sm-8" id="viewPhone">-</dd>

                                    <dt class="col-sm-4">Date of Birth:</dt>
                                    <dd class="col-sm-8" id="viewDateOfBirth">-</dd>

                                    <dt class="col-sm-4">Age:</dt>
                                    <dd class="col-sm-8" id="viewAge">-</dd>

                                    <dt class="col-sm-4">Gender:</dt>
                                    <dd class="col-sm-8" id="viewGender">-</dd>

                                    <dt class="col-sm-4">Address:</dt>
                                    <dd class="col-sm-8" id="viewAddress">-</dd>
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
                                    <dd class="col-6 col-md-7" id="viewPersonId">-</dd>

                                    <dt class="col-6 col-md-5">Created:</dt>
                                    <dd class="col-6 col-md-7" id="viewCreatedAt">-</dd>

                                    <dt class="col-6 col-md-5">Updated:</dt>
                                    <dd class="col-6 col-md-7" id="viewUpdatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Person
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Person
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