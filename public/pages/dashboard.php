<?php
/**
 * Dashboard page - Public
 */
$pageTitle = 'Dashboard';
include __DIR__ . '/../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard</h1>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Welcome to V4L</h5>
                            <p class="card-text">Vocal 4 Local - Geo-Intelligent Marketplace Platform</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Quick Access</h5>
                            <p class="card-text">Access your frequently used features</p>
                            <a href="/organization_vacancies" class="btn btn-light btn-sm">Organization Vacancies</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">System Status</h5>
                            <p class="card-text">All systems operational</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Getting Started</h5>
                            <p class="card-text">Explore the platform features</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Available Modules</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <h6>Geography</h6>
                                    <ul>
                                        <li><a href="/continents">Continents</a></li>
                                        <li><a href="/countries">Countries</a></li>
                                        <li><a href="/languages">Languages</a></li>
                                        <li><a href="/postal_addresses">Postal Addresses</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Person Management</h6>
                                    <ul>
                                        <li><a href="/persons">Persons</a></li>
                                        <li><a href="/credentials">Credentials</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Education & Skills</h6>
                                    <ul>
                                        <li><a href="/person_education">Education Records</a></li>
                                        <li><a href="/person_skills">Skills</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
