<?php
require_once '../includes/header.php';
require_once '../includes/sub_menu.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">💻 Organization Workstations</h1>
            <p class="text-muted">Manage workstations, seating arrangements, and workspace allocations</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button id="newWorkstationBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkstationModal">
                    ➕ Add Workstation
                </button>
                <button id="exportBtn" class="btn btn-outline-secondary">
                    📊 Export
                </button>
                <button id="floorPlanBtn" class="btn btn-outline-info">
                    🗺️ Floor Plan
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
                            <h6 class="card-subtitle mb-1 small">Total Workstations</h6>
                            <h4 class="card-title mb-0" id="totalWorkstations">0</h4>
                        </div>
                        <div class="stats-icon">💻</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Available</h6>
                            <h4 class="card-title mb-0" id="availableWorkstations">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Occupied</h6>
                            <h4 class="card-title mb-0" id="occupiedWorkstations">0</h4>
                        </div>
                        <div class="stats-icon">👤</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Reserved</h6>
                            <h4 class="card-title mb-0" id="reservedWorkstations">0</h4>
                        </div>
                        <div class="stats-icon">🔒</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Under Maintenance</h6>
                            <h4 class="card-title mb-0" id="maintenanceWorkstations">0</h4>
                        </div>
                        <div class="stats-icon">🔧</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Utilization</h6>
                            <h4 class="card-title mb-0" id="utilizationRate">0%</h4>
                        </div>
                        <div class="stats-icon">📊</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card filter-card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="buildingFilter" class="form-label small">Building</label>
                            <select id="buildingFilter" class="form-select">
                                <option value="">All Buildings</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="floorFilter" class="form-label small">Floor</label>
                            <select id="floorFilter" class="form-select">
                                <option value="">All Floors</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="typeFilter" class="form-label small">Type</label>
                            <select id="typeFilter" class="form-select">
                                <option value="">All Types</option>
                                <option value="Cabin">Cabin</option>
                                <option value="Open Desk">Open Desk</option>
                                <option value="Cubicle">Cubicle</option>
                                <option value="Standing Desk">Standing Desk</option>
                                <option value="Hot Desk">Hot Desk</option>
                                <option value="Meeting Booth">Meeting Booth</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="statusFilter" class="form-label small">Status</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Reserved">Reserved</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchInput" class="form-label small">Search</label>
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search workstations...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    🔍
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workstations Table/Cards -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Workstations</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="tableViewBtn">📋 Table</button>
                        <button type="button" class="btn btn-outline-secondary" id="cardViewBtn">📱 Cards</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Desktop Table View -->
                    <div id="tableView" class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Seat Code</th>
                                    <th>Building</th>
                                    <th>Floor</th>
                                    <th>Zone/Area</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Facilities</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="workstationsTableBody">
                                <!-- Dynamic content -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div id="cardView" class="p-3 d-none">
                        <div id="workstationsCardContainer">
                            <!-- Dynamic content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Workstations pagination">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Dynamic pagination -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add Workstation Modal -->
