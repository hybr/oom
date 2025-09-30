<?php
$pageTitle = "Popular Organization Skills";
$pageDescription = "Manage popular organization skills";

include_once '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">🏢 Popular Organization Skills</h1>
                    <p class="text-muted mb-0">Manage and organize popular skills across organizations</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="showFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddSkillModal()">
                        <i class="fas fa-plus"></i> Add Skill
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
                            <i class="fas fa-building text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Skills</h6>
                            <h4 class="card-title mb-0" id="totalSkills">0</h4>
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
                            <h4 class="card-title mb-0" id="activeSkills">0</h4>
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
                            <i class="fas fa-cogs text-info"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Functional</h6>
                            <h4 class="card-title mb-0" id="functionalSkills">0</h4>
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
                            <h6 class="card-subtitle mb-1 text-muted">Total Employees</h6>
                            <h4 class="card-title mb-0" id="totalEmployees">0</h4>
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
                            <label for="skillTypeFilter" class="form-label">Skill Type</label>
                            <select class="form-select" id="skillTypeFilter">
                                <option value="">All Types</option>
                                <option value="Functional">Functional</option>
                                <option value="Divisional">Divisional</option>
                                <option value="Matrix">Matrix</option>
                                <option value="Network">Network</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="functionCategoryFilter" class="form-label">Function Category</label>
                            <select class="form-select" id="functionCategoryFilter">
                                <option value="">All Categories</option>
                                <option value="Leadership">Leadership</option>
                                <option value="Finance">Finance</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Technology">Technology</option>
                                <option value="Operations">Operations</option>
                                <option value="Sales">Sales</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Legal">Legal</option>
                                <option value="Research">Research</option>
                                <option value="Support">Support</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="operationalStatusFilter" class="form-label">Status</label>
                            <select class="form-select" id="operationalStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                                <option value="Dissolved">Dissolved</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search skills...">
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
                    <button type="button" class="btn btn-outline-primary" id="treeViewBtn" onclick="switchToTreeView()">
                        <i class="fas fa-sitemap"></i> Tree
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
                                <th class="border-0 ps-3">Skill</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Function</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Employees</th>
                                <th class="border-0">Parent</th>
                                <th class="border-0 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="skillsTableBody">
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
        <div id="skillsCardContainer">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tree View -->
    <div id="treeView" class="d-none">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div id="skillTreeContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Skill pagination">
                <ul class="pagination justify-content-center" id="paginationContainer">
                    <!-- Pagination will be generated here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Skill Modal -->
<div class="modal fade" id="skillModal" tabindex="-1" aria-labelledby="skillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="skillModalLabel">Add Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="skillForm">
                    <input type="hidden" id="skillId" name="id">

                    <!-- Basic Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="skillName" class="form-label">Skill Name *</label>
                            <input type="text" class="form-control" id="skillName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="skillCode" class="form-label">Code *</label>
                            <input type="text" class="form-control" id="skillCode" name="code" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="skillType" class="form-label">Type *</label>
                            <select class="form-select" id="skillType" name="skill_type" required>
                                <option value="">Select Type</option>
                                <option value="Functional">Functional</option>
                                <option value="Divisional">Divisional</option>
                                <option value="Matrix">Matrix</option>
                                <option value="Network">Network</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="functionCategory" class="form-label">Function Category *</label>
                            <select class="form-select" id="functionCategory" name="function_category" required>
                                <option value="">Select Category</option>
                                <option value="Leadership">Leadership</option>
                                <option value="Finance">Finance</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Technology">Technology</option>
                                <option value="Operations">Operations</option>
                                <option value="Sales">Sales</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Legal">Legal</option>
                                <option value="Research">Research</option>
                                <option value="Support">Support</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="operationalStatus" class="form-label">Status *</label>
                            <select class="form-select" id="operationalStatus" name="operational_status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                                <option value="Dissolved">Dissolved</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="parentSkill" class="form-label">Parent Skill</label>
                            <select class="form-select" id="parentSkill" name="parent_skill_id">
                                <option value="">No Parent (Top Level)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="skillDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="skillDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="employeeCount" class="form-label">Employee Count</label>
                            <input type="number" class="form-control" id="employeeCount" name="employee_count" min="0">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSkill()">Save Skill</button>
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
let lastLoadedSkills = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadSkills();
});

