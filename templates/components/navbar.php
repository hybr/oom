<?php
use V4L\Core\Auth;

$isLoggedIn = Auth::isLoggedIn();
$currentUser = $isLoggedIn ? Auth::getCurrentUser() : null;
$organizations = $isLoggedIn ? Auth::getUserOrganizations() : [];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= url() ?>">
            <i class="bi bi-shop"></i> V4L
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('index.php') ? 'active' : '' ?>" href="<?= url() ?>">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('marketplace.php') ? 'active' : '' ?>" href="<?= url('marketplace.php') ?>">
                        <i class="bi bi-cart"></i> Marketplace
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('vacancies.php') ? 'active' : '' ?>" href="<?= url('vacancies.php') ?>">
                        <i class="bi bi-briefcase"></i> Jobs
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-building"></i> My Organizations
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($organizations as $org): ?>
                            <li>
                                <a class="dropdown-item" href="<?= url('organization.php?id=' . $org['id']) ?>">
                                    <?= e($org['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($organizations)): ?>
                            <li><span class="dropdown-item-text text-muted">No organizations</span></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Search bar -->
            <form class="d-flex me-3" role="search" action="<?= url('search.php') ?>" method="GET">
                <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-light btn-sm" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <ul class="navbar-nav">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= e($currentUser['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= url('profile.php') ?>">
                                    <i class="bi bi-person"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= url('dashboard.php') ?>">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= url('logout.php') ?>">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('login.php') ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm" href="<?= url('register.php') ?>">
                            Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
