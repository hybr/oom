<?php
$pageTitle = "Person Education";
$pageDescription = "Manage education history and qualifications";

include_once '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">🎓 Person Education</h1>
                    <p class="text-muted mb-0">Manage educational qualifications and history</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="showFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddEducationModal()">
                        <i class="fas fa-plus"></i> Add Education
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
                            <i class="fas fa-graduation-cap text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Records</h6>
                            <h4 class="card-title mb-0" id="totalRecords">0</h4>
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
                            <h6 class="card-subtitle mb-1 text-muted">Completed</h6>
                            <h4 class="card-title mb-0" id="completedCount">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-certificate text-info"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Verified</h6>
                            <h4 class="card-title mb-0" id="verifiedCount">0</h4>
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
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">With Distinction</h6>
                            <h4 class="card-title mb-0" id="distinctionCount">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="row mb-4 d-none" id="filtersRow">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="levelFilter" class="form-label">Education Level</label>
                            <select class="form-select" id="levelFilter">
                                <option value="">All Levels</option>
                                <option value="Pre Primary">Pre Primary</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Higher Secondary">Higher Secondary</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor">Bachelor</option>
                                <option value="Master">Master</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Certificate">Certificate</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="completed">Completed</option>
                                <option value="in_progress">In Progress</option>
                                <option value="dropout">Dropout</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="verifiedFilter" class="form-label">Verification</label>
                            <select class="form-select" id="verifiedFilter">
                                <option value="">All</option>
                                <option value="1">Verified</option>
                                <option value="0">Not Verified</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search...">
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

    <!-- Table View -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-3">Person</th>
                            <th class="border-0">Level</th>
                            <th class="border-0">Institution</th>
                            <th class="border-0">Year</th>
                            <th class="border-0">Score</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="educationTableBody">
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

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Education pagination">
                <ul class="pagination justify-content-center" id="paginationContainer">
                    <!-- Pagination will be generated here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Education Modal -->
<div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="educationModalLabel">Add Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="educationForm">
                    <input type="hidden" id="educationId" name="id">

                    <!-- Basic Information -->
                    <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="personId" class="form-label">Person *</label>
                            <select class="form-select" id="personId" name="person_id" required>
                                <option value="">Select Person</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="level" class="form-label">Education Level *</label>
                            <select class="form-select" id="level" name="level" required>
                                <option value="">Select Level</option>
                                <option value="Pre Primary">Pre Primary</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Higher Secondary">Higher Secondary</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor">Bachelor</option>
                                <option value="Master">Master</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Post Doctor">Post Doctor</option>
                                <option value="Certificate">Certificate</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="institutionName" class="form-label">Institution Name *</label>
                            <input type="text" class="form-control" id="institutionName" name="institution_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="degreeName" class="form-label">Degree/Qualification Name</label>
                            <input type="text" class="form-control" id="degreeName" name="degree_name" placeholder="e.g., B.Tech, MBA, MBBS">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Specialization/Major</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" placeholder="e.g., Computer Science, Finance">
                        </div>
                        <div class="col-md-6">
                            <label for="boardUniversity" class="form-label">Board/University</label>
                            <input type="text" class="form-control" id="boardUniversity" name="board_university">
                        </div>
                    </div>

                    <!-- Major Subjects -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Major Subjects (Max 6)</h6>
                    <div class="mb-3">
                        <label for="majorSubjects" class="form-label">Select Major Subjects</label>
                        <select class="form-select" id="majorSubjects" name="major_subjects" multiple size="8">
                            <!-- Will be populated dynamically -->
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple subjects (max 6)</small>
                    </div>

                    <!-- Academic Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Academic Performance</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="scoreType" class="form-label">Score Type</label>
                            <select class="form-select" id="scoreType" name="score_type">
                                <option value="">Select Type</option>
                                <option value="Grade">Grade</option>
                                <option value="%">Percentage</option>
                                <option value="CGPA">CGPA</option>
                                <option value="GPA">GPA</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="scoreValue" class="form-label">Score Value</label>
                            <input type="text" class="form-control" id="scoreValue" name="score_value" placeholder="e.g., A+, 85, 3.7">
                        </div>
                        <div class="col-md-4">
                            <label for="attendancePercentage" class="form-label">Attendance %</label>
                            <input type="number" class="form-control" id="attendancePercentage" name="attendance_percentage" min="0" max="100" step="0.01">
                        </div>
                    </div>

                    <!-- Timeline -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Timeline</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="yearOfEnrollment" class="form-label">Year of Enrollment</label>
                            <input type="number" class="form-control" id="yearOfEnrollment" name="year_of_enrollment" min="1900" max="2100">
                        </div>
                        <div class="col-md-4">
                            <label for="yearOfCompletion" class="form-label">Year of Completion</label>
                            <input type="number" class="form-control" id="yearOfCompletion" name="year_of_completion" min="1900" max="2100">
                        </div>
                        <div class="col-md-4">
                            <label for="duration" class="form-label">Duration (Years)</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="0" max="20">
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Additional Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="rollNumber" class="form-label">Roll/Registration Number</label>
                            <input type="text" class="form-control" id="rollNumber" name="roll_number">
                        </div>
                        <div class="col-md-6">
                            <label for="certificateNumber" class="form-label">Certificate Number</label>
                            <input type="text" class="form-control" id="certificateNumber" name="certificate_number">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="finalProjectTitle" class="form-label">Final Project Title</label>
                            <input type="text" class="form-control" id="finalProjectTitle" name="final_project_title">
                        </div>
                        <div class="col-md-6">
                            <label for="thesisTitle" class="form-label">Thesis Title (if applicable)</label>
                            <input type="text" class="form-control" id="thesisTitle" name="thesis_title">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="supervisorName" class="form-label">Supervisor/Advisor Name</label>
                            <input type="text" class="form-control" id="supervisorName" name="supervisor_name">
                        </div>
                        <div class="col-md-6">
                            <label for="rank" class="form-label">Class/Division Rank</label>
                            <input type="number" class="form-control" id="rank" name="rank" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks/Notes</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
                    </div>

                    <!-- Flags -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Status & Flags</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="distinction" name="distinction" value="1">
                                <label class="form-check-label" for="distinction">Distinction</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="scholarship" name="scholarship" value="1">
                                <label class="form-check-label" for="scholarship">Scholarship</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isHighestQualification" name="is_highest_qualification" value="1">
                                <label class="form-check-label" for="isHighestQualification">Highest Qualification</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isVerified" name="is_verified" value="1">
                                <label class="form-check-label" for="isVerified">Verified</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="statusSelect" class="form-label">Status</label>
                            <select class="form-select" id="statusSelect" name="status">
                                <option value="completed">Completed</option>
                                <option value="in_progress">In Progress</option>
                                <option value="dropout">Dropout</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEducation()">Save Education</button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Education Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromDetailsBtn">Edit</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentPage = 1;
