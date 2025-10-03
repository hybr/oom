<?php include '../includes/header.php'; ?>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>🎓 Person Skills Management</h2>
            <p class="text-muted mb-0">Manage person skills, proficiency levels, and certifications</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="showAddSkillModal()">
                <i class="fas fa-plus"></i> Add Skill
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Skills</h6>
                            <h3 class="mb-0" id="totalSkills">0</h3>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Certified</h6>
                            <h3 class="mb-0" id="certifiedSkills">0</h3>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Expert Level</h6>
                            <h3 class="mb-0" id="expertSkills">0</h3>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Avg Proficiency</h6>
                            <h3 class="mb-0" id="avgProficiency">0%</h3>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <button class="btn btn-sm btn-outline-secondary" onclick="showFilters()">
                <i class="fas fa-filter"></i> Toggle Filters
            </button>
        </div>
        <div class="card-body d-none" id="filtersRow">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Person</label>
                    <select class="form-select" id="personFilter">
                        <option value="">All Persons</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Proficiency Level</label>
                    <select class="form-select" id="proficiencyFilter">
                        <option value="">All Levels</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Elementary">Elementary</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Expert">Expert</option>
                        <option value="Master">Master</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Certification</label>
                    <select class="form-select" id="certifiedFilter">
                        <option value="">All</option>
                        <option value="1">Certified</option>
                        <option value="0">Not Certified</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchFilter" placeholder="Search skills...">
                        <button class="btn btn-outline-secondary" onclick="applyFilters()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" onclick="applyFilters()">Apply Filters</button>
                    <button class="btn btn-secondary btn-sm" onclick="clearFilters()">Clear Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Skills Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Person</th>
                            <th>Skill Name</th>
                            <th>Category</th>
                            <th>Proficiency</th>
                            <th>Experience</th>
                            <th>Certification</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="skillsTableBody">
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Skills pagination">
                <ul class="pagination justify-content-center" id="pagination">
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Skill Modal -->
<div class="modal fade" id="skillModal" tabindex="-1" aria-labelledby="skillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="skillModalLabel">Add Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="skillForm">
                <div class="modal-body">
                    <input type="hidden" id="personSkillId" name="person_skill_id">

                    <!-- Basic Information -->
                    <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="personId" class="form-label">Person <span class="text-danger">*</span></label>
                            <select class="form-select" id="personId" name="person_id" required>
                                <option value="">Select Person</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="skillId" class="form-label">Skill <span class="text-danger">*</span></label>
                            <select class="form-select" id="skillId" name="skill_id" required>
                                <option value="">Select Skill</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="proficiencyLevel" class="form-label">Proficiency Level</label>
                            <select class="form-select" id="proficiencyLevel" name="proficiency_level">
                                <option value="Beginner">Beginner</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Expert">Expert</option>
                                <option value="Master">Master</option>
                            </select>
                        </div>
                    </div>

                    <!-- Experience -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Experience</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="yearsOfExperience" class="form-label">Years of Experience</label>
                            <input type="number" class="form-control" id="yearsOfExperience" name="years_of_experience" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="monthsOfExperience" class="form-label">Months (0-11)</label>
                            <input type="number" class="form-control" id="monthsOfExperience" name="months_of_experience" min="0" max="11">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lastUsedDate" class="form-label">Last Used Date</label>
                            <input type="date" class="form-control" id="lastUsedDate" name="last_used_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="frequencyOfUse" class="form-label">Frequency of Use</label>
                            <select class="form-select" id="frequencyOfUse" name="frequency_of_use">
                                <option value="">Select Frequency</option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Occasionally">Occasionally</option>
                                <option value="Rarely">Rarely</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contextOfUse" class="form-label">Context of Use</label>
                            <input type="text" class="form-control" id="contextOfUse" name="context_of_use">
                        </div>
                    </div>

                    <!-- Certification -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Certification</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="certificationName" class="form-label">Certification Name</label>
                            <input type="text" class="form-control" id="certificationName" name="certification_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="certificationProvider" class="form-label">Provider</label>
                            <input type="text" class="form-control" id="certificationProvider" name="certification_provider">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="certificationNumber" class="form-label">Certificate Number</label>
                            <input type="text" class="form-control" id="certificationNumber" name="certification_number">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="certificationDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="certificationDate" name="certification_date">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="certificationExpiry" class="form-label">Expiry</label>
                            <input type="date" class="form-control" id="certificationExpiry" name="certification_expiry">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="certificationUrl" class="form-label">Certificate URL</label>
                            <input type="url" class="form-control" id="certificationUrl" name="certification_url">
                        </div>
                    </div>

                    <!-- Ratings & Projects -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Ratings & Projects</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="selfRating" class="form-label">Self Rating (0-10)</label>
                            <input type="number" class="form-control" id="selfRating" name="self_rating" min="0" max="10" step="0.1">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="projectsCount" class="form-label">Projects Count</label>
                            <input type="number" class="form-control" id="projectsCount" name="projects_count" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="trainingHours" class="form-label">Training Hours</label>
                            <input type="number" class="form-control" id="trainingHours" name="training_hours" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="practiceHours" class="form-label">Practice Hours</label>
                            <input type="number" class="form-control" id="practiceHours" name="practice_hours" min="0">
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Additional Details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="portfolioUrl" class="form-label">Portfolio URL</label>
                            <input type="url" class="form-control" id="portfolioUrl" name="portfolio_url">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="githubRepos" class="form-label">GitHub Repos</label>
                            <input type="text" class="form-control" id="githubRepos" name="github_repos">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Flags -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Status & Flags</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isPrimarySkill" name="is_primary_skill">
                                <label class="form-check-label" for="isPrimarySkill">Primary Skill</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isCoreSkill" name="is_core_skill">
                                <label class="form-check-label" for="isCoreSkill">Core Skill</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="willingToMentor" name="willing_to_mentor">
                                <label class="form-check-label" for="willingToMentor">Willing to Mentor</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusSelect" class="form-label">Status</label>
                            <select class="form-select" id="statusSelect" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="learning">Learning</option>
                                <option value="proficient">Proficient</option>
                                <option value="expert">Expert</option>
                                <option value="outdated">Outdated</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Skill Details</h5>
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
let lastLoadedSkills = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadSkills();
    loadPersons();
    loadPopularSkills();
});

