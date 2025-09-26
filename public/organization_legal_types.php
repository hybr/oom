<?php
// Additional styles for this page
$additional_styles = '
<style>
    .legal-type-card {
        transition: all 0.2s ease;
        cursor: pointer;
        border-left: 4px solid #dee2e6;
    }

    .legal-type-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .legal-type-card.selected {
        border-left-color: var(--bs-primary);
        background-color: var(--bs-primary-bg-subtle);
    }

    .country-flag {
        font-size: 1.2em;
        margin-right: 0.5rem;
    }

    .liability-badge {
        font-size: 0.75em;
    }

    .details-panel {
        position: sticky;
        top: 20px;
    }

    .capital-amount {
        font-family: "Courier New", monospace;
        font-weight: bold;
    }

    .requirement-check {
        color: var(--bs-success);
    }

    .requirement-cross {
        color: var(--bs-danger);
    }

    .search-highlight {
        background-color: yellow;
        padding: 1px 2px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--bs-primary);
    }

    .view-mode-tabs .nav-link {
        border-radius: 0.25rem;
        margin-right: 0.5rem;
    }

    .view-mode-tabs .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
    }
</style>';

require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏢 Organization Legal Types</h1>
            <p class="text-muted">Manage legal structures and organizational forms across jurisdictions</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newLegalTypeBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#legalTypeModal">
                ➕ Add Legal Type
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" id="totalTypes">0</div>
            <div class="text-muted">Total Types</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="totalCountries">0</div>
            <div class="text-muted">Countries</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="commonTypes">0</div>
            <div class="text-muted">Common Types</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="limitedLiability">0</div>
            <div class="text-muted">Limited Liability</div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search legal types, countries, or descriptions...">
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2">
                <select id="countryFilter" class="form-select">
                    <option value="">All Countries</option>
                </select>
                <select id="categoryFilter" class="form-select">
                    <option value="">All Categories</option>
                    <option value="Corporation">Corporation</option>
                    <option value="Partnership">Partnership</option>
                    <option value="Sole Proprietorship">Sole Proprietorship</option>
                    <option value="Limited Liability">Limited Liability</option>
                    <option value="Non-Profit">Non-Profit</option>
                    <option value="Government">Government</option>
                </select>
            </div>
        </div>
    </div>

    <!-- View Mode Tabs -->
    <ul class="nav view-mode-tabs mb-3">
        <li class="nav-item">
            <button class="nav-link active" id="viewAll" onclick="setViewMode('all')">All Types</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="viewCommon" onclick="setViewMode('common')">Common Only</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="viewByCountry" onclick="setViewMode('country')">By Country</button>
        </li>
    </ul>

    <!-- Legal Types Content -->
    <div class="row">
        <div class="col-lg-8">
            <div id="legalTypesContainer">
                <div class="text-center py-5">
                    <div class="loading-spinner"></div>
                    <p class="mt-2 text-muted">Loading legal types...</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="details-panel">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">📋 Details</h6>
                    </div>
                    <div class="card-body" id="detailsContent">
                        <p class="text-muted text-center">Select a legal type to view details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Legal Type Modal -->