let itemsPerPage = 10;
let currentFilters = {};
let lastLoadedEducations = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadEducations();
    loadPersons();
    loadSubjects();
});

// Load educations
async function loadEducations() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            ...currentFilters
        });

        const response = await fetch(`api/person_education.php?${params}`);
        const data = await response.json();

        if (data.success) {
            updateStatistics(data.statistics);
            lastLoadedEducations = data.educations;
            displayEducations(data.educations);
            updatePagination(data.pagination);
        } else {
            console.error('Failed to load educations:', data.message);
            showAlert('Failed to load educations: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading educations:', error);
        showAlert('Error loading educations. Please try again.', 'danger');
    }
}

// Load persons for dropdown
async function loadPersons() {
    try {
        const response = await fetch('api/persons.php?action=list&limit=1000');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('personId');
            select.innerHTML = '<option value="">Select Person</option>';

            data.persons.forEach(person => {
                const option = document.createElement('option');
                option.value = person.id;
                option.textContent = person.first_name + ' ' + person.last_name;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading persons:', error);
    }
}

// Load subjects for dropdown
async function loadSubjects() {
    try {
        const response = await fetch('api/popular_education_subjects.php?action=list&limit=1000');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('majorSubjects');
            select.innerHTML = '';

            data.subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = `${subject.name} (${subject.code})`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading subjects:', error);
    }
}

// Update statistics
function updateStatistics(stats) {
    document.getElementById('totalRecords').textContent = stats.total || 0;
    document.getElementById('completedCount').textContent = stats.completed || 0;
    document.getElementById('verifiedCount').textContent = stats.verified || 0;
    document.getElementById('distinctionCount').textContent = stats.distinction || 0;
}

// Display educations
function displayEducations(educations) {
    const tbody = document.getElementById('educationTableBody');

    if (educations.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No education records found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = educations.map(edu => `
        <tr onclick="viewDetails(${edu.id})" style="cursor: pointer;">
            <td class="ps-3">
                <strong>${escapeHtml(edu.person_name)}</strong>
            </td>
            <td>
                <span class="badge bg-primary">${escapeHtml(edu.level)}</span>
                ${edu.is_highest_qualification == 1 ? '<span class="badge bg-success ms-1">Highest</span>' : ''}
            </td>
            <td>${escapeHtml(edu.institution_display_name)}</td>
            <td>${edu.year_of_completion || '-'}</td>
            <td>${edu.formatted_score}</td>
            <td>
                <span class="badge bg-${getStatusColor(edu.status)}">${escapeHtml(edu.status)}</span>
                ${edu.is_verified == 1 ? '<span class="badge bg-info ms-1"><i class="fas fa-check"></i></span>' : ''}
                ${edu.distinction == 1 ? '<span class="badge bg-warning ms-1"><i class="fas fa-star"></i></span>' : ''}
            </td>
            <td class="text-end pe-3" onclick="event.stopPropagation();">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="viewDetails(${edu.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="editEducation(${edu.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteEducation(${edu.id}, '${escapeHtml(edu.level)}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// View details
function viewDetails(id) {
    fetch(`api/person_education.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const edu = data.education;
                const detailsHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Person</h6>
                            <p class="mb-0"><strong>${escapeHtml(edu.person_name)}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Education Level</h6>
                            <p class="mb-0"><span class="badge bg-primary">${escapeHtml(edu.level)}</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Institution</h6>
                            <p class="mb-0">${escapeHtml(edu.institution_display_name)}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Degree/Qualification</h6>
                            <p class="mb-0">${edu.degree_name ? escapeHtml(edu.degree_name) : '-'}</p>
                        </div>
                        ${edu.specialization ? `
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Specialization</h6>
                            <p class="mb-0">${escapeHtml(edu.specialization)}</p>
                        </div>
                        ` : ''}
                        ${edu.board_university ? `
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Board/University</h6>
                            <p class="mb-0">${escapeHtml(edu.board_university)}</p>
                        </div>
                        ` : ''}
                        ${edu.major_subject_objects && edu.major_subject_objects.length > 0 ? `
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Major Subjects (${edu.subject_count})</h6>
                            <div>
                                ${edu.major_subject_objects.map(s => `<span class="badge bg-secondary me-1">${escapeHtml(s.name)}</span>`).join('')}
                            </div>
                        </div>
                        ` : ''}
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Score</h6>
                            <p class="mb-0">${edu.formatted_score}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Year of Completion</h6>
                            <p class="mb-0">${edu.year_of_completion || '-'}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Duration</h6>
                            <p class="mb-0">${edu.duration_text || '-'}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Achievements & Status</h6>
                            <div>
                                <span class="badge bg-${getStatusColor(edu.status)} me-1">${escapeHtml(edu.status)}</span>
                                ${edu.is_verified == 1 ? '<span class="badge bg-info me-1">Verified</span>' : ''}
                                ${edu.distinction == 1 ? '<span class="badge bg-warning me-1">Distinction</span>' : ''}
                                ${edu.scholarship == 1 ? '<span class="badge bg-success me-1">Scholarship</span>' : ''}
                                ${edu.is_highest_qualification == 1 ? '<span class="badge bg-primary me-1">Highest Qualification</span>' : ''}
                            </div>
                        </div>
                        ${edu.remarks ? `
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Remarks</h6>
                            <p class="mb-0">${escapeHtml(edu.remarks)}</p>
                        </div>
                        ` : ''}
                    </div>
                `;

                document.getElementById('detailsContent').innerHTML = detailsHTML;
                document.getElementById('editFromDetailsBtn').onclick = function() {
                    bootstrap.Modal.getInstance(document.getElementById('viewDetailsModal')).hide();
                    editEducation(id);
                };

                new bootstrap.Modal(document.getElementById('viewDetailsModal')).show();
            } else {
                showAlert('Failed to load details: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error loading details:', error);
            showAlert('Error loading details. Please try again.', 'danger');
        });
}

// Filter functions
function showFilters() {
    document.getElementById('filtersRow').classList.toggle('d-none');
}

function applyFilters() {
    currentFilters = {
        level: document.getElementById('levelFilter').value,
        status: document.getElementById('statusFilter').value,
        is_verified: document.getElementById('verifiedFilter').value,
        search: document.getElementById('searchFilter').value
    };

    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key];
        }
    });

    currentPage = 1;
    loadEducations();
}

function clearFilters() {
    document.getElementById('levelFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('verifiedFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    currentPage = 1;
    loadEducations();
}

// Modal functions
function showAddEducationModal() {
    document.getElementById('educationModalLabel').textContent = 'Add Education';
    document.getElementById('educationForm').reset();
    document.getElementById('educationId').value = '';
    document.getElementById('isActive').checked = true;
    document.getElementById('statusSelect').value = 'completed';
    new bootstrap.Modal(document.getElementById('educationModal')).show();
}

function editEducation(id) {
    fetch(`api/person_education.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const edu = data.education;
                document.getElementById('educationModalLabel').textContent = 'Edit Education';
                document.getElementById('educationId').value = edu.id;
                document.getElementById('personId').value = edu.person_id || '';
                document.getElementById('level').value = edu.level || '';
                document.getElementById('institutionName').value = edu.institution_name || '';
                document.getElementById('degreeName').value = edu.degree_name || '';
                document.getElementById('specialization').value = edu.specialization || '';
                document.getElementById('boardUniversity').value = edu.board_university || '';
                document.getElementById('scoreType').value = edu.score_type || '';
                document.getElementById('scoreValue').value = edu.score_value || '';
                document.getElementById('attendancePercentage').value = edu.attendance_percentage || '';
                document.getElementById('yearOfEnrollment').value = edu.year_of_enrollment || '';
                document.getElementById('yearOfCompletion').value = edu.year_of_completion || '';
                document.getElementById('duration').value = edu.duration || '';
                document.getElementById('rollNumber').value = edu.roll_number || '';
                document.getElementById('certificateNumber').value = edu.certificate_number || '';
                document.getElementById('finalProjectTitle').value = edu.final_project_title || '';
                document.getElementById('thesisTitle').value = edu.thesis_title || '';
                document.getElementById('supervisorName').value = edu.supervisor_name || '';
                document.getElementById('rank').value = edu.rank || '';
                document.getElementById('remarks').value = edu.remarks || '';
                document.getElementById('distinction').checked = edu.distinction == 1;
                document.getElementById('scholarship').checked = edu.scholarship == 1;
                document.getElementById('isHighestQualification').checked = edu.is_highest_qualification == 1;
                document.getElementById('isVerified').checked = edu.is_verified == 1;
                document.getElementById('statusSelect').value = edu.status || 'completed';
                document.getElementById('isActive').checked = edu.is_active == 1;

                // Set major subjects
                const majorSubjects = edu.major_subjects_array || [];
                Array.from(document.getElementById('majorSubjects').options).forEach(option => {
                    option.selected = majorSubjects.includes(parseInt(option.value));
                });

                new bootstrap.Modal(document.getElementById('educationModal')).show();
            } else {
                showAlert('Failed to load education: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error loading education:', error);
            showAlert('Error loading education. Please try again.', 'danger');
        });
}

function saveEducation() {
    const form = document.getElementById('educationForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Handle checkboxes
    data.distinction = document.getElementById('distinction').checked ? 1 : 0;
    data.scholarship = document.getElementById('scholarship').checked ? 1 : 0;
    data.is_highest_qualification = document.getElementById('isHighestQualification').checked ? 1 : 0;
    data.is_verified = document.getElementById('isVerified').checked ? 1 : 0;
    data.is_active = document.getElementById('isActive').checked ? 1 : 0;

    // Handle major subjects (multi-select)
    const selectedSubjects = Array.from(document.getElementById('majorSubjects').selectedOptions).map(opt => opt.value);
    if (selectedSubjects.length > 6) {
        showAlert('Maximum 6 subjects allowed', 'warning');
        return;
    }
    data.major_subjects = JSON.stringify(selectedSubjects);

    const isEdit = document.getElementById('educationId').value;
    const url = isEdit ?
        `api/person_education.php?action=update&id=${isEdit}` :
        'api/person_education.php?action=create';

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
            bootstrap.Modal.getInstance(document.getElementById('educationModal')).hide();
            loadEducations();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving education:', error);
        showAlert('Error saving education. Please try again.', 'danger');
    });
}

function deleteEducation(id, level) {
    if (confirm(`Are you sure you want to delete this ${level} education record?`)) {
        fetch(`api/person_education.php?action=delete&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadEducations();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting education:', error);
            showAlert('Error deleting education. Please try again.', 'danger');
        });
    }
}

// Pagination
function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');

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
    loadEducations();
}

// Utility functions
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'completed': return 'success';
        case 'in_progress': return 'info';
        case 'dropout': return 'warning';
        default: return 'secondary';
    }
}

function escapeHtml(text) {
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
