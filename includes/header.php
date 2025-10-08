<?php
// Ensure bootstrap is loaded
if (!isset($BOOTSTRAP_LOADED)) {
    require_once __DIR__ . '/../bootstrap.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'V4L' ?> - V4L Platform</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-geo-alt-fill"></i> V4L
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>

                    <!-- Public Marketplace -->
                    <li class="nav-item">
                        <a class="nav-link" href="/organization_vacancies">
                            <i class="bi bi-megaphone"></i> Jobs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/catalog_categories">
                            <i class="bi bi-cart"></i> Marketplace
                        </a>
                    </li>

                    <?php if (auth()): ?>
                    <!-- My Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="myDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person"></i> My
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="myDropdown">
                            <li><h6 class="dropdown-header">Personal</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/persons/list.php"><i class="bi bi-person-badge"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/credentials/list.php"><i class="bi bi-key"></i> Account & Credentials</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Education & Skills</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/person_educations/list.php"><i class="bi bi-mortarboard"></i> My Education</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/person_education_subjects/list.php"><i class="bi bi-book"></i> Education Subjects</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/person_skills/list.php"><i class="bi bi-tools"></i> My Skills</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Applications</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/vacancy_applications/list.php"><i class="bi bi-file-earmark-text"></i> My Applications</a></li>
                        </ul>
                    </li>

                    <!-- Organization Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="orgDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-building"></i> Organization
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="orgDropdown">
                            <li><h6 class="dropdown-header">Organization Setup</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/organizations/list.php"><i class="bi bi-building"></i> Organizations</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/organization_branches/list.php"><i class="bi bi-diagram-3"></i> Branches</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/organization_buildings/list.php"><i class="bi bi-house"></i> Buildings</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/workstations/list.php"><i class="bi bi-pc-display"></i> Workstations</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Organization Structure</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/popular_organization_departments/list.php"><i class="bi bi-diagram-2"></i> Departments</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/popular_organization_teams/list.php"><i class="bi bi-people"></i> Teams</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/popular_organization_designations/list.php"><i class="bi bi-award"></i> Designations</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/popular_organization_positions/list.php"><i class="bi bi-briefcase"></i> Positions</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Hiring & Employment</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/organization_vacancies/list.php"><i class="bi bi-megaphone"></i> Vacancies</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/organization_vacancy_workstations/list.php"><i class="bi bi-geo"></i> Vacancy Workstations</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/vacancy_applications/list.php"><i class="bi bi-file-earmark-person"></i> Applications</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/application_reviews/list.php"><i class="bi bi-clipboard-check"></i> Application Reviews</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/interview_stages/list.php"><i class="bi bi-list-ol"></i> Interview Stages</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/application_interviews/list.php"><i class="bi bi-chat-dots"></i> Interviews</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/job_offers/list.php"><i class="bi bi-envelope-check"></i> Job Offers</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/employment_contracts/list.php"><i class="bi bi-file-text"></i> Employment Contracts</a></li>
                        </ul>
                    </li>

                    <!-- Market Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="marketDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-cart"></i> Market
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="marketDropdown">
                            <li><h6 class="dropdown-header">Catalog</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/catalog_categories/list.php"><i class="bi bi-folder"></i> Categories</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/catalog_items/list.php"><i class="bi bi-box-seam"></i> Catalog Items</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/catalog_item_features/list.php"><i class="bi bi-list-check"></i> Item Features</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/catalog_item_media/list.php"><i class="bi bi-images"></i> Item Media</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/catalog_item_tags/list.php"><i class="bi bi-tags"></i> Item Tags</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/catalog_item_reviews/list.php"><i class="bi bi-star"></i> Item Reviews</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Seller Management</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/seller_items/list.php"><i class="bi bi-shop"></i> Seller Items</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/seller_item_prices/list.php"><i class="bi bi-currency-dollar"></i> Item Prices</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/seller_item_inventories/list.php"><i class="bi bi-box"></i> Inventory</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/seller_service_schedules/list.php"><i class="bi bi-calendar-week"></i> Service Schedules</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/seller_item_reviews/list.php"><i class="bi bi-chat-left-quote"></i> Seller Reviews</a></li>
                        </ul>
                    </li>

                    <!-- Common Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="commonDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe"></i> Common
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="commonDropdown">
                            <li><h6 class="dropdown-header">Geography</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/continents/list.php"><i class="bi bi-globe-americas"></i> Continents</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/countries/list.php"><i class="bi bi-flag"></i> Countries</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/languages/list.php"><i class="bi bi-translate"></i> Languages</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/postal_addresses/list.php"><i class="bi bi-mailbox"></i> Addresses</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Reference Data</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/industry_categories/list.php"><i class="bi bi-diagram-3"></i> Industries</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/organization_legal_categories/list.php"><i class="bi bi-file-earmark-ruled"></i> Legal Categories</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/popular_education_subjects/list.php"><i class="bi bi-journal-text"></i> Education Subjects</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/popular_skills/list.php"><i class="bi bi-lightbulb"></i> Skills Library</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">System</h6></li>
                            <li><a class="dropdown-item" href="/pages/entities/entity_definitions/list.php"><i class="bi bi-database"></i> Entity Definitions</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/entity_process_authorizations/list.php"><i class="bi bi-shield-lock"></i> Process Authorization</a></li>
                            <li><a class="dropdown-item" href="/pages/entities/entity_instance_authorizations/list.php"><i class="bi bi-shield-check"></i> Instance Authorization</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (auth()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars(auth()->first_name ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="/settings">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/signup">
                                <i class="bi bi-person-plus"></i> Sign Up
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    <?php if (session('success')): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars(session('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars(session('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Main Content -->
