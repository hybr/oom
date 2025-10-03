<?php
$pageTitle = "Popular Education Subjects";
$pageDescription = "Manage popular education subjects and courses";

include_once '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">📚 Popular Education Subjects</h1>
                    <p class="text-muted mb-0">Manage and organize educational subjects and courses</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="showFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddSubjectModal()">
                        <i class="fas fa-plus"></i> Add Subject
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
                            <i class="fas fa-book text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Subjects</h6>
                            <h4 class="card-title mb-0" id="totalSubjects">0</h4>
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
                            <h4 class="card-title mb-0" id="activeSubjects">0</h4>
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
                            <i class="fas fa-star text-info"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Core Subjects</h6>
                            <h4 class="card-title mb-0" id="coreSubjects">0</h4>
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
                            <i class="fas fa-users text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Enrollments</h6>
                            <h4 class="card-title mb-0" id="totalEnrollments">0</h4>
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
                            <label for="categoryFilter" class="form-label">Category</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="Science">Science</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Arts">Arts</option>
                                <option value="Commerce">Commerce</option>
                                <option value="Humanities">Humanities</option>
                                <option value="Social Sciences">Social Sciences</option>
                                <option value="Language">Language</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Medical">Medical</option>
                                <option value="Law">Law</option>
                                <option value="Management">Management</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Fine Arts">Fine Arts</option>
                                <option value="Physical Education">Physical Education</option>
                                <option value="Vocational">Vocational</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="levelFilter" class="form-label">Level</label>
                            <select class="form-select" id="levelFilter">
                                <option value="">All Levels</option>
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
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search subjects...">
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
                                <th class="border-0 ps-3">Subject</th>
                                <th class="border-0">Code</th>
                                <th class="border-0">Category</th>
                                <th class="border-0">Level</th>
                                <th class="border-0">Credits</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subjectsTableBody">
                            <tr>
                                <td colspan="8" class="text-center py-4">
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
        <div id="subjectsCardContainer">
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
            <nav aria-label="Subject pagination">
                <ul class="pagination justify-content-center" id="paginationContainer">
                    <!-- Pagination will be generated here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Subject Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subjectModalLabel">Add Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="subjectForm">
                    <input type="hidden" id="subjectId" name="id">

                    <!-- Basic Information -->
                    <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="subjectName" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control" id="subjectName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="subjectCode" class="form-label">Subject Code *</label>
                            <input type="text" class="form-control" id="subjectCode" name="code" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Science">Science</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Arts">Arts</option>
                                <option value="Commerce">Commerce</option>
                                <option value="Humanities">Humanities</option>
                                <option value="Social Sciences">Social Sciences</option>
                                <option value="Language">Language</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Medical">Medical</option>
                                <option value="Law">Law</option>
                                <option value="Management">Management</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Fine Arts">Fine Arts</option>
                                <option value="Physical Education">Physical Education</option>
                                <option value="Vocational">Vocational</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="level" class="form-label">Level *</label>
                            <select class="form-select" id="level" name="level" required>
                                <option value="">Select Level</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Higher Secondary">Higher Secondary</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor">Bachelor</option>
                                <option value="Master">Master</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Certificate">Certificate</option>
                                <option value="All Levels">All Levels</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Academic Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Academic Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="credits" class="form-label">Credits</label>
                            <input type="number" class="form-control" id="credits" name="credits" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="durationHours" class="form-label">Duration (Hours)</label>
                            <input type="number" class="form-control" id="durationHours" name="duration_hours" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="difficultyLevel" class="form-label">Difficulty Level</label>
                            <select class="form-select" id="difficultyLevel" name="difficulty_level">
                                <option value="">Select Difficulty</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Expert">Expert</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="practicalComponent" class="form-label">Practical %</label>
                            <input type="number" class="form-control" id="practicalComponent" name="practical_component" min="0" max="100">
                        </div>
                        <div class="col-md-4">
                            <label for="theoryComponent" class="form-label">Theory %</label>
                            <input type="number" class="form-control" id="theoryComponent" name="theory_component" min="0" max="100">
                        </div>
                        <div class="col-md-4">
                            <label for="language" class="form-label">Language</label>
                            <input type="text" class="form-control" id="language" name="language" value="English">
                        </div>
                    </div>

                    <!-- Subject Type -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Subject Type</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isCore" name="is_core" value="1">
                                <label class="form-check-label" for="isCore">Core Subject</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isElective" name="is_elective" value="1">
                                <label class="form-check-label" for="isElective">Elective</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isMandatory" name="is_mandatory" value="1">
                                <label class="form-check-label" for="isMandatory">Mandatory</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="labRequired" name="lab_required" value="1">
                                <label class="form-check-label" for="labRequired">Lab Required</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="certificationAvailable" name="certification_available" value="1">
                                <label class="form-check-label" for="certificationAvailable">Certification Available</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1">
                                <label class="form-check-label" for="isFeatured">Featured</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Additional Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="syllabusUrl" class="form-label">Syllabus URL</label>
                            <input type="url" class="form-control" id="syllabusUrl" name="syllabus_url">
                        </div>
                        <div class="col-md-6">
                            <label for="industryRelevance" class="form-label">Industry Relevance</label>
                            <input type="text" class="form-control" id="industryRelevance" name="industry_relevance">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSubject()">Save Subject</button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Subject Details</h5>
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
let currentView = 'table';
let lastLoadedSubjects = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadSubjects();
});

