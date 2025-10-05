<?php
/**
 * Organization Vacancies List Page
 */
$pageTitle = 'Organization Vacancies';
include __DIR__ . '/../../../../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="bi bi-briefcase"></i> Organization Vacancies
                </h1>
                <a href="/organization_vacancies/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Post New Vacancy
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-0">Active Vacancies</h5>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" placeholder="Search vacancies..." id="searchInput">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Coming Soon:</strong> Organization vacancy management is under development.
                        This feature will allow you to:
                        <ul class="mb-0 mt-2">
                            <li>Post job vacancies for your organization</li>
                            <li>Manage applications from candidates</li>
                            <li>Track interview schedules</li>
                            <li>Make job offers</li>
                            <li>Create employment contracts</li>
                        </ul>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Position</th>
                                    <th>Organization</th>
                                    <th>Opening Date</th>
                                    <th>Closing Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> No vacancies available at the moment
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (placeholder) -->
                    <nav aria-label="Vacancy pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
