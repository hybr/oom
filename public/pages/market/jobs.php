<?php
$pageTitle = 'Job Opportunities';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-5">
    <h1><i class="bi bi-briefcase"></i> Job Opportunities</h1>
    <p class="lead">Find local employment opportunities in your area.</p>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> The job board is coming soon! This page will display job vacancies from local organizations.
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-search display-1 text-primary"></i>
                    <h5 class="mt-3">Search Jobs</h5>
                    <p class="text-muted">Find opportunities that match your skills</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-building display-1 text-success"></i>
                    <h5 class="mt-3">Local Companies</h5>
                    <p class="text-muted">Work for businesses in your community</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text display-1 text-warning"></i>
                    <h5 class="mt-3">Easy Apply</h5>
                    <p class="text-muted">Quick application process</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
