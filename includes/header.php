<?php
// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Get current page for active navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$page_titles = [
    'index' => 'Dashboard',
    'persons' => 'Person Management',
    'personcredentials' => 'User Management',
    'continents' => 'Continent Management',
    'languages' => 'Language Management',
    'countries' => 'Country Management',
    'industry_categories' => 'Industry Categories',
    'organization_legal_types' => 'Organization Legal Types'
];

$page_icons = [
    'index' => '🎙️',
    'persons' => '👤',
    'personcredentials' => '🔐',
    'continents' => '🌍',
    'languages' => '🗣️',
    'countries' => '🏴',
    'industry_categories' => '🏭',
    'organization_legal_types' => '🏢'
];

$page_title = $page_titles[$current_page] ?? 'V4L - Vocal 4 Local';
$page_icon = $page_icons[$current_page] ?? '🎙️';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_title); ?> - V4L Vocal 4 Local Community Platform">
    <meta name="author" content="V4L - Vocal 4 Local">
    <title><?php echo htmlspecialchars($page_title); ?> - V4L</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="<?php echo $current_page === 'index' ? '../css/styles.css' : 'styles.css'; ?>" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22><?php echo $page_icon; ?></text></svg>">

    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" as="script">

    <!-- Page-specific styles -->
    <?php if (isset($additional_styles)) echo $additional_styles; ?>
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only sr-only-focusable btn btn-primary">Skip to main content</a>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg" role="navigation" aria-label="Main navigation">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php" aria-label="V4L - Vocal 4 Local Home">
                <span class="me-2">🎙️</span>
                <span>V4L - Vocal 4 Local</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>"
                       href="index.php" aria-label="Dashboard">
                        <span class="nav-icon">🎙️</span> Dashboard
                    </a>
                    <a class="nav-link <?php echo $current_page === 'persons' ? 'active' : ''; ?>"
                       href="persons.php" aria-label="Person Management">
                        <span class="nav-icon">👤</span> Persons
                    </a>
                    <a class="nav-link <?php echo $current_page === 'personcredentials' ? 'active' : ''; ?>"
                       href="personcredentials.php" aria-label="User Management">
                        <span class="nav-icon">🔐</span> User Management
                    </a>
                    <a class="nav-link <?php echo $current_page === 'continents' ? 'active' : ''; ?>"
                       href="continents.php" aria-label="Continent Management">
                        <span class="nav-icon">🌍</span> Continents
                    </a>
                    <a class="nav-link <?php echo $current_page === 'languages' ? 'active' : ''; ?>"
                       href="languages.php" aria-label="Language Management">
                        <span class="nav-icon">🗣️</span> Languages
                    </a>
                    <a class="nav-link <?php echo $current_page === 'countries' ? 'active' : ''; ?>"
                       href="countries.php" aria-label="Country Management">
                        <span class="nav-icon">🏴</span> Countries
                    </a>
                    <a class="nav-link <?php echo $current_page === 'industry_categories' ? 'active' : ''; ?>"
                       href="industry_categories.php" aria-label="Industry Categories">
                        <span class="nav-icon">🏭</span> Industry Categories
                    </a>
                    <a class="nav-link <?php echo $current_page === 'organization_legal_types' ? 'active' : ''; ?>"
                       href="organization_legal_types.php" aria-label="Organization Legal Types">
                        <span class="nav-icon">🏢</span> Legal Types
                    </a>

                    <div class="nav-item d-flex align-items-center me-3 ms-3">
                        <span class="connection-status connected" aria-label="Connection status"></span>
                        <small class="text-muted ms-1">Real-time</small>
                    </div>

                    <button id="themeToggle" class="theme-toggle me-2" aria-label="Toggle dark/light theme">
                        <span id="themeIcon">🌙</span>
                    </button>

                    <button id="refreshBtn" class="btn btn-outline-primary btn-sm" aria-label="Refresh data">
                        <span id="loadingIndicator" class="loading-spinner" style="display: none;" aria-hidden="true"></span>
                        <span id="refreshText">Refresh</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" role="main">