<?php
$pageTitle = "Organization Positions";
$pageDescription = "Manage organization positions";

include_once '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">💼 Organization Positions</h1>
                    <p class="text-muted mb-0">Manage and organize positions across organizations</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="showFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddPositionModal()">
                        <i class="fas fa-plus"></i> Add Position
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" id="statisticsCards">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-briefcase text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Positions</h6>
                            <h4 class="card-title mb-0" id="totalPositions">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Active</h6>
                            <h4 class="card-title mb-0" id="activePositions">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-exclamation-circle text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Vacant</h6>
                            <h4 class="card-title mb-0" id="vacantPositions">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-star text-danger"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Critical</h6>
                            <h4 class="card-title mb-0" id="criticalPositions">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Row (Hidden by default) -->
    <div class="row mb-4 d-none" id="filtersRow">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="organizationFilter" class="form-label">Organization</label>
                            <select class="form-select" id="organizationFilter">
                                <option value="">All Organizations</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="departmentFilter" class="form-label">Department</label>
                            <select class="form-select" id="departmentFilter">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="positionStatusFilter" class="form-label">Status</label>
                            <select class="form-select" id="positionStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Posted">Posted</option>
                                <option value="Filled">Filled</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search positions...">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary me-2" onclick="applyFilters()">
                                <i class="fas fa-search"></i> Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group" role="group" aria-label="View toggle">
                    <button type="button" class="btn btn-outline-primary active" id="tableViewBtn" onclick="switchToTableView()">
                        <i class="fas fa-table"></i> Table
                    </button>
                    <button type="button" class="btn btn-outline-primary d-md-none" id="cardViewBtn" onclick="switchToCardView()">
                        <i class="fas fa-th-large"></i> Cards
                    </button>
                </div>
                <div id="paginationInfo" class="text-muted small"></div>
            </div>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableView">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-3">Position</th>
                                <th class="border-0">Organization</th>
                                <th class="border-0">Department</th>
                                <th class="border-0">Designation</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Vacancies</th>
                                <th class="border-0 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="positionsTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Card View (Mobile) -->
    <div id="cardView" class="d-none">
        <div id="positionsCardContainer">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Position pagination">
                <ul class="pagination justify-content-center" id="paginationContainer">
                    <!-- Pagination will be generated here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Position Modal -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="positionModalLabel">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="positionForm">
                    <input type="hidden" id="positionId" name="id">

                    <!-- Basic Information -->
                    <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="positionTitle" class="form-label">Position Title *</label>
                            <input type="text" class="form-control" id="positionTitle" name="position_title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="positionCode" class="form-label">Position Code</label>
                            <input type="text" class="form-control" id="positionCode" name="position_code">
                            <small class="text-muted">Auto-generated if left blank</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="organizationId" class="form-label">Organization *</label>
                            <select class="form-select" id="organizationId" name="organization_id" required>
                                <option value="">Select Organization</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="designationId" class="form-label">Designation *</label>
                            <select class="form-select" id="designationId" name="popular_organization_designation_id" required>
                                <option value="">Select Designation</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="departmentId" class="form-label">Department</label>
                            <select class="form-select" id="departmentId" name="popular_organization_department_id">
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="teamId" class="form-label">Team</label>
                            <select class="form-select" id="teamId" name="popular_organization_team_id">
                                <option value="">Select Team</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Employment Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Employment Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="positionType" class="form-label">Position Type</label>
                            <select class="form-select" id="positionType" name="position_type">
                                <option value="Permanent">Permanent</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Contract">Contract</option>
                                <option value="Consultant">Consultant</option>
                                <option value="Intern">Intern</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="employmentType" class="form-label">Employment Type</label>
                            <select class="form-select" id="employmentType" name="employment_type">
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="workMode" class="form-label">Work Mode</label>
                            <select class="form-select" id="workMode" name="work_mode">
                                <option value="Onsite">Onsite</option>
                                <option value="Remote">Remote</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <!-- Headcount -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="headcount" class="form-label">Headcount</label>
                            <input type="number" class="form-control" id="headcount" name="headcount" min="1" value="1">
                        </div>
                        <div class="col-md-4">
                            <label for="currentHeadcount" class="form-label">Current Headcount</label>
                            <input type="number" class="form-control" id="currentHeadcount" name="current_headcount" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label for="vacancyCount" class="form-label">Vacancies</label>
                            <input type="number" class="form-control" id="vacancyCount" name="vacancy_count" min="0" value="1" readonly>
                        </div>
                    </div>

                    <!-- Education Requirements -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Education Requirements</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="minEducationLevel" class="form-label">Minimum Education Level</label>
                            <select class="form-select" id="minEducationLevel" name="min_education_level">
                                <option value="">Not Specified</option>
                                <option value="None">None</option>
                                <option value="Pre Primary">Pre Primary</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Higher Secondary">Higher Secondary</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Associate Degree">Associate Degree</option>
                                <option value="Bachelor Degree">Bachelor Degree</option>
                                <option value="Master Degree">Master Degree</option>
                                <option value="Doctorate">Doctorate</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="minEducationSubjectId" class="form-label">Subject/Specialization</label>
                            <select class="form-select" id="minEducationSubjectId" name="min_education_subject_id">
                                <option value="">Any Subject</option>
                            </select>
                        </div>
                    </div>

                    <!-- Skills Requirements -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Skills Requirements</h6>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="requiredSkills" class="form-label">Required Skills</label>
                            <select class="form-select" id="requiredSkills" name="required_skills[]" multiple>
                                <!-- Skills options loaded dynamically -->
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple skills</small>
                        </div>
                    </div>

                    <!-- Experience -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Experience</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="minExperience" class="form-label">Minimum Experience (years)</label>
                            <input type="number" class="form-control" id="minExperience" name="min_experience_years" min="0">
                        </div>
                        <div class="col-md-6">
                            <label for="maxExperience" class="form-label">Maximum Experience (years)</label>
                            <input type="number" class="form-control" id="maxExperience" name="max_experience_years" min="0">
                        </div>
                    </div>

                    <!-- Compensation -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Compensation</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="salaryMin" class="form-label">Salary Min</label>
                            <input type="number" class="form-control" id="salaryMin" name="salary_range_min" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="salaryMax" class="form-label">Salary Max</label>
                            <input type="number" class="form-control" id="salaryMax" name="salary_range_max" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="salaryCurrency" class="form-label">Currency</label>
                            <select class="form-select" id="salaryCurrency" name="salary_currency">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="INR">INR</option>
                            </select>
                        </div>
                    </div>

                    <!-- Flags -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Flags</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isCritical" name="is_critical">
                                <label class="form-check-label" for="isCritical">
                                    Critical Position
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isLeadership" name="is_leadership_position">
                                <label class="form-check-label" for="isLeadership">
                                    Leadership Role
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isRemoteEligible" name="remote_work_eligible">
                                <label class="form-check-label" for="isRemoteEligible">
                                    Remote Eligible
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                                <label class="form-check-label" for="isActive">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePosition()">Save Position</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentPage = 1;
let itemsPerPage = 10;
let currentFilters = {};
let currentView = 'table';
let lastLoadedPositions = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadPositions();
    loadOrganizations();
    loadDesignations();
    loadDepartments();
    loadEducationSubjects();
    loadSkills();

    // Update vacancy count when headcount changes
    document.getElementById('headcount').addEventListener('input', updateVacancyCount);
    document.getElementById('currentHeadcount').addEventListener('input', updateVacancyCount);
});

