<?php
$pageTitle = 'Home';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="hero-section">
    <div class="container">
        <h1>Welcome to V4L</h1>
        <p class="lead">Your Community, Your Marketplace</p>
        <div class="mt-4">
            <?php if (Auth::check()): ?>
                <a href="/dashboard" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            <?php else: ?>
                <a href="/auth/signup" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-person-plus"></i> Get Started
                </a>
                <a href="/auth/login" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-shop display-4 text-primary"></i>
                    <h3 class="mt-3">Local Marketplace</h3>
                    <p class="text-muted">Discover and connect with local businesses in your community</p>
                    <a href="/market/catalog" class="btn btn-outline-primary">Browse Products</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-briefcase display-4 text-success"></i>
                    <h3 class="mt-3">Job Opportunities</h3>
                    <p class="text-muted">Find local employment opportunities in your area</p>
                    <a href="/market/jobs" class="btn btn-outline-success">Find Jobs</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 text-info"></i>
                    <h3 class="mt-3">Community Driven</h3>
                    <p class="text-muted">Built for communities, powered by local connections</p>
                    <a href="/about" class="btn btn-outline-info">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-6">
            <h2>Why V4L?</h2>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Support local businesses</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Discover community opportunities</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Easy-to-use platform</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Secure and reliable</li>
            </ul>
        </div>

        <div class="col-lg-6">
            <h2>Getting Started</h2>
            <ol>
                <li class="mb-2">Create your free account</li>
                <li class="mb-2">Set up your organization profile</li>
                <li class="mb-2">Start connecting with your community</li>
            </ol>
            <?php if (!Auth::check()): ?>
                <a href="/auth/signup" class="btn btn-primary">
                    <i class="bi bi-rocket"></i> Start Now
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