// View switching functions
function switchToTableView() {
    currentView = 'table';
    document.getElementById('tableView').classList.remove('d-none');
    document.getElementById('cardView').classList.add('d-none');
    document.getElementById('tableViewBtn').classList.add('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    displaySubjects(lastLoadedSubjects || []);
}

function switchToCardView() {
    currentView = 'card';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.remove('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.add('active');
    displaySubjects(lastLoadedSubjects || []);
}

// Load subjects
async function loadSubjects() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            ...currentFilters
        });

        const response = await fetch(`api/popular_education_subjects.php?${params}`);
        const data = await response.json();

        if (data.success) {
            updateStatistics(data.statistics);
            lastLoadedSubjects = data.subjects;
            displaySubjects(data.subjects);
            updatePagination(data.pagination);
        } else {
            console.error('Failed to load subjects:', data.message);
            showAlert('Failed to load subjects: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading subjects:', error);
        showAlert('Error loading subjects. Please try again.', 'danger');
    }
}

// Update statistics
function updateStatistics(stats) {
    document.getElementById('totalSubjects').textContent = stats.total || 0;
    document.getElementById('activeSubjects').textContent = stats.active || 0;
    document.getElementById('coreSubjects').textContent = stats.core || 0;
    document.getElementById('totalEnrollments').textContent = stats.totalEnrollments || 0;
}

// Display subjects based on current view
function displaySubjects(subjects) {
    if (currentView === 'table') {
        displayTableView(subjects);
    } else if (currentView === 'card') {
        displayCardView(subjects);
    }
}