function updateVacancyCount() {
    const headcount = parseInt(document.getElementById('headcount').value) || 0;
    const current = parseInt(document.getElementById('currentHeadcount').value) || 0;
    const vacancies = Math.max(0, headcount - current);
    document.getElementById('vacancyCount').value = vacancies;
}

// Load positions
async function loadPositions() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            ...currentFilters
        });

        const response = await fetch(`api/organization_positions.php?${params}`);
        const data = await response.json();

        if (data.success) {
            updateStatistics(data.statistics);
            lastLoadedPositions = data.positions;
            displayPositions(data.positions);
            updatePagination(data.pagination);
        } else {
            console.error('Failed to load positions:', data.message);
            showAlert('Failed to load positions: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading positions:', error);
        showAlert('Error loading positions. Please try again.', 'danger');
    }
}

// Update statistics
function updateStatistics(stats) {
    document.getElementById('totalPositions').textContent = stats.total || 0;
    document.getElementById('activePositions').textContent = stats.active || 0;
    document.getElementById('vacantPositions').textContent = stats.vacant || 0;
    document.getElementById('criticalPositions').textContent = stats.critical || 0;
}

// Display positions
function displayPositions(positions) {
    if (currentView === 'table') {
        displayTableView(positions);
    } else {
        displayCardView(positions);
    }
}

