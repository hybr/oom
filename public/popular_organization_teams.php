<?php
$pageTitle = "Popular Organization Teams";
$pageDescription = "Manage popular organization teams";

include_once '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">👥 Popular Organization Teams</h1>
                    <p class="text-muted mb-0">Manage and organize teams across departments</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="showFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddTeamModal()">
                        <i class="fas fa-plus"></i> Add Team
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
                            <i class="fas fa-users text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Teams</h6>
                            <h4 class="card-title mb-0" id="totalTeams">0</h4>
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
                            <h4 class="card-title mb-0" id="activeTeams">0</h4>
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
                            <h4 class="card-title mb-0" id="functionalTeams">0</h4>
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
                            <i class="fas fa-user-friends text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Members</h6>
                            <h4 class="card-title mb-0" id="totalMembers">0</h4>
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
                            <label for="departmentFilter" class="form-label">Department</label>
                            <select class="form-select" id="departmentFilter">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="teamTypeFilter" class="form-label">Team Type</label>
                            <select class="form-select" id="teamTypeFilter">
                                <option value="">All Types</option>
                                <option value="Functional">Functional</option>
                                <option value="Cross-functional">Cross-functional</option>
                                <option value="Project">Project</option>
                                <option value="Product">Product</option>
                                <option value="Scrum">Scrum</option>
                                <option value="DevOps">DevOps</option>
                                <option value="Support">Support</option>
                                <option value="Research">Research</option>
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
                                <option value="Planning">Planning</option>
                                <option value="Forming">Forming</option>
                                <option value="Performing">Performing</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search teams...">
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
                                <th class="border-0 ps-3">Team</th>
                                <th class="border-0">Department</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Members</th>
                                <th class="border-0">Parent</th>
                                <th class="border-0 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="teamsTableBody">
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
        <div id="teamsCardContainer">
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
                <div id="teamTreeContainer">
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
            <nav aria-label="Team pagination">
                <ul class="pagination justify-content-center" id="paginationContainer">
                    <!-- Pagination will be generated here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add/Edit Team Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-labelledby="teamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamModalLabel">Add Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="teamForm">
                    <input type="hidden" id="teamId" name="id">

                    <!-- Basic Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="teamName" class="form-label">Team Name *</label>
                            <input type="text" class="form-control" id="teamName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="teamCode" class="form-label">Code</label>
                            <input type="text" class="form-control" id="teamCode" name="code">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="departmentId" class="form-label">Department *</label>
                            <select class="form-select" id="departmentId" name="popular_organization_department_id" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="teamType" class="form-label">Type *</label>
                            <select class="form-select" id="teamType" name="team_type" required>
                                <option value="">Select Type</option>
                                <option value="Functional">Functional</option>
                                <option value="Cross-functional">Cross-functional</option>
                                <option value="Project">Project</option>
                                <option value="Product">Product</option>
                                <option value="Scrum">Scrum</option>
                                <option value="DevOps">DevOps</option>
                                <option value="Support">Support</option>
                                <option value="Research">Research</option>
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
                                <option value="Planning">Planning</option>
                                <option value="Forming">Forming</option>
                                <option value="Performing">Performing</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="parentTeam" class="form-label">Parent Team</label>
                            <select class="form-select" id="parentTeam" name="parent_team_id">
                                <option value="">No Parent (Top Level)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="teamDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="teamDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="currentSize" class="form-label">Current Size</label>
                            <input type="number" class="form-control" id="currentSize" name="current_size" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="targetSize" class="form-label">Target Size</label>
                            <input type="number" class="form-control" id="targetSize" name="target_size" min="0">
                        </div>
                        <div class="col-md-4">
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
                <button type="button" class="btn btn-primary" onclick="saveTeam()">Save Team</button>
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
let lastLoadedTeams = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadDepartmentsForFilters();
    loadTeams();
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
    displayTeams(lastLoadedTeams || []);
}

