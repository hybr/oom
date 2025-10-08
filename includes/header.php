<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - ' : ''; ?>V4L - Your Community, Your Marketplace</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27><text y=%27.9em%27 font-size=%2790%27>🏪</text></svg>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo url('/'); ?>">
                🏪 V4L
                <span class="navbar-tagline d-none d-md-inline">Your Community, Your Marketplace</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/'); ?>">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>

                    <?php if (auth()->check()): ?>
                        <!-- My Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="myMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> My
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo url('pages/dashboard.php'); ?>">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">My Profile</h6></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/persons/detail.php?id=' . auth()->id()); ?>">View Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/person_education/list.php'); ?>">Education</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/person_skills/list.php'); ?>">Skills</a></li>
                            </ul>
                        </li>

                        <!-- Organization Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="orgMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-building"></i> Organization
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/organizations/list.php'); ?>">My Organizations</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/organizations/create.php'); ?>">Create Organization</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Hiring</h6></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/organization_vacancies/list.php'); ?>">Vacancies</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/vacancy_applications/list.php'); ?>">Applications</a></li>
                            </ul>
                        </li>

                        <!-- Market Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="marketMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-shop"></i> Market
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo url('pages/market/catalog.php'); ?>">Browse Catalog</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/market/sellers.php'); ?>">Find Sellers</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/market/jobs.php'); ?>">Job Listings</a></li>
                            </ul>
                        </li>

                        <!-- Common Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="commonMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear"></i> Common
                            </a>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">Geography</h6></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/continents/list.php'); ?>">Continents</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/countries/list.php'); ?>">Countries</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/languages/list.php'); ?>">Languages</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Reference Data</h6></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/industry_categories/list.php'); ?>">Industries</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/popular_skills/list.php'); ?>">Skills</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('pages/entities/popular_education_subjects/list.php'); ?>">Education Subjects</a></li>
                            </ul>
                        </li>

                        <!-- User dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo escape(auth()->user()['full_name'] ?? 'User'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php
                                $orgs = auth()->getUserOrganizations();
                                if (!empty($orgs)):
                                ?>
                                    <li><h6 class="dropdown-header">Select Organization</h6></li>
                                    <?php foreach ($orgs as $org): ?>
                                        <li>
                                            <a class="dropdown-item" href="?set_org=<?php echo $org['id']; ?>">
                                                <?php if (auth()->organizationId() == $org['id']): ?>
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-circle"></i>
                                                <?php endif; ?>
                                                <?php echo escape($org['short_name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo url('pages/auth/logout.php'); ?>">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a></li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('pages/market/catalog.php'); ?>">
                                <i class="bi bi-shop"></i> Marketplace
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('pages/auth/login.php'); ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('pages/auth/signup.php'); ?>">
                                <i class="bi bi-person-plus"></i> Sign Up
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <button class="theme-toggle nav-link" id="themeToggle" title="Toggle theme">
                            <i class="bi bi-moon-fill" id="themeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // Handle organization selection
    if (isset($_GET['set_org']) && auth()->check()) {
        auth()->setOrganization($_GET['set_org']);
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }
    ?>

    <!-- Alert Messages -->
    <?php if ($successMessage = flash('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo escape($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage = flash('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo escape($errorMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