// Load skills
async function loadSkills() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            ...currentFilters
        });

        const response = await fetch(`api/person_skill.php?${params}`);
        const data = await response.json();

        if (data.success) {
            updateStatistics(data.statistics);
            lastLoadedSkills = data.skills;
            displaySkills(data.skills);
            updatePagination(data.pagination);
        } else {
            console.error('Failed to load skills:', data.message);
            showAlert('Failed to load skills: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading skills:', error);
        showAlert('Error loading skills. Please try again.', 'danger');
    }
}

// Load persons for dropdown
async function loadPersons() {
    try {
        const response = await fetch('api/persons.php?action=list&limit=1000');
        const data = await response.json();

        if (data.success) {
            const selects = ['personId', 'personFilter'];
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (!select) return;

                const currentValue = select.value;
                select.innerHTML = selectId === 'personFilter' ?
                    '<option value="">All Persons</option>' :
                    '<option value="">Select Person</option>';

                data.persons.forEach(person => {
                    const option = document.createElement('option');
                    option.value = person.id;
                    option.textContent = `${person.first_name || ''} ${person.last_name || ''}`.trim() || `Person ${person.id}`;
                    select.appendChild(option);
                });

                if (currentValue) select.value = currentValue;
            });
        }
    } catch (error) {
        console.error('Error loading persons:', error);
    }
}

// Load popular skills for dropdown
async function loadPopularSkills() {
    try {
        const response = await fetch('api/popular_skills.php?action=list&limit=1000');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('skillId');
            if (!select) return;

            const currentValue = select.value;
            select.innerHTML = '<option value="">Select Skill</option>';

            data.skills.forEach(skill => {
                const option = document.createElement('option');
                option.value = skill.id;
                option.textContent = skill.name;
                option.setAttribute('data-category', skill.skill_category || '');
                select.appendChild(option);
            });

            if (currentValue) select.value = currentValue;
        }
    } catch (error) {
        console.error('Error loading skills:', error);
    }
}

