<?php
$pageTitle = "Organization Vacancies";
$pageDescription = "Manage organization job vacancies and openings";

include_once '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">📢 Organization Vacancies</h1>
                    <p class="text-muted mb-0">Manage job vacancies and recruitment opportunities</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="showFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddVacancyModal()">
                        <i class="fas fa-plus"></i> Add Vacancy
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" id="statisticsCards">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-briefcase text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total</h6>
                            <h4 class="card-title mb-0" id="totalVacancies">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Active</h6>
                            <h4 class="card-title mb-0" id="activeVacancies">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-globe text-info"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Published</h6>
                            <h4 class="card-title mb-0" id="publishedVacancies">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Urgent</h6>
                            <h4 class="card-title mb-0" id="urgentVacancies">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-star text-danger"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Featured</h6>
                            <h4 class="card-title mb-0" id="featuredVacancies">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-users text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Openings</h6>
                            <h4 class="card-title mb-0" id="totalOpenings">0</h4>
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
                            <label for="vacancyStatusFilter" class="form-label">Status</label>
                            <select class="form-select" id="vacancyStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Posted">Posted</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Filled">Filled</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="vacancyTypeFilter" class="form-label">Type</label>
                            <select class="form-select" id="vacancyTypeFilter">
                                <option value="">All Types</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Contract">Contract</option>
                                <option value="Intern">Intern</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search vacancies...">
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
                        <i class="fas fa-table"></i> <span class="d-none d-md-inline">Table</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="cardViewBtn" onclick="switchToCardView()">
                        <i class="fas fa-th-large"></i> <span class="d-none d-md-inline">Cards</span>
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
                                <th class="border-0 ps-3">Vacancy</th>
                                <th class="border-0 d-none d-lg-table-cell">Organization</th>
                                <th class="border-0">Position</th>
                                <th class="border-0">Openings</th>
                                <th class="border-0 d-none d-md-table-cell">Deadline</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="vacanciesTableBody">
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

    <!-- Card View -->
    <div id="cardView" class="d-none">
        <div id="vacanciesCardContainer" class="row">
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Vacancy Modal -->
<div class="modal fade" id="vacancyModal" tabindex="-1" aria-labelledby="vacancyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vacancyModalLabel">Add Vacancy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="vacancyForm">
                    <input type="hidden" id="vacancyId" name="id">

                    <!-- Basic Information -->
                    <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="vacancyTitle" class="form-label">Vacancy Title *</label>
                            <input type="text" class="form-control" id="vacancyTitle" name="vacancy_title" required>
                        </div>
                        <div class="col-md-4">
                            <label for="vacancyCode" class="form-label">Vacancy Code</label>
                            <input type="text" class="form-control" id="vacancyCode" name="vacancy_code">
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
                            <label for="positionId" class="form-label">Position *</label>
                            <select class="form-select" id="positionId" name="organization_position_id" required>
                                <option value="">Select Position</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numberOfOpenings" class="form-label">Number of Openings *</label>
                            <input type="number" class="form-control" id="numberOfOpenings" name="number_of_openings" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="priorityLevel" class="form-label">Priority Level</label>
                            <select class="form-select" id="priorityLevel" name="priority_level">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Contact Information -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">📞 Contact Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="contactPersonName" class="form-label">Contact Person Name</label>
                            <input type="text" class="form-control" id="contactPersonName" name="contact_person_name">
                        </div>
                        <div class="col-md-6">
                            <label for="contactPersonTitle" class="form-label">Contact Person Title</label>
                            <input type="text" class="form-control" id="contactPersonTitle" name="contact_person_title">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="contactPersonEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="contactPersonEmail" name="contact_person_email">
                        </div>
                        <div class="col-md-4">
                            <label for="contactPersonPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="contactPersonPhone" name="contact_person_phone">
                        </div>
                        <div class="col-md-4">
                            <label for="contactPersonMobile" class="form-label">Mobile</label>
                            <input type="tel" class="form-control" id="contactPersonMobile" name="contact_person_mobile">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="applicationEmail" class="form-label">Application Email</label>
                            <input type="email" class="form-control" id="applicationEmail" name="application_email">
                        </div>
                        <div class="col-md-6">
                            <label for="applicationMethod" class="form-label">Application Method</label>
                            <select class="form-select" id="applicationMethod" name="application_method">
                                <option value="Email">Email</option>
                                <option value="Online Form">Online Form</option>
                                <option value="Career Portal">Career Portal</option>
                                <option value="In Person">In Person</option>
                            </select>
                        </div>
                    </div>

                    <!-- Workstations -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">🪑 Available Workstations</h6>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="availableWorkstations" class="form-label">Select Workstations</label>
                            <select class="form-select" id="availableWorkstations" name="available_workstations[]" multiple size="5">
                                <!-- Workstations loaded dynamically -->
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple workstations</small>
                        </div>
                    </div>

                    <!-- Dates -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Important Dates</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="applicationDeadline" class="form-label">Application Deadline</label>
                            <input type="date" class="form-control" id="applicationDeadline" name="application_deadline">
                        </div>
                        <div class="col-md-4">
                            <label for="targetStartDate" class="form-label">Target Start Date</label>
                            <input type="date" class="form-control" id="targetStartDate" name="target_start_date">
                        </div>
                        <div class="col-md-4">
                            <label for="expectedClosureDate" class="form-label">Expected Closure Date</label>
                            <input type="date" class="form-control" id="expectedClosureDate" name="expected_closure_date">
                        </div>
                    </div>

                    <!-- Employment Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Employment Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="vacancyType" class="form-label">Vacancy Type</label>
                            <select class="form-select" id="vacancyType" name="vacancy_type">
                                <option value="Permanent">Permanent</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Contract">Contract</option>
                                <option value="Intern">Intern</option>
                                <option value="Consultant">Consultant</option>
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

                    <!-- Experience & Education -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Requirements</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="minExperience" class="form-label">Minimum Experience (years)</label>
                            <input type="number" class="form-control" id="minExperience" name="min_experience_years" min="0">
                        </div>
                        <div class="col-md-6">
                            <label for="minEducationLevel" class="form-label">Minimum Education Level</label>
                            <select class="form-select" id="minEducationLevel" name="min_education_level">
                                <option value="">Not Specified</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Higher Secondary">Higher Secondary</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor Degree">Bachelor Degree</option>
                                <option value="Master Degree">Master Degree</option>
                                <option value="Doctorate">Doctorate</option>
                            </select>
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
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Settings</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isUrgent" name="is_urgent">
                                <label class="form-check-label" for="isUrgent">
                                    Urgent
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured">
                                <label class="form-check-label" for="isFeatured">
                                    Featured
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isPublished" name="is_published">
                                <label class="form-check-label" for="isPublished">
                                    Published
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
                <button type="button" class="btn btn-primary" onclick="saveVacancy()">Save Vacancy</button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Vacancy Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewDetailsContent">
                <!-- Content loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentFilters = {};
