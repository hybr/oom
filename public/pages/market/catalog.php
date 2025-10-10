<?php
$pageTitle = 'Product Catalog';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-5">
    <h1><i class="bi bi-shop"></i> Product Catalog</h1>
    <p class="lead">Discover local products and services from your community.</p>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> The product catalog is coming soon! This page will display products from local organizations.
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-basket display-1 text-primary"></i>
                    <h5 class="mt-3">Browse Products</h5>
                    <p class="text-muted">Search and filter local products</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-geo-alt display-1 text-success"></i>
                    <h5 class="mt-3">Find Nearby</h5>
                    <p class="text-muted">Discover businesses in your area</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-star display-1 text-warning"></i>
                    <h5 class="mt-3">Top Rated</h5>
                    <p class="text-muted">See highly-rated local vendors</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