// Update statistics
function updateStatistics(stats) {
    document.getElementById('totalSkills').textContent = stats.total || 0;
    document.getElementById('certifiedSkills').textContent = stats.certified_skills || 0;
    document.getElementById('expertSkills').textContent = stats.expert_skills || 0;
    document.getElementById('avgProficiency').textContent = Math.round(stats.avg_proficiency || 0) + '%';
}

// Display skills
function displaySkills(skills) {
    const tbody = document.getElementById('skillsTableBody');

    if (!skills || skills.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center">No skills found</td></tr>';
        return;
    }

    tbody.innerHTML = skills.map(skill => `
        <tr>
            <td>${skill.id}</td>
            <td>${escapeHtml(skill.person_name || 'N/A')}</td>
            <td>
                <strong>${escapeHtml(skill.skill_name)}</strong>
                ${skill.is_primary_skill == 1 ? '<span class="badge bg-primary ms-1">Primary</span>' : ''}
                ${skill.is_core_skill == 1 ? '<span class="badge bg-info ms-1">Core</span>' : ''}
            </td>
            <td>${escapeHtml(skill.skill_category || '-')}</td>
            <td>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar ${getProficiencyColor(skill.proficiency_level)}"
                         role="progressbar"
                         style="width: ${skill.proficiency_percentage || 0}%"
                         aria-valuenow="${skill.proficiency_percentage || 0}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        ${skill.proficiency_level || 'N/A'}
                    </div>
                </div>
            </td>
            <td>${formatExperience(skill.years_of_experience, skill.months_of_experience)}</td>
            <td>
                ${skill.is_certified == 1 ?
                    `<span class="badge bg-success">✓ ${escapeHtml(skill.certification_name || 'Certified')}</span>` :
                    '<span class="badge bg-secondary">No</span>'
                }
            </td>
            <td>
                ${skill.average_rating > 0 ?
                    `<span class="badge bg-warning">${skill.average_rating}/10</span>` :
                    '-'
                }
            </td>
            <td><span class="badge bg-${getStatusColor(skill.status)}">${escapeHtml(skill.status)}</span></td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="viewDetails(${skill.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="editSkill(${skill.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteSkill(${skill.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update pagination
function updatePagination(pagination) {
    const paginationEl = document.getElementById('pagination');
    const totalPages = pagination.total_pages;
    const currentPageNum = pagination.current_page;

    if (totalPages <= 1) {
        paginationEl.innerHTML = '';
        return;
    }

    let html = '';

    // Previous button
    html += `
        <li class="page-item ${currentPageNum === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPageNum - 1}); return false;">Previous</a>
        </li>
    `;

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPageNum - 2 && i <= currentPageNum + 2)) {
            html += `
                <li class="page-item ${i === currentPageNum ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `;
        } else if (i === currentPageNum - 3 || i === currentPageNum + 3) {
            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Next button
    html += `
        <li class="page-item ${currentPageNum === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPageNum + 1}); return false;">Next</a>
        </li>
    `;

    paginationEl.innerHTML = html;
}

// Change page
function changePage(page) {
    currentPage = page;
    loadSkills();
}

// View skill details
function viewDetails(id) {
    fetch(`api/person_skill.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const skill = data.skill;

                const detailsHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Skill Name</h6>
                            <p class="mb-0">${escapeHtml(skill.skill_name)}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Category</h6>
                            <p class="mb-0">${escapeHtml(skill.skill_category || '-')}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Proficiency Level</h6>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar ${getProficiencyColor(skill.proficiency_level)}"
                                     role="progressbar"
                                     style="width: ${skill.proficiency_percentage || 0}%">
                                    ${skill.proficiency_level} (${skill.proficiency_percentage}%)
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Experience</h6>
                            <p class="mb-0">${formatExperience(skill.years_of_experience, skill.months_of_experience)}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Average Rating</h6>
                            <p class="mb-0">${skill.average_rating > 0 ? skill.average_rating + '/10' : 'Not Rated'}</p>
                        </div>
                        ${skill.is_certified == 1 ? `
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Certification</h6>
                            <p class="mb-0">
                                <strong>${escapeHtml(skill.certification_name)}</strong> by ${escapeHtml(skill.certification_provider)}<br>
                                ${skill.certification_date ? 'Date: ' + skill.certification_date : ''}
                                ${skill.certification_expiry ? ' | Expires: ' + skill.certification_expiry : ''}
                            </p>
                        </div>
                        ` : ''}
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Status & Flags</h6>
                            <div>
                                <span class="badge bg-${getStatusColor(skill.status)} me-1">${escapeHtml(skill.status)}</span>
                                ${skill.is_primary_skill == 1 ? '<span class="badge bg-primary me-1">Primary</span>' : ''}
                                ${skill.is_core_skill == 1 ? '<span class="badge bg-info me-1">Core</span>' : ''}
                                ${skill.willing_to_mentor == 1 ? '<span class="badge bg-success me-1">Willing to Mentor</span>' : ''}
                            </div>
                        </div>
                        ${skill.notes ? `
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Notes</h6>
                            <p class="mb-0">${escapeHtml(skill.notes)}</p>
                        </div>
                        ` : ''}
                    </div>
                `;

                document.getElementById('detailsContent').innerHTML = detailsHTML;
                document.getElementById('editFromDetailsBtn').onclick = function() {
                    bootstrap.Modal.getInstance(document.getElementById('viewDetailsModal')).hide();
                    editSkill(id);
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
        person_id: document.getElementById('personFilter').value,
        proficiency_level: document.getElementById('proficiencyFilter').value,
        is_certified: document.getElementById('certifiedFilter').value,
        search: document.getElementById('searchFilter').value
    };

    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key];
        }
    });

    currentPage = 1;
    loadSkills();
}