// View switching functions
function switchToTableView() {
    currentView = 'table';
    document.getElementById('tableView').classList.remove('d-none');
    document.getElementById('cardView').classList.add('d-none');
    document.getElementById('treeView').classList.add('d-none');
    document.getElementById('tableViewBtn').classList.add('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    document.getElementById('treeViewBtn').classList.remove('active');
    displaySkills(lastLoadedSkills || []);
}

function switchToCardView() {
    currentView = 'card';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.remove('d-none');
    document.getElementById('treeView').classList.add('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.add('active');
    document.getElementById('treeViewBtn').classList.remove('active');
    displaySkills(lastLoadedSkills || []);
}

function switchToTreeView() {
    currentView = 'tree';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.add('d-none');
    document.getElementById('treeView').classList.remove('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    document.getElementById('treeViewBtn').classList.add('active');
    displayTreeView(lastLoadedSkills || []);
}

// Load skills
async function loadSkills() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            ...currentFilters
        });

        const response = await fetch(`api/popular_skills.php?${params}`);
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

// Update statistics
function updateStatistics(stats) {
    document.getElementById('totalSkills').textContent = stats.total || 0;
    document.getElementById('activeSkills').textContent = stats.active || 0;
    document.getElementById('functionalSkills').textContent = stats.functional || 0;
    document.getElementById('totalEmployees').textContent = stats.totalEmployees || 0;
}

// Display skills based on current view
function displaySkills(skills) {
    if (currentView === 'table') {
        displayTableView(skills);
    } else if (currentView === 'card') {
        displayCardView(skills);
    }
}

// Display table view
function displayTableView(skills) {
    const tbody = document.getElementById('skillsTableBody');

    if (skills.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No skills found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = skills.map(dept => `
        <tr>
            <td class="ps-3">
                <div>
                    <strong>${escapeHtml(dept.name)}</strong>
                    <br>
                    <small class="text-muted">${escapeHtml(dept.code)}</small>
                </div>
            </td>
            <td>
                <span class="badge bg-secondary">${escapeHtml(dept.skill_type || 'N/A')}</span>
            </td>
            <td>
                <span class="badge bg-info">${escapeHtml(dept.function_category || 'N/A')}</span>
            </td>
            <td>
                <span class="badge bg-${getStatusColor(dept.operational_status)}">${escapeHtml(dept.operational_status || 'N/A')}</span>
            </td>
            <td>${dept.employee_count || 0}</td>
            <td>
                ${dept.parent_skill_name ? escapeHtml(dept.parent_skill_name) : '<span class="text-muted">Top Level</span>'}
            </td>
            <td class="text-end pe-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editSkill(${dept.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteSkill(${dept.id}, '${escapeHtml(dept.name)}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Display card view
function displayCardView(skills) {
    const container = document.getElementById('skillsCardContainer');

    if (skills.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No skills found
            </div>
        `;
        return;
    }

    container.innerHTML = skills.map(dept => `
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">${escapeHtml(dept.name)}</h6>
                    <span class="badge bg-${getStatusColor(dept.operational_status)}">${escapeHtml(dept.operational_status || 'N/A')}</span>
                </div>
                <p class="card-text text-muted small mb-2">${escapeHtml(dept.code)}</p>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Type:</small><br>
                        <span class="badge bg-secondary">${escapeHtml(dept.skill_type || 'N/A')}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Function:</small><br>
                        <span class="badge bg-info">${escapeHtml(dept.function_category || 'N/A')}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Employees:</small><br>
                        <strong>${dept.employee_count || 0}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Parent:</small><br>
                        ${dept.parent_skill_name ? escapeHtml(dept.parent_skill_name) : '<span class="text-muted">Top Level</span>'}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="editSkill(${dept.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSkill(${dept.id}, '${escapeHtml(dept.name)}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Display tree view
function displayTreeView(skills) {
    const container = document.getElementById('skillTreeContainer');

    if (skills.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No skills found
            </div>
        `;
        return;
    }

    // Build tree structure
    const tree = buildTree(skills);
    container.innerHTML = renderTree(tree);
}

// Build tree structure from flat array
function buildTree(skills) {
    const map = {};
    const roots = [];

    // Create map
    skills.forEach(dept => {
        map[dept.id] = { ...dept, children: [] };
    });

    // Build tree
    skills.forEach(dept => {
        if (dept.parent_skill_id && map[dept.parent_skill_id]) {
            map[dept.parent_skill_id].children.push(map[dept.id]);
        } else {
            roots.push(map[dept.id]);
        }
    });

    return roots;
}

// Render tree HTML
function renderTree(nodes, level = 0) {
    return nodes.map(node => `
        <div class="tree-node" style="margin-left: ${level * 20}px;">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-building text-primary me-2"></i>
                <strong>${escapeHtml(node.name)}</strong>
                <span class="badge bg-${getStatusColor(node.operational_status)} ms-2">${escapeHtml(node.operational_status || 'N/A')}</span>
            </div>
            ${node.children.length > 0 ? renderTree(node.children, level + 1) : ''}
        </div>
    `).join('');
}

// Filter functions
function showFilters() {
    const filtersRow = document.getElementById('filtersRow');
    filtersRow.classList.toggle('d-none');
}

function applyFilters() {
    currentFilters = {
        skill_type: document.getElementById('skillTypeFilter').value,
        function_category: document.getElementById('functionCategoryFilter').value,
        operational_status: document.getElementById('operationalStatusFilter').value,
        search: document.getElementById('searchFilter').value
    };

    // Remove empty filters
    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key];
        }
    });

    currentPage = 1;
    loadSkills();
}

