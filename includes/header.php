<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="V4L - Your Community, Your Marketplace">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' ws://localhost:8080 wss://localhost:8080 https://cdn.jsdelivr.net https://maps.googleapis.com;">

    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - V4L' : 'V4L - Your Community, Your Marketplace'; ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shop"></i> V4L
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (Auth::check()): ?>
                        <!-- My Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> My
                            </a>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">Personal</h6></li>
                                <li><a class="dropdown-item" href="/my/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="/my/settings">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Education</h6></li>
                                <li><a class="dropdown-item" href="/entities/person_education/list">My Education</a></li>
                                <li><a class="dropdown-item" href="/entities/person_education_subject/list">My Subjects</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Skills</h6></li>
                                <li><a class="dropdown-item" href="/entities/person_skill/list">My Skills</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/auth/logout">Logout</a></li>
                            </ul>
                        </li>

                        <!-- Organization Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-building"></i> Organization
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                                <li><a class="dropdown-item" href="/entities/organization/list">Organizations</a></li>
                                <li><a class="dropdown-item" href="/entities/catalogitem/list">Products</a></li>
                            </ul>
                        </li>

                        <!-- Common Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-database"></i> Common
                            </a>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">Geography</h6></li>
                                <li><a class="dropdown-item" href="/entities/continent/list">Continents</a></li>
                                <li><a class="dropdown-item" href="/entities/country/list">Countries</a></li>
                                <li><a class="dropdown-item" href="/entities/state/list">States</a></li>
                                <li><a class="dropdown-item" href="/entities/city/list">Cities</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Reference Data</h6></li>
                                <li><a class="dropdown-item" href="/entities/language/list">Languages</a></li>
                                <li><a class="dropdown-item" href="/entities/currency/list">Currencies</a></li>
                                <li><a class="dropdown-item" href="/entities/timezone/list">Time Zones</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Education</h6></li>
                                <li><a class="dropdown-item" href="/entities/enum_education_levels/list">Education Levels</a></li>
                                <li><a class="dropdown-item" href="/entities/popular_education_subject/list">Education Subjects</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Skills</h6></li>
                                <li><a class="dropdown-item" href="/entities/popular_skill/list">Popular Skills</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Market Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-cart"></i> Market
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/market/catalog">Browse Products</a></li>
                            <li><a class="dropdown-item" href="/market/jobs">Find Jobs</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <button class="btn btn-link nav-link" onclick="toggleTheme()" title="Toggle theme">
                            <i class="bi bi-moon-stars" id="theme-icon"></i>
                        </button>
                    </li>

                    <?php if (Auth::check()): ?>
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars(Auth::user()['username'] ?? 'User'); ?>
                            </span>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/signup">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
