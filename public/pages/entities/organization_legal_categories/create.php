<?php
/**
 * Create Organization Legal Category Page
 */

use Entities\OrganizationLegalCategory;

$pageTitle = 'Add New Organization Legal Category';

// Get all legal categories for the parent dropdown
$legalCategories = OrganizationLegalCategory::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/organization_legal_categories">Organization Legal Categories</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Organization Legal Category
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/organization_legal_categories/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name') ?>"
                                   required
                                   autofocus>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the name of the legal category (e.g., "LLC", "Corporation", "Partnership")</div>
                        </div>

                        <div class="mb-3">
                            <label for="parent_category_id" class="form-label">Parent Category</label>
                            <select class="form-select <?= errors('parent_category_id') ? 'is-invalid' : '' ?>"
                                    id="parent_category_id"
                                    name="parent_category_id">
                                <option value="">None (Top-level category)</option>
                                <?php foreach ($legalCategories as $category): ?>
                                    <option value="<?= $category->id ?>" <?= old('parent_category_id') == $category->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'parent_category_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select a parent category to create a subcategory (optional)</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/organization_legal_categories" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>