function clearFilters() {
    document.getElementById('skillTypeFilter').value = '';
    document.getElementById('functionCategoryFilter').value = '';
    document.getElementById('operationalStatusFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    currentPage = 1;
    loadSkills();
}

// Modal functions
function showAddSkillModal() {
    document.getElementById('skillModalLabel').textContent = 'Add Skill';
    document.getElementById('skillForm').reset();
    document.getElementById('skillId').value = '';
    loadParentSkills();
    new bootstrap.Modal(document.getElementById('skillModal')).show();
}

function editSkill(id) {
    // Load skill data and populate form
    fetch(`api/popular_skills.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const dept = data.skill;
                document.getElementById('skillModalLabel').textContent = 'Edit Skill';
                document.getElementById('skillId').value = dept.id;
                document.getElementById('skillName').value = dept.name || '';
                document.getElementById('skillCode').value = dept.code || '';
                document.getElementById('skillType').value = dept.skill_type || '';
                document.getElementById('functionCategory').value = dept.function_category || '';
                document.getElementById('operationalStatus').value = dept.operational_status || '';
                document.getElementById('parentSkill').value = dept.parent_skill_id || '';
                document.getElementById('skillDescription').value = dept.description || '';
                document.getElementById('employeeCount').value = dept.employee_count || '';
                document.getElementById('priorityLevel').value = dept.priority_level || '';

                loadParentSkills(dept.id);
                new bootstrap.Modal(document.getElementById('skillModal')).show();
            } else {
                showAlert('Failed to load skill: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error loading skill:', error);
            showAlert('Error loading skill. Please try again.', 'danger');
        });
}

function loadParentSkills(excludeId = null) {
    fetch('api/popular_skills.php?action=list&limit=1000')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('parentSkill');
                select.innerHTML = '<option value="">No Parent (Top Level)</option>';

                data.skills.forEach(dept => {
                    if (!excludeId || dept.id != excludeId) {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading parent skills:', error);
        });
}

function saveSkill() {
    const form = document.getElementById('skillForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    const isEdit = document.getElementById('skillId').value;
    const url = isEdit ?
        `api/popular_skills.php?action=update&id=${isEdit}` :
        'api/popular_skills.php?action=create';

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
            bootstrap.Modal.getInstance(document.getElementById('skillModal')).hide();
            loadSkills();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving skill:', error);
        showAlert('Error saving skill. Please try again.', 'danger');
    });
}

function deleteSkill(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        fetch(`api/popular_skills.php?action=delete&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadSkills();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting skill:', error);
            showAlert('Error deleting skill. Please try again.', 'danger');
        });
    }
}

// Pagination
function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const info = document.getElementById('paginationInfo');

    // Update info
    info.textContent = `Showing ${((pagination.currentPage - 1) * pagination.itemsPerPage) + 1}-${Math.min(pagination.currentPage * pagination.itemsPerPage, pagination.totalItems)} of ${pagination.totalItems} skills`;

    if (pagination.totalPages <= 1) {
        container.innerHTML = '';
        return;
    }

    let paginationHTML = '';

    // Previous button
    paginationHTML += `
        <li class="page-item ${pagination.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${pagination.currentPage - 1})" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;

    // Page numbers
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

    // Next button
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
    loadSkills();
}

// Utility functions
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'active': return 'success';
        case 'inactive': return 'secondary';
        case 'suspended': return 'warning';
        case 'dissolved': return 'danger';
        case 'pending': return 'info';
        default: return 'secondary';
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showAlert(message, type) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(alert);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>

<?php include_once '../includes/footer.php'; ?>