function clearFilters() {
    document.getElementById('personFilter').value = '';
    document.getElementById('proficiencyFilter').value = '';
    document.getElementById('certifiedFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    currentPage = 1;
    loadSkills();
}

// Modal functions
function showAddSkillModal() {
    document.getElementById('skillModalLabel').textContent = 'Add Skill';
    document.getElementById('skillForm').reset();
    document.getElementById('personSkillId').value = '';
    document.getElementById('isActive').checked = true;
    document.getElementById('statusSelect').value = 'active';
    new bootstrap.Modal(document.getElementById('skillModal')).show();
}

function editSkill(id) {
    fetch(`api/person_skill.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const skill = data.skill;
                document.getElementById('skillModalLabel').textContent = 'Edit Skill';
                document.getElementById('personSkillId').value = skill.id;
                document.getElementById('personId').value = skill.person_id;
                document.getElementById('skillId').value = skill.skill_id;
                document.getElementById('proficiencyLevel').value = skill.proficiency_level;
                document.getElementById('yearsOfExperience').value = skill.years_of_experience || 0;
                document.getElementById('monthsOfExperience').value = skill.months_of_experience || 0;
                document.getElementById('lastUsedDate').value = skill.last_used_date || '';
                document.getElementById('frequencyOfUse').value = skill.frequency_of_use || '';
                document.getElementById('contextOfUse').value = skill.context_of_use || '';
                document.getElementById('certificationName').value = skill.certification_name || '';
                document.getElementById('certificationProvider').value = skill.certification_provider || '';
                document.getElementById('certificationNumber').value = skill.certification_number || '';
                document.getElementById('certificationDate').value = skill.certification_date || '';
                document.getElementById('certificationExpiry').value = skill.certification_expiry || '';
                document.getElementById('certificationUrl').value = skill.certification_url || '';
                document.getElementById('selfRating').value = skill.self_rating || '';
                document.getElementById('projectsCount').value = skill.projects_count || 0;
                document.getElementById('trainingHours').value = skill.training_hours || '';
                document.getElementById('practiceHours').value = skill.practice_hours || '';
                document.getElementById('portfolioUrl').value = skill.portfolio_url || '';
                document.getElementById('githubRepos').value = skill.github_repos || '';
                document.getElementById('notes').value = skill.notes || '';
                document.getElementById('isPrimarySkill').checked = skill.is_primary_skill == 1;
                document.getElementById('isCoreSkill').checked = skill.is_core_skill == 1;
                document.getElementById('willingToMentor').checked = skill.willing_to_mentor == 1;
                document.getElementById('isActive').checked = skill.is_active == 1;
                document.getElementById('statusSelect').value = skill.status;

                new bootstrap.Modal(document.getElementById('skillModal')).show();
            }
        })
        .catch(error => {
            console.error('Error loading skill:', error);
            showAlert('Error loading skill. Please try again.', 'danger');
        });
}

// Form submission
document.getElementById('skillForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const personSkillId = document.getElementById('personSkillId').value;
    const formData = {
        person_id: document.getElementById('personId').value,
        skill_id: document.getElementById('skillId').value,
        proficiency_level: document.getElementById('proficiencyLevel').value,
        years_of_experience: parseInt(document.getElementById('yearsOfExperience').value) || 0,
        months_of_experience: parseInt(document.getElementById('monthsOfExperience').value) || 0,
        last_used_date: document.getElementById('lastUsedDate').value,
        frequency_of_use: document.getElementById('frequencyOfUse').value,
        context_of_use: document.getElementById('contextOfUse').value,
        certification_name: document.getElementById('certificationName').value,
        certification_provider: document.getElementById('certificationProvider').value,
        certification_number: document.getElementById('certificationNumber').value,
        certification_date: document.getElementById('certificationDate').value,
        certification_expiry: document.getElementById('certificationExpiry').value,
        certification_url: document.getElementById('certificationUrl').value,
        self_rating: parseFloat(document.getElementById('selfRating').value) || 0,
        projects_count: parseInt(document.getElementById('projectsCount').value) || 0,
        training_hours: parseInt(document.getElementById('trainingHours').value) || null,
        practice_hours: parseInt(document.getElementById('practiceHours').value) || null,
        portfolio_url: document.getElementById('portfolioUrl').value,
        github_repos: document.getElementById('githubRepos').value,
        notes: document.getElementById('notes').value,
        is_primary_skill: document.getElementById('isPrimarySkill').checked ? 1 : 0,
        is_core_skill: document.getElementById('isCoreSkill').checked ? 1 : 0,
        willing_to_mentor: document.getElementById('willingToMentor').checked ? 1 : 0,
        is_active: document.getElementById('isActive').checked ? 1 : 0,
        status: document.getElementById('statusSelect').value
    };

    try {
        let response;
        if (personSkillId) {
            formData.skill_id = personSkillId;
            response = await fetch('api/person_skill.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        } else {
            response = await fetch('api/person_skill.php?action=create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        }

        const data = await response.json();

        if (data.success) {
            showAlert(personSkillId ? 'Skill updated successfully' : 'Skill added successfully', 'success');
            bootstrap.Modal.getInstance(document.getElementById('skillModal')).hide();
            loadSkills();
        } else {
            showAlert('Error: ' + data.error, 'danger');
        }
    } catch (error) {
        console.error('Error saving skill:', error);
        showAlert('Error saving skill. Please try again.', 'danger');
    }
});

// Delete skill
async function deleteSkill(id) {
    if (!confirm('Are you sure you want to delete this skill?')) {
        return;
    }

    try {
        const response = await fetch(`api/person_skill.php?id=${id}`, {
            method: 'DELETE'
        });

        const data = await response.json();

        if (data.success) {
            showAlert('Skill deleted successfully', 'success');
            loadSkills();
        } else {
            showAlert('Error: ' + data.error, 'danger');
        }
    } catch (error) {
        console.error('Error deleting skill:', error);
        showAlert('Error deleting skill. Please try again.', 'danger');
    }
}

// Utility functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getStatusColor(status) {
    const colors = {
        'active': 'success',
        'inactive': 'secondary',
        'learning': 'info',
        'proficient': 'primary',
        'expert': 'warning',
        'outdated': 'danger'
    };
    return colors[status] || 'secondary';
}

function getProficiencyColor(level) {
    const colors = {
        'Beginner': 'bg-danger',
        'Elementary': 'bg-warning',
        'Intermediate': 'bg-info',
        'Advanced': 'bg-primary',
        'Expert': 'bg-success',
        'Master': 'bg-dark'
    };
    return colors[level] || 'bg-secondary';
}

function formatExperience(years, months) {
    years = parseInt(years) || 0;
    months = parseInt(months) || 0;

    if (years === 0 && months === 0) return '-';

    let parts = [];
    if (years > 0) parts.push(`${years}y`);
    if (months > 0) parts.push(`${months}m`);

    return parts.join(' ');
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<?php include '../includes/footer.php'; ?>
