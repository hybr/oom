<?php
require_once __DIR__ . '/../../../../bootstrap.php';

auth()->requireAuth();

require_once ENTITIES_PATH . '/Continent.php';

$continent = new Continent();
$page = $_GET['page'] ?? 1;
$perPage = $_GET['per_page'] ?? 25;
$search = $_GET['search'] ?? '';

// Search filter
$conditions = [];
if ($search) {
    // Note: This is simplified. For proper search, you'd need to modify the all() method
    $records = $continent->search('name', $search);
    $totalRecords = count($records);
    $totalPages = 1;
} else {
    $pagination = $continent->paginate($page, $perPage, $conditions);
    $records = $pagination['data'];
    $totalPages = $pagination['total_pages'];
    $totalRecords = $pagination['total_records'];
}

$pageTitle = 'Continents';
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('pages/dashboard.php'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Continents</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-globe"></i> Continents</h1>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Continent
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search continents..." value="<?php echo escape($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="per_page" class="form-select">
                        <option value="10" <?php echo $perPage == 10 ? 'selected' : ''; ?>>10 per page</option>
                        <option value="25" <?php echo $perPage == 25 ? 'selected' : ''; ?>>25 per page</option>
                        <option value="50" <?php echo $perPage == 50 ? 'selected' : ''; ?>>50 per page</option>
                        <option value="100" <?php echo $perPage == 100 ? 'selected' : ''; ?>>100 per page</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="alert alert-info">
        Showing <strong><?php echo count($records); ?></strong> of <strong><?php echo $totalRecords; ?></strong> continents
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($records)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i> No continents found. <a href="create.php">Create one now</a>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?php echo escape($record['id']); ?></td>
                                    <td><?php echo escape($record['name']); ?></td>
                                    <td><?php echo escape($record['created_at']); ?></td>
                                    <td class="text-nowrap">
                                        <a href="detail.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-info me-1" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this continent?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&per_page=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&per_page=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
