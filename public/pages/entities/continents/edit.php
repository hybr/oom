<?php
/**
 * Edit Continent Page
 */

use Entities\Continent;

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/continents');
    exit;
}

$continent = Continent::find($id);

if (!$continent) {
    $_SESSION['error'] = 'Continent not found';
    redirect('/continents');
    exit;
}

$pageTitle = 'Edit ' . $continent->name;

include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/continents">Continents</a></li>
                    <li class="breadcrumb-item"><a href="/continents/<?= $continent->id ?>"><?= htmlspecialchars($continent->name) ?></a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>

            <!-- Header -->
            <h1 class="mb-4">
                <i class="bi bi-pencil"></i> Edit Continent
            </h1>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <?php include __DIR__ . '/../../../../views/components/form-errors.php'; ?>

                    <form method="POST" action="/continents/<?= $continent->id ?>/update" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="version" value="<?= $continent->version ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Continent Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?= errors('name') ? 'is-invalid' : '' ?>"
                                   id="name"
                                   name="name"
                                   value="<?= old('name', $continent->name) ?>"
                                   required
                                   autofocus>
                            <?php $field = 'name'; include __DIR__ . '/../../../../views/components/form-errors.php'; ?>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                Last updated: <?= date('F d, Y h:i A', strtotime($continent->updated_at)) ?>
                                (Version: <?= $continent->version ?>)
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/continents/<?= $continent->id ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Continent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
