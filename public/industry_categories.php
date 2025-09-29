<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🏭 Industry Category Management</h1>
            <p class="text-muted">Manage industry categories with hierarchical tree structure</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newCategoryBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                ➕ Add Category
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Categories</h6>
                            <h4 class="card-title mb-0" id="totalCategories">0</h4>
                        </div>
                        <div class="stats-icon">🏭</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Root Categories</h6>
                            <h4 class="card-title mb-0" id="rootCategories">0</h4>
                        </div>
                        <div class="stats-icon">🌳</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Active</h6>
                            <h4 class="card-title mb-0" id="activeCategories">0</h4>
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
                            <h6 class="card-subtitle mb-1 small">Featured</h6>
                            <h4 class="card-title mb-0" id="featuredCategories">0</h4>
                        </div>
                        <div class="stats-icon">⭐</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Max Level</h6>
                            <h4 class="card-title mb-0" id="maxLevel">0</h4>
                        </div>
                        <div class="stats-icon">📊</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Leaf Categories</h6>
                            <h4 class="card-title mb-0" id="leafCategories">0</h4>
                        </div>
                        <div class="stats-icon">🍃</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="row mb-3">
        <div class="col-lg-6 col-12 mb-2 mb-lg-0">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchCategories" class="form-control" placeholder="Search categories, codes, descriptions...">
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="d-flex gap-2 flex-wrap">
                <select id="statusFilter" class="form-select" style="max-width: 150px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="levelFilter" class="form-select" style="max-width: 150px;">
                    <option value="">All Levels</option>
                </select>
                <select id="featuredFilter" class="form-select" style="max-width: 150px;">
                    <option value="">All Types</option>
                    <option value="featured">Featured</option>
                    <option value="regular">Regular</option>
                </select>
                <div class="btn-group ms-auto" role="group">
                    <input type="radio" class="btn-check" name="viewMode" id="treeView" autocomplete="off" checked>
                    <label class="btn btn-outline-primary btn-sm" for="treeView">🌳 Tree</label>

                    <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                    <label class="btn btn-outline-primary btn-sm" for="listView">📋 List</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    <div class="row mb-3" id="breadcrumbRow" style="display: none;">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" id="categoryBreadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="industryManager.showRootLevel()">🏠 Root</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Categories Display -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Industry Categories</h5>
                    <small class="text-muted" id="categoryCount">0 categories</small>
                </div>
                <div class="card-body">
                    <div id="categoriesDisplay">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading categories...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Industry Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select class="form-select" id="parent_id" name="parent_id">
                                    <option value="">Root Level Category</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="naics_code" class="form-label">NAICS Code</label>
                                <input type="text" class="form-control" id="naics_code" name="naics_code" placeholder="e.g., 54">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sic_code" class="form-label">SIC Code</label>
                                <input type="text" class="form-control" id="sic_code" name="sic_code" placeholder="e.g., 73">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="isic_code" class="form-label">ISIC Code</label>
                                <input type="text" class="form-control" id="isic_code" name="isic_code" placeholder="e.g., J">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon</label>
                                <input type="text" class="form-control" id="icon" name="icon" placeholder="🏭">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="color" name="color" value="#6c757d">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">
                                    Featured
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Industry Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm">
                <input type="hidden" id="editCategoryId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editParent_id" class="form-label">Parent Category</label>
                                <select class="form-select" id="editParent_id" name="parent_id">
                                    <option value="">Root Level Category</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editNaics_code" class="form-label">NAICS Code</label>
                                <input type="text" class="form-control" id="editNaics_code" name="naics_code">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editSic_code" class="form-label">SIC Code</label>
                                <input type="text" class="form-control" id="editSic_code" name="sic_code">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editIsic_code" class="form-label">ISIC Code</label>
                                <input type="text" class="form-control" id="editIsic_code" name="isic_code">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editIcon" class="form-label">Icon</label>
                                <input type="text" class="form-control" id="editIcon" name="icon">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editColor" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="editColor" name="color">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editSort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="editSort_order" name="sort_order" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIs_active" name="is_active">
                                <label class="form-check-label" for="editIs_active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIs_featured" name="is_featured">
                                <label class="form-check-label" for="editIs_featured">
                                    Featured
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Category Detail Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🏭 Industry Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4">Name:</dt>
                                            <dd class="col-sm-8" id="viewName">-</dd>

                                            <dt class="col-sm-4">Full Path:</dt>
                                            <dd class="col-sm-8" id="viewFullName">-</dd>

                                            <dt class="col-sm-4">Level:</dt>
                                            <dd class="col-sm-8" id="viewLevel">-</dd>

                                            <dt class="col-sm-4">Icon:</dt>
                                            <dd class="col-sm-8" id="viewIcon">-</dd>

                                            <dt class="col-sm-4">Sort Order:</dt>
                                            <dd class="col-sm-8" id="viewSortOrder">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4">NAICS:</dt>
                                            <dd class="col-sm-8" id="viewNaicsCode">-</dd>

                                            <dt class="col-sm-4">SIC:</dt>
                                            <dd class="col-sm-8" id="viewSicCode">-</dd>

                                            <dt class="col-sm-4">ISIC:</dt>
                                            <dd class="col-sm-8" id="viewIsicCode">-</dd>

                                            <dt class="col-sm-4">Slug:</dt>
                                            <dd class="col-sm-8" id="viewSlug">-</dd>

                                            <dt class="col-sm-4">Color:</dt>
                                            <dd class="col-sm-8" id="viewColor">-</dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <dt>Description:</dt>
                                    <dd id="viewDescription">-</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Hierarchy & Status</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-6">Status:</dt>
                                    <dd class="col-6" id="viewStatus">-</dd>

                                    <dt class="col-6">Featured:</dt>
                                    <dd class="col-6" id="viewFeatured">-</dd>

                                    <dt class="col-6">Type:</dt>
                                    <dd class="col-6" id="viewCategoryType">-</dd>

                                    <dt class="col-6">Children:</dt>
                                    <dd class="col-6" id="viewChildrenCount">-</dd>

                                    <dt class="col-6">ID:</dt>
                                    <dd class="col-6" id="viewCategoryId">-</dd>

                                    <dt class="col-6">Created:</dt>
                                    <dd class="col-6" id="viewCreatedAt">-</dd>

                                    <dt class="col-6">Updated:</dt>
                                    <dd class="col-6" id="viewUpdatedAt">-</dd>
                                </dl>

                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                            ✏️ Edit Category
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="viewAddChildBtn">
                                            ➕ Add Child Category
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" id="viewChildrenBtn">
                                            👶 View Children
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" id="viewMoveBtn">
                                            🔄 Move Category
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                            🗑️ Delete Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Breadcrumb Navigation in Modal -->
                <div class="row mt-3" id="viewBreadcrumbRow">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Category Path</h6>
                            </div>
                            <div class="card-body">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0" id="viewCategoryBreadcrumb">
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Children Categories -->
                <div class="row mt-3" id="viewChildrenRow">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Child Categories</h6>
                            </div>
                            <div class="card-body">
                                <div id="viewChildrenList">
                                    <p class="text-muted">No child categories</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Move Category Modal -->
<div class="modal fade" id="moveCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Move Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="moveCategoryForm">
                <input type="hidden" id="moveCategoryId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="moveNewParent" class="form-label">New Parent Category</label>
                        <select class="form-select" id="moveNewParent" name="new_parent_id" required>
                            <option value="">Root Level</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <small>Moving a category will also move all its child categories.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Move Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>