// Display table view
function displayTableView(subjects) {
    const tbody = document.getElementById('subjectsTableBody');

    if (subjects.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No subjects found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = subjects.map(subject => `
        <tr onclick="viewDetails(${subject.id})" style="cursor: pointer;">
            <td class="ps-3">
                <div>
                    <strong>${escapeHtml(subject.name)}</strong>
                    ${subject.is_featured == 1 ? '<span class="badge bg-warning ms-1">Featured</span>' : ''}
                </div>
            </td>
            <td>
                <code>${escapeHtml(subject.code)}</code>
            </td>
            <td>
                <span class="badge bg-secondary">${escapeHtml(subject.category)}</span>
            </td>
            <td>
                <span class="badge bg-info">${escapeHtml(subject.level)}</span>
            </td>
            <td>${subject.credits || '-'}</td>
            <td>
                ${subject.is_core == 1 ? '<span class="badge bg-primary">Core</span>' : ''}
                ${subject.is_elective == 1 ? '<span class="badge bg-success">Elective</span>' : ''}
                ${subject.is_mandatory == 1 ? '<span class="badge bg-danger">Mandatory</span>' : ''}
            </td>
            <td>
                <span class="badge bg-${getStatusColor(subject.status)}">${escapeHtml(subject.status)}</span>
            </td>
            <td class="text-end pe-3" onclick="event.stopPropagation();">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="viewDetails(${subject.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="editSubject(${subject.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteSubject(${subject.id}, '${escapeHtml(subject.name)}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Display card view
function displayCardView(subjects) {
    const container = document.getElementById('subjectsCardContainer');

    if (subjects.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No subjects found
            </div>
        `;
        return;
    }

    container.innerHTML = subjects.map(subject => `
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">
                        ${escapeHtml(subject.name)}
                        ${subject.is_featured == 1 ? '<span class="badge bg-warning ms-1">Featured</span>' : ''}
                    </h6>
                    <span class="badge bg-${getStatusColor(subject.status)}">${escapeHtml(subject.status)}</span>
                </div>
                <p class="card-text text-muted small mb-2"><code>${escapeHtml(subject.code)}</code></p>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Category:</small><br>
                        <span class="badge bg-secondary">${escapeHtml(subject.category)}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Level:</small><br>
                        <span class="badge bg-info">${escapeHtml(subject.level)}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Credits:</small><br>
                        <strong>${subject.credits || '-'}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Type:</small><br>
                        ${subject.is_core == 1 ? '<span class="badge bg-primary">Core</span>' : ''}
                        ${subject.is_elective == 1 ? '<span class="badge bg-success">Elective</span>' : ''}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-info" onclick="viewDetails(${subject.id})">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-sm btn-outline-primary" onclick="editSubject(${subject.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSubject(${subject.id}, '${escapeHtml(subject.name)}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// View details
function viewDetails(id) {
    fetch(`api/popular_education_subjects.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const subject = data.subject;
                const detailsHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Subject Name</h6>
                            <p class="mb-0"><strong>${escapeHtml(subject.name)}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Subject Code</h6>
                            <p class="mb-0"><code>${escapeHtml(subject.code)}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Category</h6>
                            <p class="mb-0"><span class="badge bg-secondary">${escapeHtml(subject.category)}</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Level</h6>
                            <p class="mb-0"><span class="badge bg-info">${escapeHtml(subject.level)}</span></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Description</h6>
                            <p class="mb-0">${subject.description ? escapeHtml(subject.description) : '-'}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Credits</h6>
                            <p class="mb-0">${subject.credits || '-'}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Duration</h6>
                            <p class="mb-0">${subject.duration_hours ? subject.duration_hours + ' hours' : '-'}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Difficulty</h6>
                            <p class="mb-0">${subject.difficulty_level ? escapeHtml(subject.difficulty_level) : '-'}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Subject Type</h6>
                            <div>
                                ${subject.is_core == 1 ? '<span class="badge bg-primary me-1">Core</span>' : ''}
                                ${subject.is_elective == 1 ? '<span class="badge bg-success me-1">Elective</span>' : ''}
                                ${subject.is_mandatory == 1 ? '<span class="badge bg-danger me-1">Mandatory</span>' : ''}
                                ${subject.lab_required == 1 ? '<span class="badge bg-warning me-1">Lab Required</span>' : ''}
                                ${subject.certification_available == 1 ? '<span class="badge bg-info me-1">Certification</span>' : ''}
                            </div>
                        </div>
                        ${subject.has_prerequisites ? `
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Prerequisites</h6>
                            <div>
                                ${subject.prerequisite_subjects.map(p => `<span class="badge bg-secondary me-1">${escapeHtml(p.name)} (${escapeHtml(p.code)})</span>`).join('')}
                            </div>
                        </div>
                        ` : ''}
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Enrollment Count</h6>
                            <p class="mb-0">${subject.enrollment_count || 0}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Popularity Score</h6>
                            <p class="mb-0">${subject.popularity_score || 0}</p>
                        </div>
                    </div>
                `;

                document.getElementById('detailsContent').innerHTML = detailsHTML;
                document.getElementById('editFromDetailsBtn').onclick = function() {
                    bootstrap.Modal.getInstance(document.getElementById('viewDetailsModal')).hide();
                    editSubject(id);
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
    const filtersRow = document.getElementById('filtersRow');
    filtersRow.classList.toggle('d-none');
}

function applyFilters() {
    currentFilters = {
        category: document.getElementById('categoryFilter').value,
        level: document.getElementById('levelFilter').value,
        status: document.getElementById('statusFilter').value,
        search: document.getElementById('searchFilter').value
    };

    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key];
        }
    });

    currentPage = 1;
    loadSubjects();
}

function clearFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('levelFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    currentPage = 1;
    loadSubjects();
}

// Modal functions
function showAddSubjectModal() {
    document.getElementById('subjectModalLabel').textContent = 'Add Subject';
    document.getElementById('subjectForm').reset();
    document.getElementById('subjectId').value = '';
    document.getElementById('isActive').checked = true;
    new bootstrap.Modal(document.getElementById('subjectModal')).show();
}

function editSubject(id) {
    fetch(`api/popular_education_subjects.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const subject = data.subject;
                document.getElementById('subjectModalLabel').textContent = 'Edit Subject';
                document.getElementById('subjectId').value = subject.id;
                document.getElementById('subjectName').value = subject.name || '';
                document.getElementById('subjectCode').value = subject.code || '';
                document.getElementById('category').value = subject.category || '';
                document.getElementById('level').value = subject.level || '';
                document.getElementById('description').value = subject.description || '';
                document.getElementById('credits').value = subject.credits || '';
                document.getElementById('durationHours').value = subject.duration_hours || '';
                document.getElementById('difficultyLevel').value = subject.difficulty_level || '';
                document.getElementById('practicalComponent').value = subject.practical_component || '';
                document.getElementById('theoryComponent').value = subject.theory_component || '';
                document.getElementById('language').value = subject.language || 'English';
                document.getElementById('syllabusUrl').value = subject.syllabus_url || '';
                document.getElementById('industryRelevance').value = subject.industry_relevance || '';

                document.getElementById('isCore').checked = subject.is_core == 1;
                document.getElementById('isElective').checked = subject.is_elective == 1;
                document.getElementById('isMandatory').checked = subject.is_mandatory == 1;
                document.getElementById('labRequired').checked = subject.lab_required == 1;
                document.getElementById('certificationAvailable').checked = subject.certification_available == 1;
                document.getElementById('isFeatured').checked = subject.is_featured == 1;
                document.getElementById('isActive').checked = subject.is_active == 1;

                new bootstrap.Modal(document.getElementById('subjectModal')).show();
            } else {
                showAlert('Failed to load subject: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error loading subject:', error);
            showAlert('Error loading subject. Please try again.', 'danger');
        });
}