function switchToCardView() {
    currentView = 'card';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.remove('d-none');
    document.getElementById('treeView').classList.add('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.add('active');
    document.getElementById('treeViewBtn').classList.remove('active');
    displayTeams(lastLoadedTeams || []);
}

function switchToTreeView() {
    currentView = 'tree';
    document.getElementById('tableView').classList.add('d-none');
    document.getElementById('cardView').classList.add('d-none');
    document.getElementById('treeView').classList.remove('d-none');
    document.getElementById('tableViewBtn').classList.remove('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    document.getElementById('treeViewBtn').classList.add('active');
    displayTreeView(lastLoadedTeams || []);
}

// Load departments for filters and form
async function loadDepartmentsForFilters() {
    try {
        const response = await fetch('api/popular_organization_departments.php?action=list&limit=1000');
        const data = await response.json();

        if (data.success) {
            const filterSelect = document.getElementById('departmentFilter');
            const formSelect = document.getElementById('departmentId');

            data.departments.forEach(dept => {
                const option1 = document.createElement('option');
                option1.value = dept.id;
                option1.textContent = dept.name;
                filterSelect.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = dept.id;
                option2.textContent = dept.name;
                formSelect.appendChild(option2);
            });
        }
    } catch (error) {
        console.error('Error loading departments:', error);
    }
}

// Load teams
async function loadTeams() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            ...currentFilters
        });

        const response = await fetch(`api/popular_organization_teams.php?${params}`);
        const data = await response.json();

        if (data.success) {
            updateStatistics(data.statistics);
            lastLoadedTeams = data.teams;
            displayTeams(data.teams);
            updatePagination(data.pagination);
        } else {
            console.error('Failed to load teams:', data.message);
            showAlert('Failed to load teams: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error loading teams:', error);
        showAlert('Error loading teams. Please try again.', 'danger');
    }
}

// Update statistics
function updateStatistics(stats) {
    document.getElementById('totalTeams').textContent = stats.total || 0;
    document.getElementById('activeTeams').textContent = stats.active || 0;
    document.getElementById('functionalTeams').textContent = stats.functional || 0;
    document.getElementById('totalMembers').textContent = stats.totalMembers || 0;
}

// Display teams based on current view
function displayTeams(teams) {
    if (currentView === 'table') {
        displayTableView(teams);
    } else if (currentView === 'card') {
        displayCardView(teams);
    }
}

// Display table view
function displayTableView(teams) {
    const tbody = document.getElementById('teamsTableBody');

    if (teams.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No teams found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = teams.map(team => `
        <tr>
            <td class="ps-3">
                <div>
                    <strong>${escapeHtml(team.name)}</strong>
                    ${team.code ? `<br><small class="text-muted">${escapeHtml(team.code)}</small>` : ''}
                </div>
            </td>
            <td>
                <span class="badge bg-secondary">${escapeHtml(team.department_name || 'N/A')}</span>
            </td>
            <td>
                <span class="badge bg-info">${escapeHtml(team.team_type || 'N/A')}</span>
            </td>
            <td>
                <span class="badge bg-${getStatusColor(team.operational_status)}">${escapeHtml(team.operational_status || 'N/A')}</span>
            </td>
            <td>${team.current_size || 0}</td>
            <td>
                ${team.parent_team_name ? escapeHtml(team.parent_team_name) : '<span class="text-muted">Top Level</span>'}
            </td>
            <td class="text-end pe-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editTeam(${team.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteTeam(${team.id}, '${escapeHtml(team.name)}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Display card view
function displayCardView(teams) {
    const container = document.getElementById('teamsCardContainer');

    if (teams.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No teams found
            </div>
        `;
        return;
    }

    container.innerHTML = teams.map(team => `
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">${escapeHtml(team.name)}</h6>
                    <span class="badge bg-${getStatusColor(team.operational_status)}">${escapeHtml(team.operational_status || 'N/A')}</span>
                </div>
                ${team.code ? `<p class="card-text text-muted small mb-2">${escapeHtml(team.code)}</p>` : ''}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Department:</small><br>
                        <span class="badge bg-secondary">${escapeHtml(team.department_name || 'N/A')}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Type:</small><br>
                        <span class="badge bg-info">${escapeHtml(team.team_type || 'N/A')}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Members:</small><br>
                        <strong>${team.current_size || 0}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Parent:</small><br>
                        ${team.parent_team_name ? escapeHtml(team.parent_team_name) : '<span class="text-muted">Top Level</span>'}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="editTeam(${team.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteTeam(${team.id}, '${escapeHtml(team.name)}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Display tree view
function displayTreeView(teams) {
    const container = document.getElementById('teamTreeContainer');

    if (teams.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                No teams found
            </div>
        `;
        return;
    }

    // Build tree structure
    const tree = buildTree(teams);
    container.innerHTML = renderTree(tree);
}

// Build tree structure from flat array
function buildTree(teams) {
    const map = {};
    const roots = [];

    // Create map
    teams.forEach(team => {
        map[team.id] = { ...team, children: [] };
    });

    // Build tree
    teams.forEach(team => {
        if (team.parent_team_id && map[team.parent_team_id]) {
            map[team.parent_team_id].children.push(map[team.id]);
        } else {
            roots.push(map[team.id]);
        }
    });

    return roots;
}

// Render tree HTML
function renderTree(nodes, level = 0) {
    return nodes.map(node => `
        <div class="tree-node" style="margin-left: ${level * 20}px;">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-users text-primary me-2"></i>
                <strong>${escapeHtml(node.name)}</strong>
                <span class="badge bg-${getStatusColor(node.operational_status)} ms-2">${escapeHtml(node.operational_status || 'N/A')}</span>
                ${node.department_name ? `<span class="badge bg-secondary ms-2">${escapeHtml(node.department_name)}</span>` : ''}
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
        department_id: document.getElementById('departmentFilter').value,
        team_type: document.getElementById('teamTypeFilter').value,
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
    loadTeams();
}

function clearFilters() {
    document.getElementById('departmentFilter').value = '';
    document.getElementById('teamTypeFilter').value = '';
    document.getElementById('operationalStatusFilter').value = '';
    document.getElementById('searchFilter').value = '';
    currentFilters = {};
    currentPage = 1;
    loadTeams();
}

// Modal functions
function showAddTeamModal() {
    document.getElementById('teamModalLabel').textContent = 'Add Team';
    document.getElementById('teamForm').reset();
    document.getElementById('teamId').value = '';
    loadParentTeams();
    new bootstrap.Modal(document.getElementById('teamModal')).show();
}

function editTeam(id) {
    // Load team data and populate form
    fetch(`api/popular_organization_teams.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const team = data.team;
                document.getElementById('teamModalLabel').textContent = 'Edit Team';
                document.getElementById('teamId').value = team.id;
                document.getElementById('teamName').value = team.name || '';
                document.getElementById('teamCode').value = team.code || '';
                document.getElementById('departmentId').value = team.popular_organization_department_id || '';
                document.getElementById('teamType').value = team.team_type || '';
                document.getElementById('operationalStatus').value = team.operational_status || '';
                document.getElementById('parentTeam').value = team.parent_team_id || '';
                document.getElementById('teamDescription').value = team.description || '';
                document.getElementById('currentSize').value = team.current_size || '';
                document.getElementById('targetSize').value = team.target_size || '';
                document.getElementById('priorityLevel').value = team.priority_level || '';

                loadParentTeams(team.id);
                new bootstrap.Modal(document.getElementById('teamModal')).show();
            } else {
                showAlert('Failed to load team: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error loading team:', error);
            showAlert('Error loading team. Please try again.', 'danger');
        });
}