<div class="modal fade" id="legalTypeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Legal Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="legalTypeForm">
                <div class="modal-body">
                    <input type="hidden" id="legalTypeId" name="id">

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Legal Type Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="abbreviation" class="form-label">Abbreviation</label>
                            <input type="text" class="form-control" id="abbreviation" name="abbreviation">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="country_id" class="form-label">Country *</label>
                            <select class="form-select" id="country_id" name="country_id" required>
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Corporation">Corporation</option>
                                <option value="Partnership">Partnership</option>
                                <option value="Sole Proprietorship">Sole Proprietorship</option>
                                <option value="Limited Liability">Limited Liability</option>
                                <option value="Non-Profit">Non-Profit</option>
                                <option value="Government">Government</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="liability_type" class="form-label">Liability Type</label>
                            <select class="form-select" id="liability_type" name="liability_type">
                                <option value="">Select Type</option>
                                <option value="Limited">Limited</option>
                                <option value="Unlimited">Unlimited</option>
                                <option value="Mixed">Mixed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_common" name="is_common">
                                <label class="form-check-label" for="is_common">
                                    Common/Popular Type
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Legal Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Page-specific JavaScript
$additional_scripts = '
<script>
    class LegalTypeManager {
        constructor() {
            this.legalTypes = [];
            this.countries = [];
            this.selectedType = null;
            this.viewMode = "all";
            this.filters = {
                search: "",
                country: "",
                category: ""
            };
            this.init();
        }

        async init() {
            await this.loadCountries();
            await this.loadLegalTypes();
            this.bindEvents();
            this.updateStats();
            window.legalTypeManager = this;
        }

        bindEvents() {
            document.getElementById("searchInput").addEventListener("input", (e) => {
                this.filters.search = e.target.value;
                this.filterAndRender();
            });

            document.getElementById("countryFilter").addEventListener("change", (e) => {
                this.filters.country = e.target.value;
                this.filterAndRender();
            });

            document.getElementById("categoryFilter").addEventListener("change", (e) => {
                this.filters.category = e.target.value;
                this.filterAndRender();
            });
        }

        async loadCountries() {
            try {
                const response = await fetch("/api/entity.php?entity=Country");
                const result = await response.json();
                if (result.success) {
                    this.countries = result.data || [];
                    this.populateCountryDropdowns();
                }
            } catch (error) {
                console.error("Error loading countries:", error);
            }
        }

        populateCountryDropdowns() {
            const selects = ["countryFilter", "country_id"];
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select && selectId === "countryFilter") {
                    select.innerHTML = "<option value=\"\">All Countries</option>";
                } else if (select) {
                    select.innerHTML = "<option value=\"\">Select Country</option>";
                }

                this.countries.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.id;
                    option.textContent = `${country.flag || ""} ${country.name}`;
                    select.appendChild(option);
                });
            });
        }

        async loadLegalTypes() {
            const refreshBtn = document.getElementById("refreshBtn");
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch("/api/entity.php?entity=OrganizationLegalType");
                const result = await response.json();

                if (result.success) {
                    this.legalTypes = result.data || [];
                    this.filterAndRender();
                    this.updateStats();
                    showToast("Legal types loaded successfully", "success");
                } else {
                    throw new Error(result.message || "Failed to load legal types");
                }
            } catch (error) {
                handleApiError(error, "loading legal types");
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        filterAndRender() {
            let filtered = this.legalTypes;

            // Apply filters
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(type =>
                    type.name?.toLowerCase().includes(search) ||
                    type.description?.toLowerCase().includes(search) ||
                    type.category?.toLowerCase().includes(search)
                );
            }

            if (this.filters.country) {
                filtered = filtered.filter(type => type.country_id == this.filters.country);
            }

            if (this.filters.category) {
                filtered = filtered.filter(type => type.category === this.filters.category);
            }

            // Apply view mode filter
            if (this.viewMode === "common") {
                filtered = filtered.filter(type => type.is_common);
            }

            this.renderLegalTypes(filtered);
        }

        renderLegalTypes(types) {
            const container = document.getElementById("legalTypesContainer");

            if (types.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <p class="text-muted">No legal types found matching your criteria.</p>
                    </div>
                `;
                return;
            }

            const html = types.map(type => {
                const country = this.countries.find(c => c.id == type.country_id);
                return `
                    <div class="card legal-type-card mb-3" onclick="window.legalTypeManager.selectType(${type.id})">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1">
                                        <span class="country-flag">${country?.flag || "🏴"}</span>
                                        ${type.name}
                                        ${type.abbreviation ? `<small class="text-muted">(${type.abbreviation})</small>` : ""}
                                    </h6>
                                    <p class="card-text text-muted small mb-2">${type.description || "No description available"}</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-secondary">${type.category}</span>
                                        ${type.liability_type ? `<span class="badge liability-badge bg-info">${type.liability_type} Liability</span>` : ""}
                                        ${type.is_common ? `<span class="badge bg-success">Common</span>` : ""}
                                    </div>
                                </div>
                                <small class="text-muted">${country?.name || "Unknown"}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join("");

            container.innerHTML = html;
        }

        selectType(id) {
            const type = this.legalTypes.find(t => t.id == id);
            if (!type) return;

            this.selectedType = type;

            // Update UI selection
            document.querySelectorAll(".legal-type-card").forEach(card => {
                card.classList.remove("selected");
            });
            event.currentTarget.classList.add("selected");

            this.showTypeDetails(type);
        }

        showTypeDetails(type) {
            const country = this.countries.find(c => c.id == type.country_id);
            const content = document.getElementById("detailsContent");

            content.innerHTML = `
                <h6 class="mb-3">${type.name}</h6>
                <dl class="row small">
                    <dt class="col-5">Country:</dt>
                    <dd class="col-7">${country?.flag || ""} ${country?.name || "Unknown"}</dd>

                    <dt class="col-5">Category:</dt>
                    <dd class="col-7">${type.category}</dd>

                    ${type.liability_type ? `
                        <dt class="col-5">Liability:</dt>
                        <dd class="col-7">${type.liability_type}</dd>
                    ` : ""}

                    <dt class="col-5">Common:</dt>
                    <dd class="col-7">${type.is_common ? "✅ Yes" : "❌ No"}</dd>
                </dl>

                ${type.description ? `
                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="small text-muted mt-1">${type.description}</p>
                    </div>
                ` : ""}

                <div class="mt-3 d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="window.legalTypeManager.editType(${type.id})">
                        ✏️ Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="window.legalTypeManager.deleteType(${type.id})">
                        🗑️ Delete
                    </button>
                </div>
            `;
        }

        updateStats() {
            const total = this.legalTypes.length;
            const countries = new Set(this.legalTypes.map(t => t.country_id)).size;
            const common = this.legalTypes.filter(t => t.is_common).length;
            const limitedLiability = this.legalTypes.filter(t => t.liability_type === "Limited").length;

            document.getElementById("totalTypes").textContent = total;
            document.getElementById("totalCountries").textContent = countries;
            document.getElementById("commonTypes").textContent = common;
            document.getElementById("limitedLiability").textContent = limitedLiability;
        }

        editType(id) {
            const type = this.legalTypes.find(t => t.id == id);
            if (!type) return;

            // Populate form
            document.getElementById("legalTypeId").value = type.id;
            document.getElementById("name").value = type.name || "";
            document.getElementById("abbreviation").value = type.abbreviation || "";
            document.getElementById("country_id").value = type.country_id || "";
            document.getElementById("category").value = type.category || "";
            document.getElementById("description").value = type.description || "";
            document.getElementById("liability_type").value = type.liability_type || "";
            document.getElementById("is_common").checked = type.is_common || false;

            document.getElementById("modalTitle").textContent = "Edit Legal Type";

            const modal = new bootstrap.Modal(document.getElementById("legalTypeModal"));
            modal.show();
        }

        async deleteType(id) {
            if (!confirm("Are you sure you want to delete this legal type?")) return;

            try {
                const response = await fetch(`/api/entity.php?entity=OrganizationLegalType&id=${id}`, {
                    method: "DELETE"
                });
                const result = await response.json();

                if (result.success) {
                    showToast("Legal type deleted successfully", "success");
                    await this.loadLegalTypes();
                    document.getElementById("detailsContent").innerHTML = `
                        <p class="text-muted text-center">Select a legal type to view details</p>
                    `;
                } else {
                    throw new Error(result.message || "Failed to delete legal type");
                }
            } catch (error) {
                handleApiError(error, "deleting legal type");
            }
        }
    }

    function setViewMode(mode) {
        // Update tab appearance
        document.querySelectorAll(".view-mode-tabs .nav-link").forEach(link => {
            link.classList.remove("active");
        });

        if (mode === "all") document.getElementById("viewAll").classList.add("active");
        else if (mode === "common") document.getElementById("viewCommon").classList.add("active");
        else if (mode === "country") document.getElementById("viewByCountry").classList.add("active");

        // Update manager
        if (window.legalTypeManager) {
            window.legalTypeManager.viewMode = mode;
            window.legalTypeManager.filterAndRender();
        }
    }

    // Initialize when DOM is ready
    document.addEventListener("DOMContentLoaded", function() {
        new LegalTypeManager();
    });
</script>';

require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>