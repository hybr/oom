<?php
require_once '../includes/header.php';
?>

<style>
    :root {
        --tree-indent: 20px;
    }

    .category-tree {
        max-height: 600px;
        overflow-y: auto;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.375rem;
        padding: 1rem;
    }

    .category-item {
        padding: 0.5rem;
        border-radius: 0.375rem;
        margin: 0.25rem 0;
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .category-item:hover {
        background-color: var(--bs-light);
        border-left-color: var(--bs-primary);
    }

    .category-item.selected {
        background-color: var(--bs-primary-bg-subtle);
        border-left-color: var(--bs-primary);
    }

    .category-level-0 { margin-left: 0; }
    .category-level-1 { margin-left: calc(var(--tree-indent) * 1); }
    .category-level-2 { margin-left: calc(var(--tree-indent) * 2); }
    .category-level-3 { margin-left: calc(var(--tree-indent) * 3); }
    .category-level-4 { margin-left: calc(var(--tree-indent) * 4); }
    .category-level-5 { margin-left: calc(var(--tree-indent) * 5); }

    .category-icon {
        font-size: 1.2em;
        margin-right: 0.5rem;
    }

    .category-badge {
        font-size: 0.75em;
    }

    .category-stats {
        font-size: 0.875em;
        opacity: 0.7;
    }

    .search-highlight {
        background-color: yellow;
        padding: 0 2px;
        border-radius: 2px;
    }

    .category-details {
        position: sticky;
        top: 20px;
    }

    .breadcrumb-item {
        cursor: pointer;
    }

    .breadcrumb-item:hover {
        text-decoration: underline;
    }

    .action-btn {
        margin: 0.25rem;
    }

    .featured-badge {
        background: linear-gradient(45deg, #ff6b6b, #ffd93d);
        color: white;
        font-weight: bold;
    }

    .tree-toggle {
        cursor: pointer;
        width: 20px;
        text-align: center;
        display: inline-block;
    }

    .tree-toggle:hover {
        color: var(--bs-primary);
    }

    .collapsed .children {
        display: none;
    }

    .form-floating .form-select {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    [data-bs-theme="dark"] .category-item:hover {
        background-color: var(--bs-dark);
    }

    [data-bs-theme="dark"] .search-highlight {
        background-color: #ffc107;
        color: #000;
    }

    @media (max-width: 768px) {
        .category-tree {
            max-height: 400px;
        }

        .category-details {
            position: static;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏭 Industry Categories</h1>
            <p class="text-muted">Manage industry classifications and categories</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newCategoryBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                ➕ Add Category
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Left Panel - Category Tree -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                🏭 Industry Categories
                                <span class="badge bg-primary ms-2" id="totalCount">0</span>
                            </h5>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary active" id="treeViewBtn">
                                    📋 Tree
                                </button>
                                <button class="btn btn-outline-primary" id="listViewBtn">
                                    📋 List
                                </button>
                                <button class="btn btn-outline-primary" id="featuredViewBtn">
                                    ⭐ Featured
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">🔍</span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search categories...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="levelFilter">
                                <option value="">All Levels</option>
                                <option value="0">Root Categories</option>
                                <option value="1">Level 1</option>
                                <option value="2">Level 2</option>
                                <option value="3">Level 3</option>
                                <option value="4">Level 4</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="category-tree" id="categoryTree">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading categories...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Category Details -->
        <div class="col-md-4">
            <div class="card category-details">
                <div class="card-header">
                    <h6 class="mb-0">📋 Category Details</h6>
                </div>
                <div class="card-body" id="categoryDetails">
                    <div class="text-center text-muted py-4">
                        <p>Select a category from the tree to view details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <input type="hidden" id="categoryId">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="categoryName" required>
                                <label for="categoryName">Category Name *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="categoryIcon" placeholder="🏭">
                                <label for="categoryIcon">Icon</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="categoryDescription" style="height: 80px"></textarea>
                        <label for="categoryDescription">Description</label>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="categoryParent">
                                    <option value="">No Parent (Root Category)</option>
                                </select>
                                <label for="categoryParent">Parent Category</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="color" class="form-control form-control-color" id="categoryColor" value="#6c757d">
                                <label for="categoryColor">Color</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="categoryNaics">
                                <label for="categoryNaics">NAICS Code</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="categorySic">
                                <label for="categorySic">SIC Code</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="categorySortOrder" value="0">
                                <label for="categorySortOrder">Sort Order</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="categoryActive" checked>
                                <label class="form-check-label" for="categoryActive">Active</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="categoryFeatured">
                                <label class="form-check-label" for="categoryFeatured">Featured</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" id="saveSpinner"></span>
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Page-specific JavaScript for Industry Categories would go here
document.addEventListener('DOMContentLoaded', function() {
    // Initialize category management functionality
    showToast('Industry Categories page loaded', 'info');
});
</script>

<?php
require_once '../includes/footer.php';
?>