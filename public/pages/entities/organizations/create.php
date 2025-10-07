<?php
/**
 * Create Organization Page
 */

use Entities\Person;
use Entities\IndustryCategory;
use Entities\OrganizationLegalCategory;

$pageTitle = 'Add New Organization';

// Get all required data for dropdowns
$persons = Person::all();
$industries = IndustryCategory::all();
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
                    <li class="breadcrumb-item"><a href="/organizations">Organizations</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Organization
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/organizations/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="short_name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('short_name') ? 'is-invalid' : '' ?>"
                                   id="short_name"
                                   name="short_name"
                                   value="<?= old('short_name') ?>"
                                   required
                                   autofocus>
                            <?php $field = 'short_name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the organization's name</div>
                        </div>

                        <div class="mb-3">
                            <label for="tag_line" class="form-label">Tag Line</label>
                            <input type="text"
                                   class="form-control <?= errors('tag_line') ? 'is-invalid' : '' ?>"
                                   id="tag_line"
                                   name="tag_line"
                                   value="<?= old('tag_line') ?>">
                            <?php $field = 'tag_line'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter a brief tagline or slogan</div>
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url"
                                   class="form-control <?= errors('website') ? 'is-invalid' : '' ?>"
                                   id="website"
                                   name="website"
                                   value="<?= old('website') ?>"
                                   placeholder="https://example.com">
                            <?php $field = 'website'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the organization's website URL</div>
                        </div>

                        <div class="mb-3">
                            <label for="subdomain" class="form-label">Subdomain</label>
                            <input type="text"
                                   class="form-control <?= errors('subdomain') ? 'is-invalid' : '' ?>"
                                   id="subdomain"
                                   name="subdomain"
                                   value="<?= old('subdomain') ?>"
                                   placeholder="mycompany">
                            <?php $field = 'subdomain'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter a subdomain for the organization (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_id" class="form-label">Administrator</label>
                            <select class="form-select <?= errors('admin_id') ? 'is-invalid' : '' ?>"
                                    id="admin_id"
                                    name="admin_id">
                                <option value="">Select an administrator...</option>
                                <?php foreach ($persons as $person): ?>
                                    <option value="<?= $person->id ?>" <?= old('admin_id') == $person->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($person->first_name . ' ' . $person->last_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'admin_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the organization administrator (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="industry_id" class="form-label">Industry</label>
                            <select class="form-select <?= errors('industry_id') ? 'is-invalid' : '' ?>"
                                    id="industry_id"
                                    name="industry_id">
                                <option value="">Select an industry...</option>
                                <?php foreach ($industries as $industry): ?>
                                    <option value="<?= $industry->id ?>" <?= old('industry_id') == $industry->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($industry->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'industry_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the industry category (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="legal_category_id" class="form-label">Legal Category</label>
                            <select class="form-select <?= errors('legal_category_id') ? 'is-invalid' : '' ?>"
                                    id="legal_category_id"
                                    name="legal_category_id">
                                <option value="">Select a legal category...</option>
                                <?php foreach ($legalCategories as $category): ?>
                                    <option value="<?= $category->id ?>" <?= old('legal_category_id') == $category->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'legal_category_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the legal category (e.g., LLC, Corporation) (optional)</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/organizations" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Organization
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>