// Display table view
function displayTableView(positions) {
    const tbody = document.getElementById('positionsTableBody');

    if (positions.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No positions found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = positions.map(pos => `
        <tr>
            <td class="ps-3">
                <div>
                    <strong>${escapeHtml(pos.position_title)}</strong>
                    <br>
                    <small class="text-muted">${escapeHtml(pos.position_code || '')}</small>
                </div>
            </td>
            <td>${escapeHtml(pos.organization_name || 'N/A')}</td>
            <td>${escapeHtml(pos.department_name || 'N/A')}</td>
            <td>${escapeHtml(pos.designation_name || 'N/A')}</td>
            <td>
                <span class="badge bg-${getStatusColor(pos.position_status)}">${escapeHtml(pos.position_status || 'Draft')}</span>
                ${pos.is_vacant ? '<span class="badge bg-warning ms-1">Vacant</span>' : ''}
            </td>
            <td>${pos.vacancy_count || 0}</td>
            <td class="text-end pe-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editPosition(${pos.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deletePosition(${pos.id}, '${escapeHtml(pos.position_title)}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Display card view
function displayCardView(positions) {
    const container = document.getElementById('positionsCardContainer');

    if (positions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No positions found
            </div>
        `;
        return;
    }

    container.innerHTML = positions.map(pos => `
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">${escapeHtml(pos.position_title)}</h6>
                    <span class="badge bg-${getStatusColor(pos.position_status)}">${escapeHtml(pos.position_status || 'Draft')}</span>
                </div>
                <p class="card-text text-muted small mb-2">${escapeHtml(pos.position_code || '')}</p>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Organization:</small><br>
                        ${escapeHtml(pos.organization_name || 'N/A')}
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Department:</small><br>
                        ${escapeHtml(pos.department_name || 'N/A')}
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Designation:</small><br>
                        ${escapeHtml(pos.designation_name || 'N/A')}
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Vacancies:</small><br>
                        <strong>${pos.vacancy_count || 0}</strong>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="editPosition(${pos.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deletePosition(${pos.id}, '${escapeHtml(pos.position_title)}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// View switching
function switchToTableView() {
    currentView = 'table';
    document.getElementById('tableView').classList.remove('d-none');
    document.getElementById('cardView').classList.add('d-none');
    document.getElementById('tableViewBtn').classList.add('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    displayPositions(lastLoadedPositions || []);
}

function switchToCardView() {
    currentView = 'card';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.remove('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.add('active');
    displayPositions(lastLoadedPositions || []);
}

// Filter functions
function showFilters() {
    const filtersRow = document.getElementById('filtersRow');
    filtersRow.classList.toggle('d-none');
}

function applyFilters() {
    currentFilters = {
        organization_id: document.getElementById('organizationFilter').value,
        department_id: document.getElementById('departmentFilter').value,
        position_status: document.getElementById('positionStatusFilter').value,
        search: document.getElementById('searchFilter').value
    };

    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key];
        }
    });

    currentPage = 1;
    loadPositions();
}

function clearFilters() {
    document.getElementById('organizationFilter').value = '';
    document.getElementById('departmentFilter').value = '';
    document.getElementById('positionStatusFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    currentPage = 1;
    loadPositions();
}

// Modal functions
function showAddPositionModal() {
    document.getElementById('positionModalLabel').textContent = 'Add Position';
    document.getElementById('positionForm').reset();
    document.getElementById('positionId').value = '';
    new bootstrap.Modal(document.getElementById('positionModal')).show();
}

function editPosition(id) {
    fetch(`api/organization_positions.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pos = data.position;
                document.getElementById('positionModalLabel').textContent = 'Edit Position';
                document.getElementById('positionId').value = pos.id;
                document.getElementById('positionTitle').value = pos.position_title || '';
                document.getElementById('positionCode').value = pos.position_code || '';
                document.getElementById('organizationId').value = pos.organization_id || '';
                document.getElementById('designationId').value = pos.popular_organization_designation_id || '';
                document.getElementById('departmentId').value = pos.popular_organization_department_id || '';
                document.getElementById('teamId').value = pos.popular_organization_team_id || '';
                document.getElementById('description').value = pos.description || '';
                document.getElementById('positionType').value = pos.position_type || '';
                document.getElementById('employmentType').value = pos.employment_type || '';
                document.getElementById('workMode').value = pos.work_mode || '';
                document.getElementById('headcount').value = pos.headcount || 1;
                document.getElementById('currentHeadcount').value = pos.current_headcount || 0;
                document.getElementById('vacancyCount').value = pos.vacancy_count || 1;
                document.getElementById('minEducationLevel').value = pos.min_education_level || '';
                document.getElementById('minEducationSubjectId').value = pos.min_education_subject_id || '';
                document.getElementById('minExperience').value = pos.min_experience_years || '';
                document.getElementById('maxExperience').value = pos.max_experience_years || '';
                document.getElementById('salaryMin').value = pos.salary_range_min || '';
                document.getElementById('salaryMax').value = pos.salary_range_max || '';
                document.getElementById('salaryCurrency').value = pos.salary_currency || 'USD';
                document.getElementById('isCritical').checked = pos.is_critical == 1;
                document.getElementById('isLeadership').checked = pos.is_leadership_position == 1;
                document.getElementById('isRemoteEligible').checked = pos.remote_work_eligible == 1;
                document.getElementById('isActive').checked = pos.is_active == 1;

                // Set multi-select skills
                if (pos.required_skills) {
                    const skillIds = JSON.parse(pos.required_skills);
                    Array.from(document.getElementById('requiredSkills').options).forEach(option => {
                        option.selected = skillIds.includes(parseInt(option.value));
                    });
                }

                new bootstrap.Modal(document.getElementById('positionModal')).show();
            } else {
                showAlert('Failed to load position: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error loading position:', error);
            showAlert('Error loading position. Please try again.', 'danger');
        });
}

