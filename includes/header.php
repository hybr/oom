<?php
// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Get current page for active navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Menu structure based on menu.txt
$menu_structure = [
    'Dashboard' => [
        'icon' => '🎙️',
        'page' => 'index',
        'title' => 'Dashboard'
    ],
    'My' => [
        'icon' => '👤',
        'items' => [
            'persons' => ['title' => 'Persons', 'icon' => '👤'],
            'postal_addresses' => ['title' => 'Addresses', 'icon' => '📍'],
            'personcredentials' => ['title' => 'User Management', 'icon' => '🔐', 'sub_items' => [
                'login' => ['title' => 'Login', 'icon' => '🔑'],
                'logout' => ['title' => 'Logout', 'icon' => '🚪'],
                'signup' => ['title' => 'Sign Up', 'icon' => '📝'],
                'forgot_password' => ['title' => 'Forgot Password', 'icon' => '❓'],
                'reset_password' => ['title' => 'Reset Password', 'icon' => '🔄'],
                'change_password' => ['title' => 'Change Password', 'icon' => '🔐']
            ]]
        ]
    ],
    'Common' => [
        'icon' => '🌍',
        'items' => [
            'continents' => ['title' => 'Continents', 'icon' => '🌍'],
            'languages' => ['title' => 'Languages', 'icon' => '🗣️'],
            'countries' => ['title' => 'Countries', 'icon' => '🏴'],
            'industry_categories' => ['title' => 'Industry Categories', 'icon' => '🏭'],
            'organization_legal_types' => ['title' => 'Legal Types', 'icon' => '🏢']
        ]
    ],
    'Organizations' => [
        'icon' => '🏛️',
        'items' => [
            'organizations' => ['title' => 'Organizations', 'icon' => '🏛️'],
            'organization_branches' => ['title' => 'Branches', 'icon' => '🏢', 'sub_items' => [
                'organization_buildings' => ['title' => 'Buildings', 'icon' => '🏗️', 'sub_items' => [
                    'organization_workstations' => ['title' => 'Workstations', 'icon' => '💻']
                ]]
            ]],
            'popular_organization_departments' => ['title' => 'Popular Departments', 'icon' => '🏢', 'sub_items' => [
                'teams' => ['title' => 'Teams', 'icon' => '👥']
            ]],
            'postal_addresses' => ['title' => 'Addresses', 'icon' => '📍']
        ]
    ]
];