function saveSubject() {
    const form = document.getElementById('subjectForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Handle checkboxes
    data.is_core = document.getElementById('isCore').checked ? 1 : 0;
    data.is_elective = document.getElementById('isElective').checked ? 1 : 0;
    data.is_mandatory = document.getElementById('isMandatory').checked ? 1 : 0;
    data.lab_required = document.getElementById('labRequired').checked ? 1 : 0;
    data.certification_available = document.getElementById('certificationAvailable').checked ? 1 : 0;
    data.is_featured = document.getElementById('isFeatured').checked ? 1 : 0;
    data.is_active = document.getElementById('isActive').checked ? 1 : 0;

    const isEdit = document.getElementById('subjectId').value;
    const url = isEdit ?
        `api/popular_education_subjects.php?action=update&id=${isEdit}` :
        'api/popular_education_subjects.php?action=create';

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
            bootstrap.Modal.getInstance(document.getElementById('subjectModal')).hide();
            loadSubjects();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving subject:', error);
        showAlert('Error saving subject. Please try again.', 'danger');
    });
}

function deleteSubject(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        fetch(`api/popular_education_subjects.php?action=delete&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadSubjects();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting subject:', error);
            showAlert('Error deleting subject. Please try again.', 'danger');
        });
    }
}

// Pagination
function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const info = document.getElementById('paginationInfo');

    info.textContent = `Showing ${((pagination.currentPage - 1) * pagination.itemsPerPage) + 1}-${Math.min(pagination.currentPage * pagination.itemsPerPage, pagination.totalItems)} of ${pagination.totalItems} subjects`;

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
    loadSubjects();
}

// Utility functions
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'active': return 'success';
        case 'inactive': return 'secondary';
        case 'draft': return 'warning';
        case 'archived': return 'danger';
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