<div class="modal fade" id="addWorkstationModal" tabindex="-1" aria-labelledby="addWorkstationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorkstationModalLabel">➕ Add New Workstation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addWorkstationForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">📍 Basic Information</h6>
                        </div>
                        <div class="col-md-6">
                            <label for="buildingId" class="form-label">Building <span class="text-danger">*</span></label>
                            <select id="buildingId" name="building_id" class="form-select" required>
                                <option value="">Select Building</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="floorNumber" class="form-label">Floor Number <span class="text-danger">*</span></label>
                            <input type="number" id="floorNumber" name="floor_number" class="form-control" required min="1">
                        </div>
                        <div class="col-md-3">
                            <label for="seatNumber" class="form-label">Seat Number</label>
                            <input type="text" id="seatNumber" name="seat_number" class="form-control" placeholder="001">
                        </div>
                        <div class="col-md-6">
                            <label for="seatCode" class="form-label">Seat Code <span class="text-danger">*</span></label>
                            <input type="text" id="seatCode" name="seat_code" class="form-control" placeholder="WS-3F-045" required>
                        </div>
                        <div class="col-md-6">
                            <label for="zoneArea" class="form-label">Zone/Area</label>
                            <input type="text" id="zoneArea" name="zone_area" class="form-control" placeholder="East Wing, Bay A">
                        </div>
                        <div class="col-md-6">
                            <label for="workstationType" class="form-label">Workstation Type</label>
                            <select id="workstationType" name="workstation_type" class="form-select">
                                <option value="Open Desk">Open Desk</option>
                                <option value="Cabin">Cabin</option>
                                <option value="Cubicle">Cubicle</option>
                                <option value="Standing Desk">Standing Desk</option>
                                <option value="Hot Desk">Hot Desk</option>
                                <option value="Meeting Booth">Meeting Booth</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="occupancyStatus" class="form-label">Occupancy Status</label>
                            <select id="occupancyStatus" name="occupancy_status" class="form-select">
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Reserved">Reserved</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>

                        <!-- Physical Specifications -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">📐 Physical Specifications</h6>
                        </div>
                        <div class="col-md-4">
                            <label for="widthCm" class="form-label">Width (cm)</label>
                            <input type="number" id="widthCm" name="width_cm" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label for="depthCm" class="form-label">Depth (cm)</label>
                            <input type="number" id="depthCm" name="depth_cm" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label for="heightCm" class="form-label">Height (cm)</label>
                            <input type="number" id="heightCm" name="height_cm" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label for="coordinateX" class="form-label">Coordinate X</label>
                            <input type="number" id="coordinateX" name="coordinate_x" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label for="coordinateY" class="form-label">Coordinate Y</label>
                            <input type="number" id="coordinateY" name="coordinate_y" class="form-control" step="0.01">
                        </div>

                        <!-- Equipment & Facilities -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">🖥️ Equipment & Facilities</h6>
                        </div>
                        <div class="col-md-4">
                            <label for="powerOutlets" class="form-label">Power Outlets</label>
                            <input type="number" id="powerOutlets" name="power_outlets" class="form-control" value="2" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="networkPorts" class="form-label">Network Ports</label>
                            <input type="number" id="networkPorts" name="network_ports" class="form-control" value="1" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="monitorCount" class="form-label">Monitor Count</label>
                            <input type="number" id="monitorCount" name="monitor_count" class="form-control" value="1" min="0">
                        </div>
                        <div class="col-md-6">
                            <label for="phoneExtension" class="form-label">Phone Extension</label>
                            <input type="text" id="phoneExtension" name="phone_extension" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="monitorSize" class="form-label">Monitor Size</label>
                            <input type="text" id="monitorSize" name="monitor_size" class="form-control" placeholder="24 inch">
                        </div>

                        <!-- Facilities Features -->
                        <div class="col-12">
                            <label for="facilitiesFeatures" class="form-label">Facilities & Features</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dualMonitor" name="facilities[]" value="Dual Monitor">
                                        <label class="form-check-label" for="dualMonitor">Dual Monitor</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dockingStation" name="facilities[]" value="Docking Station">
                                        <label class="form-check-label" for="dockingStation">Docking Station</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="phone" name="facilities[]" value="Phone">
                                        <label class="form-check-label" for="phone">Phone</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="keyboardTray" name="facilities[]" value="Keyboard Tray">
                                        <label class="form-check-label" for="keyboardTray">Keyboard Tray</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cpuHolder" name="facilities[]" value="CPU Holder">
                                        <label class="form-check-label" for="cpuHolder">CPU Holder</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cableManagement" name="facilities[]" value="Cable Management">
                                        <label class="form-check-label" for="cableManagement">Cable Management</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea id="remarks" name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">💾 Save Workstation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewWorkstationModal" tabindex="-1" aria-labelledby="viewWorkstationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewWorkstationModalLabel">👁️ Workstation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="workstationDetailsContent">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editWorkstationBtn">✏️ Edit</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let itemsPerPage = 10;
    let currentFilters = {};

    // Initialize the page
    init();

    function init() {
        loadWorkstations();
        loadBuildings();
        setupEventListeners();
    }

    function setupEventListeners() {
        // Filter changes
        document.getElementById('buildingFilter').addEventListener('change', applyFilters);
        document.getElementById('floorFilter').addEventListener('change', applyFilters);
        document.getElementById('typeFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('searchBtn').addEventListener('click', applyFilters);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') applyFilters();
        });

        // View toggles
        document.getElementById('tableViewBtn').addEventListener('click', function() {
            showTableView();
        });
        document.getElementById('cardViewBtn').addEventListener('click', function() {
            showCardView();
        });

        // Form submission
        document.getElementById('addWorkstationForm').addEventListener('submit', handleAddWorkstation);

        // Building change updates floors
        document.getElementById('buildingId').addEventListener('change', updateFloors);
        document.getElementById('buildingFilter').addEventListener('change', updateFloorFilter);
    }

    async function loadWorkstations() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                limit: itemsPerPage,
                ...currentFilters
            });

            const response = await fetch(`api/organization_workstations.php?${params}`);
            const data = await response.json();

            if (data.success) {
                updateStatistics(data.statistics);
                displayWorkstations(data.workstations);
                updatePagination(data.pagination);
            } else {
                showAlert('Error loading workstations: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Failed to load workstations', 'danger');
        }
    }

    async function loadBuildings() {
        try {
            const response = await fetch('api/organization_buildings.php?action=list');
            const data = await response.json();

            if (data.success) {
                const buildingSelect = document.getElementById('buildingId');
                const buildingFilter = document.getElementById('buildingFilter');

                buildingSelect.innerHTML = '<option value="">Select Building</option>';
                buildingFilter.innerHTML = '<option value="">All Buildings</option>';

                data.buildings.forEach(building => {
                    const option = `<option value="${building.id}">${building.name}</option>`;
                    buildingSelect.innerHTML += option;
                    buildingFilter.innerHTML += option;
                });
            }
        } catch (error) {
            console.error('Error loading buildings:', error);
        }
    }

    function updateStatistics(stats) {
        document.getElementById('totalWorkstations').textContent = stats.total || 0;
        document.getElementById('availableWorkstations').textContent = stats.available || 0;
        document.getElementById('occupiedWorkstations').textContent = stats.occupied || 0;
        document.getElementById('reservedWorkstations').textContent = stats.reserved || 0;
        document.getElementById('maintenanceWorkstations').textContent = stats.maintenance || 0;

        const utilizationRate = stats.total > 0 ? Math.round((stats.occupied / stats.total) * 100) : 0;
        document.getElementById('utilizationRate').textContent = utilizationRate + '%';
    }

    function displayWorkstations(workstations) {
        const tableBody = document.getElementById('workstationsTableBody');
        const cardContainer = document.getElementById('workstationsCardContainer');

        // Clear existing content
        tableBody.innerHTML = '';
        cardContainer.innerHTML = '';

        workstations.forEach(workstation => {
            // Table row
            const row = createTableRow(workstation);
            tableBody.appendChild(row);

            // Card
            const card = createCard(workstation);
            cardContainer.appendChild(card);
        });
    }

    function createTableRow(workstation) {
        const row = document.createElement('tr');
        const facilitiesArray = JSON.parse(workstation.facilities_features || '[]');
        const facilitiesBadges = facilitiesArray.slice(0, 3).map(f =>
            `<span class="badge bg-secondary me-1">${f}</span>`
        ).join('');
        const moreCount = facilitiesArray.length > 3 ? `<small>+${facilitiesArray.length - 3} more</small>` : '';

        row.innerHTML = `
            <td><strong>${workstation.seat_code}</strong></td>
            <td>${workstation.building_name || 'N/A'}</td>
            <td>Floor ${workstation.floor_number}</td>
            <td>${workstation.zone_area || '-'}</td>
            <td><span class="badge bg-info">${workstation.workstation_type}</span></td>
            <td>${getStatusBadge(workstation.occupancy_status)}</td>
            <td>${workstation.assigned_employee_name || '<em>Unassigned</em>'}</td>
            <td>${facilitiesBadges}${moreCount}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="viewWorkstation(${workstation.id})" title="View Details">👁️</button>
                    <button class="btn btn-outline-warning" onclick="editWorkstation(${workstation.id})" title="Edit">✏️</button>
                    <button class="btn btn-outline-info" onclick="manageWorkstation(${workstation.id})" title="Manage">⚙️</button>
                </div>
            </td>
        `;
        return row;
    }

    function createCard(workstation) {
        const card = document.createElement('div');
        card.className = 'col-12 col-md-6 col-lg-4 mb-3';

        const facilitiesArray = JSON.parse(workstation.facilities_features || '[]');
        const facilitiesList = facilitiesArray.slice(0, 4).map(f =>
            `<span class="badge bg-secondary me-1 mb-1">${f}</span>`
        ).join('');

        card.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">${workstation.seat_code}</h6>
                        ${getStatusBadge(workstation.occupancy_status)}
                    </div>
                    <p class="card-text small text-muted mb-2">
                        <i class="fas fa-building"></i> ${workstation.building_name || 'N/A'}<br>
                        <i class="fas fa-layer-group"></i> Floor ${workstation.floor_number}
                        ${workstation.zone_area ? '<br><i class="fas fa-map-marker"></i> ' + workstation.zone_area : ''}
                    </p>
                    <div class="mb-2">
                        <span class="badge bg-info">${workstation.workstation_type}</span>
                    </div>
                    ${workstation.assigned_employee_name ?
                        `<p class="small mb-2"><strong>Assigned to:</strong> ${workstation.assigned_employee_name}</p>` :
                        `<p class="small mb-2 text-muted"><em>Unassigned</em></p>`
                    }
                    <div class="mb-3">
                        ${facilitiesList}
                        ${facilitiesArray.length > 4 ? `<small class="text-muted">+${facilitiesArray.length - 4} more</small>` : ''}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary btn-sm" onclick="viewWorkstation(${workstation.id})">👁️ View</button>
                        <button class="btn btn-outline-warning btn-sm" onclick="editWorkstation(${workstation.id})">✏️ Edit</button>
                        <button class="btn btn-outline-info btn-sm" onclick="manageWorkstation(${workstation.id})">⚙️ Manage</button>
                    </div>
                </div>
            </div>
        `;
        return card;
    }

    function getStatusBadge(status) {
        const badges = {
            'Available': '<span class="badge bg-success">✅ Available</span>',
            'Occupied': '<span class="badge bg-primary">👤 Occupied</span>',
            'Reserved': '<span class="badge bg-warning">🔒 Reserved</span>',
            'Under Maintenance': '<span class="badge bg-danger">🔧 Maintenance</span>'
        };
        return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
    }

    function showTableView() {
        document.getElementById('tableView').classList.remove('d-none');
        document.getElementById('cardView').classList.add('d-none');
        document.getElementById('tableViewBtn').classList.add('active');
        document.getElementById('cardViewBtn').classList.remove('active');
    }

    function showCardView() {
        document.getElementById('tableView').classList.add('d-none');
        document.getElementById('cardView').classList.remove('d-none');
        document.getElementById('tableViewBtn').classList.remove('active');
        document.getElementById('cardViewBtn').classList.add('active');
    }

    function applyFilters() {
        currentFilters = {
            building_id: document.getElementById('buildingFilter').value,
            floor_number: document.getElementById('floorFilter').value,
            workstation_type: document.getElementById('typeFilter').value,
            occupancy_status: document.getElementById('statusFilter').value,
            search: document.getElementById('searchInput').value
        };

        // Remove empty filters
        Object.keys(currentFilters).forEach(key => {
            if (!currentFilters[key]) delete currentFilters[key];
        });

        currentPage = 1;
        loadWorkstations();
    }

    async function handleAddWorkstation(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const facilities = [];
        document.querySelectorAll('input[name="facilities[]"]:checked').forEach(cb => {
            facilities.push(cb.value);
        });
        formData.set('facilities_features', JSON.stringify(facilities));

        try {
            const response = await fetch('api/organization_workstations.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showAlert('Workstation added successfully!', 'success');
                document.getElementById('addWorkstationForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('addWorkstationModal')).hide();
                loadWorkstations();
            } else {
                showAlert('Error: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Failed to add workstation', 'danger');
        }
    }

    // Global functions for button actions
    window.viewWorkstation = async function(id) {
        try {
            const response = await fetch(`api/organization_workstations.php?action=get&id=${id}`);
            const data = await response.json();

            if (data.success) {
                displayWorkstationDetails(data.workstation);
                const modal = new bootstrap.Modal(document.getElementById('viewWorkstationModal'));
                modal.show();
            } else {
                showAlert('Error loading workstation details: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Failed to load workstation details', 'danger');
        }
    };

    window.editWorkstation = function(id) {
        // Implementation for edit functionality
        console.log('Edit workstation:', id);
    };

    window.manageWorkstation = function(id) {
        // Implementation for manage functionality (assign/unassign, maintenance, etc.)
        console.log('Manage workstation:', id);
    };

    function displayWorkstationDetails(workstation) {
        const content = document.getElementById('workstationDetailsContent');
        const facilities = JSON.parse(workstation.facilities_features || '[]');
        const facilitiesList = facilities.map(f => `<span class="badge bg-secondary me-1 mb-1">${f}</span>`).join('');

        content.innerHTML = `
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="border-bottom pb-2">📍 Basic Information</h6>
                </div>
                <div class="col-md-6">
                    <strong>Seat Code:</strong><br>
                    <span class="text-primary">${workstation.seat_code}</span>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong><br>
                    ${getStatusBadge(workstation.occupancy_status)}
                </div>
                <div class="col-md-6">
                    <strong>Building:</strong><br>
                    ${workstation.building_name || 'N/A'}
                </div>
                <div class="col-md-3">
                    <strong>Floor:</strong><br>
                    Floor ${workstation.floor_number}
                </div>
                <div class="col-md-3">
                    <strong>Zone/Area:</strong><br>
                    ${workstation.zone_area || '-'}
                </div>
                <div class="col-md-6">
                    <strong>Workstation Type:</strong><br>
                    <span class="badge bg-info">${workstation.workstation_type}</span>
                </div>
                <div class="col-md-6">
                    <strong>Assigned Employee:</strong><br>
                    ${workstation.assigned_employee_name || '<em>Unassigned</em>'}
                </div>

                <div class="col-12 mt-4">
                    <h6 class="border-bottom pb-2">📐 Physical Specifications</h6>
                </div>
                <div class="col-md-4">
                    <strong>Dimensions:</strong><br>
                    ${workstation.width_cm || '-'} × ${workstation.depth_cm || '-'} × ${workstation.height_cm || '-'} cm
                </div>
                <div class="col-md-4">
                    <strong>Coordinates:</strong><br>
                    (${workstation.coordinate_x || '-'}, ${workstation.coordinate_y || '-'})
                </div>
                <div class="col-md-4">
                    <strong>Power/Network:</strong><br>
                    ${workstation.power_outlets || 0} outlets, ${workstation.network_ports || 0} ports
                </div>

                <div class="col-12 mt-4">
                    <h6 class="border-bottom pb-2">🖥️ Equipment & Facilities</h6>
                </div>
                <div class="col-12">
                    <strong>Facilities:</strong><br>
                    ${facilitiesList || '<em>No facilities specified</em>'}
                </div>
                <div class="col-md-6">
                    <strong>Monitor Count:</strong><br>
                    ${workstation.monitor_count || 0}
                </div>
                <div class="col-md-6">
                    <strong>Phone Extension:</strong><br>
                    ${workstation.phone_extension || '-'}
                </div>

                ${workstation.remarks ? `
                <div class="col-12 mt-4">
                    <h6 class="border-bottom pb-2">📝 Notes</h6>
                </div>
                <div class="col-12">
                    <strong>Remarks:</strong><br>
                    ${workstation.remarks}
                </div>
                ` : ''}

                <div class="col-12 mt-4">
                    <h6 class="border-bottom pb-2">📅 Audit Information</h6>
                </div>
                <div class="col-md-6">
                    <strong>Created:</strong><br>
                    ${new Date(workstation.created_at).toLocaleString()}
                </div>
                <div class="col-md-6">
                    <strong>Last Updated:</strong><br>
                    ${new Date(workstation.updated_at).toLocaleString()}
                </div>
            </div>
        `;
    }

    function updatePagination(pagination) {
        const paginationEl = document.getElementById('pagination');
        paginationEl.innerHTML = '';

        if (pagination.totalPages <= 1) return;

        // Previous button
        if (pagination.currentPage > 1) {
            paginationEl.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${pagination.currentPage - 1})">Previous</a>
                </li>
            `;
        }

        // Page numbers
        for (let i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++) {
            paginationEl.innerHTML += `
                <li class="page-item ${i === pagination.currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        }

        // Next button
        if (pagination.currentPage < pagination.totalPages) {
            paginationEl.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${pagination.currentPage + 1})">Next</a>
                </li>
            `;
        }
    }

    window.changePage = function(page) {
        currentPage = page;
        loadWorkstations();
    };

    function updateFloors() {
        const buildingId = document.getElementById('buildingId').value;
        if (!buildingId) return;

        // Load floors for selected building
        // This would typically make an API call to get floors for the building
        console.log('Update floors for building:', buildingId);
    }

    function updateFloorFilter() {
        const buildingId = document.getElementById('buildingFilter').value;
        const floorFilter = document.getElementById('floorFilter');

        floorFilter.innerHTML = '<option value="">All Floors</option>';

        if (!buildingId) return;

        // Load floors for selected building
        // This would typically make an API call to get floors for the building
        console.log('Update floor filter for building:', buildingId);
    }

    function showAlert(message, type = 'info') {
        // Create and show Bootstrap alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>