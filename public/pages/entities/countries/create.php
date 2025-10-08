<?php
/**
 * Create Country Page
 */

use Entities\Continent;

$pageTitle = 'Add New Country';

// Get all continents for the dropdown
$continents = Continent::all();

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/countries">Countries</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-plus-circle"></i> Add New Country
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/countries/store" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Country Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name') ?>"
                                   required
                                   autofocus>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Enter the full name of the country</div>
                        </div>

                        <div class="mb-3">
                            <?php
                            $fk_label = 'Continent';
                            $fk_for = 'continent_id';
                            $fk_entity = 'continents';
                            $fk_required = true;
                            $fk_icon = 'bi-globe-americas';
                            include __DIR__ . '/../../../../views/components/fk-label.php';
                            ?>
                            <select class="form-select <?= errors('continent_id') ? 'is-invalid' : '' ?>"
                                    id="continent_id"
                                    name="continent_id"
                                    required>
                                <option value="">Select a continent...</option>
                                <?php foreach ($continents as $continent): ?>
                                    <option value="<?= $continent->id ?>" <?= old('continent_id') == $continent->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($continent->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php $field = 'continent_id'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                            <div class="form-text">Select the continent this country belongs to</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/countries" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Country
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