// Get current menu context
function getCurrentMenuContext($current_page, $menu_structure) {
    foreach ($menu_structure as $section_name => $section) {
        if (isset($section['page']) && $section['page'] === $current_page) {
            return ['section' => $section_name, 'level' => 1];
        }
        if (isset($section['items'])) {
            foreach ($section['items'] as $page => $item) {
                if ($page === $current_page) {
                    return ['section' => $section_name, 'page' => $page, 'level' => 2];
                }
                if (isset($item['sub_items'])) {
                    foreach ($item['sub_items'] as $sub_page => $sub_item) {
                        if ($sub_page === $current_page) {
                            return ['section' => $section_name, 'parent' => $page, 'page' => $sub_page, 'level' => 3];
                        }
                        // Check for level 4 (sub-sub-items)
                        if (isset($sub_item['sub_items'])) {
                            foreach ($sub_item['sub_items'] as $sub_sub_page => $sub_sub_item) {
                                if ($sub_sub_page === $current_page) {
                                    return [
                                        'section' => $section_name,
                                        'parent' => $page,
                                        'grandparent' => $sub_page,
                                        'page' => $sub_sub_page,
                                        'level' => 4
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return ['section' => 'Dashboard', 'level' => 1];
}

$menu_context = getCurrentMenuContext($current_page, $menu_structure);

// Page titles mapping
$page_titles = [
    'index' => 'Dashboard',
    'persons' => 'Person Management',
    'personcredentials' => 'User Management',
    'continents' => 'Continent Management',
    'languages' => 'Language Management',
    'countries' => 'Country Management',
    'industry_categories' => 'Industry Categories',
    'organization_legal_types' => 'Organization Legal Types',
    'organizations' => 'Organizations',
    'organization_branches' => 'Organization Branches',
    'organization_buildings' => 'Organization Buildings',
    'postal_addresses' => 'Postal Addresses'
];

// Get page title and icon
function getPageDetails($current_page, $menu_structure, $page_titles) {
    // Check direct mapping first
    $title = $page_titles[$current_page] ?? 'V4L - Vocal 4 Local';
    $icon = '🎙️';

    // Find icon from menu structure
    foreach ($menu_structure as $section) {
        if (isset($section['page']) && $section['page'] === $current_page) {
            $icon = $section['icon'];
            break;
        }
        if (isset($section['items'])) {
            foreach ($section['items'] as $page => $item) {
                if ($page === $current_page) {
                    $icon = $item['icon'];
                    break 2;
                }
                if (isset($item['sub_items'])) {
                    foreach ($item['sub_items'] as $sub_page => $sub_item) {
                        if ($sub_page === $current_page) {
                            $icon = $sub_item['icon'];
                            break 3;
                        }
                    }
                }
            }
        }
    }

    return ['title' => $title, 'icon' => $icon];
}

$page_details = getPageDetails($current_page, $menu_structure, $page_titles);
$page_title = $page_details['title'];
$page_icon = $page_details['icon'];
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

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="<?php echo $current_page === 'index' ? '../css/styles.css' : 'styles.css'; ?>" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22><?php echo $page_icon; ?></text></svg>">

    <!-- Preload critical resources -->
    <!-- Bootstrap JS removed from preload as it's not critical for initial rendering -->

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
                    <?php foreach ($menu_structure as $section_name => $section): ?>
                        <?php if (isset($section['page'])): ?>
                            <!-- Direct page link -->
                            <a class="nav-link <?php echo $menu_context['section'] === $section_name ? 'active' : ''; ?>"
                               href="<?php echo $section['page']; ?>.php" aria-label="<?php echo $section['title']; ?>">
                                <span class="nav-icon"><?php echo $section['icon']; ?></span> <?php echo $section_name; ?>
                            </a>
                        <?php else: ?>
                            <!-- Dropdown menu -->
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?php echo $menu_context['section'] === $section_name ? 'active' : ''; ?>"
                                   href="#" id="dropdown<?php echo $section_name; ?>" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="nav-icon"><?php echo $section['icon']; ?></span> <?php echo $section_name; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown<?php echo $section_name; ?>">
                                    <?php foreach ($section['items'] as $page => $item): ?>
                                        <li>
                                            <a class="dropdown-item <?php echo $current_page === $page ? 'active' : ''; ?>"
                                               href="<?php echo $page; ?>.php">
                                                <span class="nav-icon"><?php echo $item['icon']; ?></span> <?php echo $item['title']; ?>
                                            </a>
                                        </li>
                                        <?php if (isset($item['sub_items'])): ?>
                                            <?php foreach ($item['sub_items'] as $sub_page => $sub_item): ?>
                                                <li>
                                                    <a class="dropdown-item ps-4 <?php echo $current_page === $sub_page ? 'active' : ''; ?>"
                                                       href="<?php echo $sub_page; ?>.php">
                                                        <span class="nav-icon"><?php echo $sub_item['icon']; ?></span> <?php echo $sub_item['title']; ?>
                                                    </a>
                                                </li>
                                                <?php if (isset($sub_item['sub_items'])): ?>
                                                    <?php foreach ($sub_item['sub_items'] as $sub_sub_page => $sub_sub_item): ?>
                                                        <li>
                                                            <a class="dropdown-item ps-5 <?php echo $current_page === $sub_sub_page ? 'active' : ''; ?>"
                                                               href="<?php echo $sub_sub_page; ?>.php">
                                                                <span class="nav-icon"><?php echo $sub_sub_item['icon']; ?></span> <?php echo $sub_sub_item['title']; ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

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