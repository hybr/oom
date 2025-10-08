<?php
require_once __DIR__ . '/../../../bootstrap.php';

// Public page - no auth required

$db = db();
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Build query for open vacancies
$sql = "SELECT ov.*, o.short_name as organization_name, o.tag_line,
               pop.name as position_name,
               pod.name as department_name,
               podes.name as designation_name
        FROM organization_vacancies ov
        JOIN organizations o ON ov.organization_id = o.id
        JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
        LEFT JOIN popular_organization_departments pod ON pop.department_id = pod.id
        LEFT JOIN popular_organization_designations podes ON pop.designation_id = podes.id
        WHERE ov.deleted_at IS NULL
          AND ov.status = 'Open'
          AND o.deleted_at IS NULL
          AND (ov.closing_date IS NULL OR ov.closing_date >= date('now'))";

$params = [];

if ($search) {
    $sql .= " AND (pop.name LIKE ? OR o.short_name LIKE ? OR pop.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Count total
$countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as t";
$totalRecords = $db->selectOne($countSql, $params)['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

// Get data
$sql .= " ORDER BY ov.opening_date DESC LIMIT $perPage OFFSET $offset";
$vacancies = $db->select($sql, $params);

$pageTitle = 'Job Listings';
require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="text-center py-5">
        <h1 class="display-4">💼 Job Opportunities</h1>
        <p class="lead text-muted">Find your next career opportunity in your local community</p>
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Search by position, company, or keyword..." value="<?php echo escape($search); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">
            Found <strong><?php echo $totalRecords; ?></strong> open positions
        </p>
        <?php if (auth()->check()): ?>
            <a href="<?php echo url('pages/entities/organization_vacancies/list.php'); ?>" class="btn btn-sm btn-success">
                <i class="bi bi-briefcase"></i> My Applications
            </a>
        <?php endif; ?>
    </div>

    <!-- Job Listings -->
    <?php if (empty($vacancies)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h4>No vacancies found</h4>
            <p class="text-muted">Check back later for new opportunities!</p>
        </div>
    <?php else: ?>
        <?php foreach ($vacancies as $vacancy): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title mb-2">
                                <a href="<?php echo auth()->check() ? url('pages/entities/organization_vacancies/detail.php?id=' . $vacancy['id']) : url('pages/auth/login.php'); ?>" class="text-decoration-none">
                                    <?php echo escape($vacancy['position_name']); ?>
                                </a>
                            </h4>
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-building"></i>
                                <?php echo escape($vacancy['organization_name']); ?>
                                <?php if ($vacancy['tag_line']): ?>
                                    <small class="text-muted">- <?php echo escape($vacancy['tag_line']); ?></small>
                                <?php endif; ?>
                            </h5>

                            <div class="mb-2">
                                <?php if ($vacancy['department_name']): ?>
                                    <span class="badge bg-primary me-2">
                                        <i class="bi bi-diagram-3"></i> <?php echo escape($vacancy['department_name']); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($vacancy['designation_name']): ?>
                                    <span class="badge bg-secondary me-2">
                                        <i class="bi bi-award"></i> <?php echo escape($vacancy['designation_name']); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> <?php echo escape($vacancy['status']); ?>
                                </span>
                            </div>

                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar"></i> Posted: <?php echo date('M d, Y', strtotime($vacancy['opening_date'])); ?>
                                <?php if ($vacancy['closing_date']): ?>
                                    | <i class="bi bi-calendar-x"></i> Closes: <?php echo date('M d, Y', strtotime($vacancy['closing_date'])); ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-4 text-end d-flex flex-column justify-content-center">
                            <?php if (auth()->check()): ?>
                                <a href="<?php echo url('pages/entities/organization_vacancies/detail.php?id=' . $vacancy['id']); ?>" class="btn btn-primary mb-2">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                <a href="<?php echo url('pages/entities/vacancy_applications/create.php?vacancy_id=' . $vacancy['id']); ?>" class="btn btn-success">
                                    <i class="bi bi-file-earmark-text"></i> Apply Now
                                </a>
                            <?php else: ?>
                                <a href="<?php echo url('pages/auth/login.php'); ?>" class="btn btn-primary mb-2">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to View
                                </a>
                                <a href="<?php echo url('pages/auth/signup.php'); ?>" class="btn btn-outline-success">
                                    <i class="bi bi-person-plus"></i> Sign Up to Apply
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