let currentView = 'table';
let lastLoadedVacancies = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadVacancies();
    loadOrganizations();
    loadPositions();
    loadWorkstations();
});

// Load vacancies
async function loadVacancies() {
    try {
        const response = await fetch('api/organization_vacancies.php');
        const data = await response.json();

        if (data.success) {
            lastLoadedVacancies = data.data;
            updateStatistics(lastLoadedVacancies);
            displayVacancies(lastLoadedVacancies);
        } else {
            console.error('Failed to load vacancies:', data.message);
            showAlert('Failed to load vacancies: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading vacancies:', error);
        showAlert('Error loading vacancies. Please try again.', 'danger');
    }
}

// Update statistics
function updateStatistics(vacancies) {
    const stats = {
        total: vacancies.length,
        active: vacancies.filter(v => v.vacancy_status === 'Active' || v.vacancy_status === 'Posted').length,
        published: vacancies.filter(v => v.is_published == 1).length,
        urgent: vacancies.filter(v => v.is_urgent == 1).length,
        featured: vacancies.filter(v => v.is_featured == 1).length,
        openings: vacancies.reduce((sum, v) => sum + (parseInt(v.remaining_openings) || 0), 0)
    };

    document.getElementById('totalVacancies').textContent = stats.total;
    document.getElementById('activeVacancies').textContent = stats.active;
    document.getElementById('publishedVacancies').textContent = stats.published;
    document.getElementById('urgentVacancies').textContent = stats.urgent;
    document.getElementById('featuredVacancies').textContent = stats.featured;
    document.getElementById('totalOpenings').textContent = stats.openings;
}

// Display vacancies
function displayVacancies(vacancies) {
    if (currentView === 'table') {
        displayTableView(vacancies);
    } else {
        displayCardView(vacancies);
    }
}

// Display table view
function displayTableView(vacancies) {
    const tbody = document.getElementById('vacanciesTableBody');

    if (vacancies.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No vacancies found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = vacancies.map(vac => `
        <tr>
            <td class="ps-3">
                <div>
                    <strong>${escapeHtml(vac.vacancy_title)}</strong>
                    ${vac.is_featured ? '<span class="badge bg-danger ms-1">Featured</span>' : ''}
                    ${vac.is_urgent ? '<span class="badge bg-warning ms-1">Urgent</span>' : ''}
                    <br>
                    <small class="text-muted">${escapeHtml(vac.vacancy_code || '')}</small>
                </div>
            </td>
            <td class="d-none d-lg-table-cell">${escapeHtml(vac.organization_name || 'N/A')}</td>
            <td><small>${escapeHtml(vac.position_title || 'N/A')}</small></td>
            <td>
                <span class="badge bg-secondary">${vac.remaining_openings || 0}/${vac.number_of_openings || 1}</span>
            </td>
            <td class="d-none d-md-table-cell">
                <small>${vac.application_deadline ? formatDate(vac.application_deadline) : 'N/A'}</small>
                ${vac.days_until_deadline !== null && vac.days_until_deadline <= 7 ? `<br><small class="text-danger">${vac.days_until_deadline} days left</small>` : ''}
            </td>
            <td>
                <span class="badge bg-${getStatusColor(vac.vacancy_status)}">${escapeHtml(vac.vacancy_status || 'Draft')}</span>
                ${vac.is_published ? '<br><small class="text-success">Published</small>' : ''}
            </td>
            <td class="text-end pe-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="viewVacancyDetails(${vac.id})" title="View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="editVacancy(${vac.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteVacancy(${vac.id}, '${escapeHtml(vac.vacancy_title)}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Display card view
function displayCardView(vacancies) {
    const container = document.getElementById('vacanciesCardContainer');

    if (vacancies.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No vacancies found
            </div>
        `;
        return;
    }

    container.innerHTML = vacancies.map(vac => `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 ${vac.is_featured ? 'border-danger' : ''}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">${escapeHtml(vac.vacancy_title)}</h6>
                        <div>
                            ${vac.is_featured ? '<span class="badge bg-danger">Featured</span>' : ''}
                            ${vac.is_urgent ? '<span class="badge bg-warning">Urgent</span>' : ''}
                        </div>
                    </div>
                    <p class="card-text text-muted small mb-2">${escapeHtml(vac.vacancy_code || '')}</p>
                    <div class="mb-3">
                        <small class="text-muted">Position:</small><br>
                        <strong>${escapeHtml(vac.position_title || 'N/A')}</strong>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted">Organization:</small><br>
                            <small>${escapeHtml(vac.organization_name || 'N/A')}</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Openings:</small><br>
                            <strong>${vac.remaining_openings || 0}/${vac.number_of_openings || 1}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Deadline:</small><br>
                            <small>${vac.application_deadline ? formatDate(vac.application_deadline) : 'N/A'}</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Status:</small><br>
                            <span class="badge bg-${getStatusColor(vac.vacancy_status)}">${escapeHtml(vac.vacancy_status || 'Draft')}</span>
                        </div>
                    </div>
                    ${vac.contact_person_email ? `
                        <div class="mb-3">
                            <small class="text-muted">📧 Contact:</small><br>
                            <small><a href="mailto:${escapeHtml(vac.contact_person_email)}">${escapeHtml(vac.contact_person_email)}</a></small>
                        </div>
                    ` : ''}
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info flex-fill" onclick="viewVacancyDetails(${vac.id})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="editVacancy(${vac.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteVacancy(${vac.id}, '${escapeHtml(vac.vacancy_title)}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// View vacancy details
async function viewVacancyDetails(id) {
    try {
        const response = await fetch(`api/organization_vacancies.php?id=${id}`);
        const data = await response.json();

        if (data.success) {
            const vac = data.data;
            const content = document.getElementById('viewDetailsContent');

            content.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-3">
                        <h4>${escapeHtml(vac.vacancy_title)}</h4>
                        <p class="text-muted">${escapeHtml(vac.vacancy_code || '')}</p>
                        <div>
                            <span class="badge bg-${getStatusColor(vac.vacancy_status)}">${escapeHtml(vac.vacancy_status)}</span>
                            ${vac.is_published ? '<span class="badge bg-success">Published</span>' : ''}
                            ${vac.is_featured ? '<span class="badge bg-danger">Featured</span>' : ''}
                            ${vac.is_urgent ? '<span class="badge bg-warning">Urgent</span>' : ''}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Organization:</strong><br>
                        ${escapeHtml(vac.organization_name || 'N/A')}
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Position:</strong><br>
                        ${escapeHtml(vac.position_title || 'N/A')}
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Openings:</strong><br>
                        ${vac.remaining_openings || 0} remaining of ${vac.number_of_openings || 1}
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Application Deadline:</strong><br>
                        ${vac.application_deadline ? formatDate(vac.application_deadline) : 'Not specified'}
                        ${vac.days_until_deadline !== null ? `<br><small class="text-muted">${vac.days_until_deadline} days remaining</small>` : ''}
                    </div>

                    ${vac.description ? `
                        <div class="col-12 mb-3">
                            <strong>Description:</strong><br>
                            <p>${escapeHtml(vac.description)}</p>
                        </div>
                    ` : ''}

                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">📞 Contact Information</h6>
                    </div>

                    ${vac.contact_person_name ? `
                        <div class="col-md-6 mb-3">
                            <strong>Contact Person:</strong><br>
                            ${escapeHtml(vac.contact_person_name)}
                            ${vac.contact_person_title ? `<br><small class="text-muted">${escapeHtml(vac.contact_person_title)}</small>` : ''}
                        </div>
                    ` : ''}

                    ${vac.contact_person_email ? `
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong><br>
                            <a href="mailto:${escapeHtml(vac.contact_person_email)}">${escapeHtml(vac.contact_person_email)}</a>
                        </div>
                    ` : ''}

                    ${vac.contact_person_phone ? `
                        <div class="col-md-6 mb-3">
                            <strong>Phone:</strong><br>
                            <a href="tel:${escapeHtml(vac.contact_person_phone)}">${escapeHtml(vac.contact_person_phone)}</a>
                        </div>
                    ` : ''}

                    ${vac.contact_person_mobile ? `
                        <div class="col-md-6 mb-3">
                            <strong>Mobile:</strong><br>
                            <a href="tel:${escapeHtml(vac.contact_person_mobile)}">${escapeHtml(vac.contact_person_mobile)}</a>
                        </div>
                    ` : ''}

                    ${vac.application_email ? `
                        <div class="col-12 mb-3">
                            <strong>Apply via:</strong><br>
                            ${escapeHtml(vac.application_method || 'Email')} - <a href="mailto:${escapeHtml(vac.application_email)}">${escapeHtml(vac.application_email)}</a>
                        </div>
                    ` : ''}

                    <div class="col-md-6 mb-3">
                        <strong>Salary Range:</strong><br>
                        ${vac.salary_range_formatted || 'Not specified'}
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Experience:</strong><br>
                        ${vac.experience_range_formatted || 'Not specified'}
                    </div>
                </div>
            `;

            new bootstrap.Modal(document.getElementById('viewDetailsModal')).show();
        } else {
            showAlert('Failed to load vacancy details: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading vacancy details:', error);
        showAlert('Error loading vacancy details. Please try again.', 'danger');
    }
}

// View switching
function switchToTableView() {
    currentView = 'table';
    document.getElementById('tableView').classList.remove('d-none');
    document.getElementById('cardView').classList.add('d-none');
    document.getElementById('tableViewBtn').classList.add('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    displayVacancies(lastLoadedVacancies || []);
}

function switchToCardView() {
    currentView = 'card';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.remove('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.add('active');
    displayVacancies(lastLoadedVacancies || []);
}

// Filter functions
function showFilters() {
    const filtersRow = document.getElementById('filtersRow');
    filtersRow.classList.toggle('d-none');
}

function applyFilters() {
    // Filter logic implementation
    currentFilters = {
        organization_id: document.getElementById('organizationFilter').value,
        vacancy_status: document.getElementById('vacancyStatusFilter').value,
        vacancy_type: document.getElementById('vacancyTypeFilter').value,
        search: document.getElementById('searchFilter').value
    };

    let filtered = lastLoadedVacancies;

    if (currentFilters.organization_id) {
        filtered = filtered.filter(v => v.organization_id == currentFilters.organization_id);
    }

    if (currentFilters.vacancy_status) {
        filtered = filtered.filter(v => v.vacancy_status === currentFilters.vacancy_status);
    }

    if (currentFilters.vacancy_type) {
        filtered = filtered.filter(v => v.vacancy_type === currentFilters.vacancy_type);
    }

    if (currentFilters.search) {
        const query = currentFilters.search.toLowerCase();
        filtered = filtered.filter(v =>
            (v.vacancy_title && v.vacancy_title.toLowerCase().includes(query)) ||
            (v.vacancy_code && v.vacancy_code.toLowerCase().includes(query))
        );
    }

    displayVacancies(filtered);
}

function clearFilters() {
    document.getElementById('organizationFilter').value = '';
    document.getElementById('vacancyStatusFilter').value = '';
    document.getElementById('vacancyTypeFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    displayVacancies(lastLoadedVacancies);
}

// Modal functions
function showAddVacancyModal() {
    document.getElementById('vacancyModalLabel').textContent = 'Add Vacancy';
    document.getElementById('vacancyForm').reset();
    document.getElementById('vacancyId').value = '';
    new bootstrap.Modal(document.getElementById('vacancyModal')).show();
}

async function editVacancy(id) {
    try {
        const response = await fetch(`api/organization_vacancies.php?id=${id}`);
        const data = await response.json();

        if (data.success) {
            const vac = data.data;
            document.getElementById('vacancyModalLabel').textContent = 'Edit Vacancy';
            document.getElementById('vacancyId').value = vac.id;
            document.getElementById('vacancyTitle').value = vac.vacancy_title || '';
            document.getElementById('vacancyCode').value = vac.vacancy_code || '';
            document.getElementById('organizationId').value = vac.organization_id || '';
            document.getElementById('positionId').value = vac.organization_position_id || '';
            document.getElementById('numberOfOpenings').value = vac.number_of_openings || 1;
            document.getElementById('priorityLevel').value = vac.priority_level || 'Medium';
            document.getElementById('description').value = vac.description || '';
            document.getElementById('contactPersonName').value = vac.contact_person_name || '';
            document.getElementById('contactPersonTitle').value = vac.contact_person_title || '';
            document.getElementById('contactPersonEmail').value = vac.contact_person_email || '';
            document.getElementById('contactPersonPhone').value = vac.contact_person_phone || '';
            document.getElementById('contactPersonMobile').value = vac.contact_person_mobile || '';
            document.getElementById('applicationEmail').value = vac.application_email || '';
            document.getElementById('applicationMethod').value = vac.application_method || 'Email';
            document.getElementById('applicationDeadline').value = vac.application_deadline || '';
            document.getElementById('targetStartDate').value = vac.target_start_date || '';
            document.getElementById('expectedClosureDate').value = vac.expected_closure_date || '';
            document.getElementById('vacancyType').value = vac.vacancy_type || 'Permanent';
            document.getElementById('employmentType').value = vac.employment_type || 'Full-time';
            document.getElementById('workMode').value = vac.work_mode || 'Onsite';
            document.getElementById('minExperience').value = vac.min_experience_years || '';
            document.getElementById('minEducationLevel').value = vac.min_education_level || '';
            document.getElementById('salaryMin').value = vac.salary_range_min || '';
            document.getElementById('salaryMax').value = vac.salary_range_max || '';
            document.getElementById('salaryCurrency').value = vac.salary_currency || 'USD';
            document.getElementById('isUrgent').checked = vac.is_urgent == 1;
            document.getElementById('isFeatured').checked = vac.is_featured == 1;
            document.getElementById('isPublished').checked = vac.is_published == 1;
            document.getElementById('isActive').checked = vac.is_active == 1;

            // Set workstations
            if (vac.workstation_ids && vac.workstation_ids.length > 0) {
                Array.from(document.getElementById('availableWorkstations').options).forEach(option => {
                    option.selected = vac.workstation_ids.includes(parseInt(option.value));
                });
            }

            new bootstrap.Modal(document.getElementById('vacancyModal')).show();
        } else {
            showAlert('Failed to load vacancy: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading vacancy:', error);
        showAlert('Error loading vacancy. Please try again.', 'danger');
    }
}

async function saveVacancy() {
    const form = document.getElementById('vacancyForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Handle multi-select workstations
    const selectedWorkstations = Array.from(document.getElementById('availableWorkstations').selectedOptions).map(opt => opt.value);
    data.available_workstations = JSON.stringify(selectedWorkstations);

    // Convert checkboxes
    data.is_urgent = document.getElementById('isUrgent').checked ? 1 : 0;
    data.is_featured = document.getElementById('isFeatured').checked ? 1 : 0;
    data.is_published = document.getElementById('isPublished').checked ? 1 : 0;
    data.is_active = document.getElementById('isActive').checked ? 1 : 0;

    try {
        const response = await fetch('api/organization_vacancies.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showAlert(result.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('vacancyModal')).hide();
            loadVacancies();
        } else {
            showAlert(result.message, 'danger');
        }
    } catch (error) {
        console.error('Error saving vacancy:', error);
        showAlert('Error saving vacancy. Please try again.', 'danger');
    }
}

async function deleteVacancy(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        try {
            const response = await fetch(`api/organization_vacancies.php?id=${id}`, {
                method: 'DELETE'
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                loadVacancies();
            } else {
                showAlert(data.message, 'danger');
            }
        } catch (error) {
            console.error('Error deleting vacancy:', error);
            showAlert('Error deleting vacancy. Please try again.', 'danger');
        }
    }
}

// Load dropdown data
async function loadOrganizations() {
    try {
        const response = await fetch('api/organization_vacancies.php?action=organizations');
        const data = await response.json();
        if (data.success) {
            populateSelect('organizationId', data.data, 'id', 'name');
            populateSelect('organizationFilter', data.data, 'id', 'name');
        }
    } catch (error) {
        console.error('Error loading organizations:', error);
    }
}

async function loadPositions() {
    try {
        const response = await fetch('api/organization_vacancies.php?action=positions');
        const data = await response.json();
        if (data.success) {
            const select = document.getElementById('positionId');
            select.innerHTML = '<option value="">Select Position</option>';
            data.data.forEach(pos => {
                const option = document.createElement('option');
                option.value = pos.id;
                option.textContent = `${pos.position_title} (${pos.position_code || 'N/A'})`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading positions:', error);
    }
}

async function loadWorkstations() {
    try {
        const response = await fetch('api/organization_vacancies.php?action=workstations');
        const data = await response.json();
        if (data.success) {
            const select = document.getElementById('availableWorkstations');
            select.innerHTML = '';
            data.data.forEach(ws => {
                const option = document.createElement('option');
                option.value = ws.id;
                option.textContent = `${ws.seat_code} - ${ws.workstation_type} (Floor ${ws.floor_number})`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading workstations:', error);
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

// Utility functions
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'active':
        case 'posted': return 'success';
        case 'approved': return 'info';
        case 'on hold': return 'warning';
        case 'filled': return 'secondary';
        case 'closed':
        case 'cancelled': return 'dark';
        case 'expired': return 'danger';
        default: return 'warning';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString();
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