function loadParentTeams(excludeId = null) {
    fetch('api/popular_organization_teams.php?action=list&limit=1000')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('parentTeam');
                select.innerHTML = '<option value="">No Parent (Top Level)</option>';

                data.teams.forEach(team => {
                    if (!excludeId || team.id != excludeId) {
                        const option = document.createElement('option');
                        option.value = team.id;
                        option.textContent = team.name + (team.department_name ? ' (' + team.department_name + ')' : '');
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading parent teams:', error);
        });
}

function saveTeam() {
    const form = document.getElementById('teamForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    const isEdit = document.getElementById('teamId').value;
    const url = isEdit ?
        `api/popular_organization_teams.php?action=update&id=${isEdit}` :
        'api/popular_organization_teams.php?action=create';

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
            bootstrap.Modal.getInstance(document.getElementById('teamModal')).hide();
            loadTeams();
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error saving team:', error);
        showAlert('Error saving team. Please try again.', 'danger');
    });
}

function deleteTeam(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        fetch(`api/popular_organization_teams.php?action=delete&id=${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadTeams();
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting team:', error);
            showAlert('Error deleting team. Please try again.', 'danger');
        });
    }
}

// Pagination
function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const info = document.getElementById('paginationInfo');

    // Update info
    info.textContent = `Showing ${((pagination.currentPage - 1) * pagination.itemsPerPage) + 1}-${Math.min(pagination.currentPage * pagination.itemsPerPage, pagination.totalItems)} of ${pagination.totalItems} teams`;

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
    loadTeams();
}

// Utility functions
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'active': return 'success';
        case 'inactive': return 'secondary';
        case 'suspended': return 'warning';
        case 'dissolved': return 'danger';
        case 'pending': return 'info';
        case 'planning': return 'info';
        case 'forming': return 'primary';
        case 'performing': return 'success';
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