function savePosition() {
    const form = document.getElementById('positionForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Handle multi-select skills
    const selectedSkills = Array.from(document.getElementById('requiredSkills').selectedOptions).map(opt => opt.value);
    data.required_skills = JSON.stringify(selectedSkills);

    const isEdit = document.getElementById('positionId').value;
    const url = isEdit ?
        `api/organization_positions.php?action=update&id=${isEdit}` :
        'api/organization_positions.php?action=create';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('positionModal')).hide();
            loadPositions();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving position:', error);
        showAlert('Error saving position. Please try again.', 'danger');
    });
}

function deletePosition(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        fetch(`api/organization_positions.php?action=delete&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadPositions();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting position:', error);
            showAlert('Error deleting position. Please try again.', 'danger');
        });
    }
}

// Load dropdown data
async function loadOrganizations() {
    try {
        const response = await fetch('api/organizations.php?action=list&limit=1000');
        const data = await response.json();
        if (data.success) {
            populateSelect('organizationId', data.organizations, 'id', 'name');
            populateSelect('organizationFilter', data.organizations, 'id', 'name');
        }
    } catch (error) {
        console.error('Error loading organizations:', error);
    }
}

async function loadDesignations() {
    try {
        const response = await fetch('api/popular_organization_designations.php?action=list&limit=1000');
        const data = await response.json();
        if (data.success) {
            populateSelect('designationId', data.designations, 'id', 'name');
        }
    } catch (error) {
        console.error('Error loading designations:', error);
    }
}

async function loadDepartments() {
    try {
        const response = await fetch('api/popular_organization_departments.php?action=list&limit=1000');
        const data = await response.json();
        if (data.success) {
            populateSelect('departmentId', data.departments, 'id', 'name');
            populateSelect('departmentFilter', data.departments, 'id', 'name');
        }
    } catch (error) {
        console.error('Error loading departments:', error);
    }
}

async function loadEducationSubjects() {
    try {
        const response = await fetch('api/popular_education_subjects.php?action=list&limit=1000');
        const data = await response.json();
        if (data.success) {
            populateSelect('minEducationSubjectId', data.subjects, 'id', 'name');
        }
    } catch (error) {
        console.error('Error loading education subjects:', error);
    }
}

async function loadSkills() {
    try {
        const response = await fetch('api/popular_skills.php?action=list&limit=1000');
        const data = await response.json();
        if (data.success && data.skills) {
            const select = document.getElementById('requiredSkills');
            select.innerHTML = data.skills.map(skill =>
                `<option value="${skill.id}">${escapeHtml(skill.name)}</option>`
            ).join('');
        }
    } catch (error) {
        console.error('Error loading skills:', error);
    }
}

function populateSelect(selectId, items, valueField, textField) {
    const select = document.getElementById(selectId);
    const currentValue = select.value;
    const firstOption = select.options[0]?.cloneNode(true);

    select.innerHTML = '';
    if (firstOption) {
        select.appendChild(firstOption);
    }

    items.forEach(item => {
        const option = document.createElement('option');
        option.value = item[valueField];
        option.textContent = item[textField];
        select.appendChild(option);
    });

    if (currentValue) {
        select.value = currentValue;
    }
}

// Pagination
function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const info = document.getElementById('paginationInfo');

    info.textContent = `Showing ${((pagination.currentPage - 1) * pagination.itemsPerPage) + 1}-${Math.min(pagination.currentPage * pagination.itemsPerPage, pagination.totalItems)} of ${pagination.totalItems} positions`;

    if (pagination.totalPages <= 1) {
        container.innerHTML = '';
        return;
    }

    let paginationHTML = '';

    paginationHTML += `
        <li class="page-item ${pagination.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${pagination.currentPage - 1})" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;

    for (let i = 1; i <= pagination.totalPages; i++) {
        if (i === 1 || i === pagination.totalPages || (i >= pagination.currentPage - 2 && i <= pagination.currentPage + 2)) {
            paginationHTML += `
                <li class="page-item ${i === pagination.currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        } else if (i === pagination.currentPage - 3 || i === pagination.currentPage + 3) {
            paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    paginationHTML += `
        <li class="page-item ${pagination.currentPage === pagination.totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${pagination.currentPage + 1})" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;

    container.innerHTML = paginationHTML;
}

function changePage(page) {
    currentPage = page;
    loadPositions();
}

// Utility functions
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'active': return 'success';
        case 'posted': return 'primary';
        case 'approved': return 'info';
        case 'filled': return 'secondary';
        case 'closed': return 'dark';
        case 'cancelled': return 'danger';
        default: return 'warning';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(alert);

    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>

<?php include_once '../includes/footer.php'; ?>
