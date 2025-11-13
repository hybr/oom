<?php
require_once __DIR__ . '/bootstrap.php';

use V4L\Core\Auth;
use V4L\Core\MetadataLoader;
use V4L\Core\Database;

Auth::requireLogin();

$currentUser = Auth::getCurrentUser();
$organizations = Auth::getUserOrganizations();
$isSuperAdmin = Auth::isSuperAdmin();

// Get all entities
$entities = MetadataLoader::loadEntities();

// Get all active processes grouped by category
$processes = Database::fetchAll(
    "SELECT * FROM process_graph
     WHERE is_active = 1 AND is_published = 1
     ORDER BY category, name"
);

// Group processes by category
$processCategories = [];
foreach ($processes as $process) {
    $category = $process['category'] ?? 'Other';
    if (!isset($processCategories[$category])) {
        $processCategories[$category] = [];
    }
    $processCategories[$category][] = $process;
}

// Group entities by domain
$entityDomains = [];
foreach ($entities as $entity) {
    $domain = $entity['domain'] ?? 'Other';
    if (!isset($entityDomains[$domain])) {
        $entityDomains[$domain] = [];
    }
    $entityDomains[$domain][] = $entity;
}

$pageTitle = 'Dashboard - ' . APP_NAME;
ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Main content -->
        <main class="col-12 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               id="dashboardSearch"
                               class="form-control form-control-lg"
                               placeholder="Search Business Processes and Entities..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                            <i class="bi bi-x-lg"></i> Clear
                        </button>
                    </div>
                    <small class="text-muted ms-2" id="searchResultsCount"></small>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3>Welcome back, <?= e($currentUser['first_name'] ?? $currentUser['username']) ?>!</h3>
                            <p class="mb-0">You have access to <?= count($organizations) ?> organization(s).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizations -->
            <?php if (!empty($organizations)): ?>
            <h3 class="mb-3">My Organizations</h3>
            <div class="row g-4 mb-4">
                <?php foreach ($organizations as $org): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= e($org['name']) ?></h5>
                            <?php if ($org['description']): ?>
                                <p class="card-text text-muted"><?= e(truncate($org['description'], 80)) ?></p>
                            <?php endif; ?>
                            <a href="organization.php?id=<?= e($org['id']) ?>" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Quick Access -->
            <h3 class="mb-3">Quick Access</h3>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-cart text-primary" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Marketplace</h5>
                            <a href="marketplace.php" class="btn btn-sm btn-outline-primary">Browse</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-briefcase text-success" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Job Vacancies</h5>
                            <a href="vacancies.php" class="btn btn-sm btn-outline-success">View Jobs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-person text-info" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">My Profile</h5>
                            <a href="profile.php" class="btn btn-sm btn-outline-info">Edit Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-gear text-secondary" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Settings</h5>
                            <a href="settings.php" class="btn btn-sm btn-outline-secondary">Configure</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Processes -->
            <?php if (!empty($processCategories)): ?>
            <h3 class="mb-3 mt-5" id="businessProcessesSection">Business Processes</h3>
            <?php foreach ($processCategories as $categoryName => $categoryProcesses): ?>
                <div class="mb-4 process-category-section" data-category="<?= e($categoryName) ?>">
                    <h5 class="text-muted mb-3 category-heading">
                        <i class="bi bi-diagram-3"></i>
                        <?= e(ucwords(str_replace('_', ' ', strtolower($categoryName)))) ?>
                    </h5>
                    <div class="row g-3">
                        <?php foreach ($categoryProcesses as $process): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 searchable-item process-item"
                             data-name="<?= e(strtolower($process['name'])) ?>"
                             data-description="<?= e(strtolower($process['description'] ?? '')) ?>"
                             data-category="<?= e(strtolower($categoryName)) ?>">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-play-circle text-primary"></i>
                                        <?= e($process['name']) ?>
                                    </h6>
                                    <?php if ($process['description']): ?>
                                        <p class="card-text small text-muted mb-3">
                                            <?= e(truncate($process['description'], 80)) ?>
                                        </p>
                                    <?php endif; ?>
                                    <a href="process-execute.php?code=<?= e($process['code']) ?>"
                                       class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-arrow-right-circle"></i> Execute
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- Entities -->
            <?php if (!empty($entityDomains)): ?>
            <h3 class="mb-3 mt-5" id="entitiesSection">Entities</h3>
            <?php foreach ($entityDomains as $domainName => $domainEntities): ?>
                <div class="mb-4 entity-domain-section" data-domain="<?= e($domainName) ?>">
                    <h5 class="text-muted mb-3 category-heading">
                        <i class="bi bi-database"></i>
                        <?= e(ucwords(str_replace('_', ' ', strtolower($domainName)))) ?>
                    </h5>
                    <div class="row g-3">
                        <?php foreach ($domainEntities as $entity): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 searchable-item entity-item"
                             data-name="<?= e(strtolower($entity['name'])) ?>"
                             data-description="<?= e(strtolower($entity['description'] ?? '')) ?>"
                             data-domain="<?= e(strtolower($domainName)) ?>">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-table text-success"></i>
                                        <?= e($entity['name']) ?>
                                    </h6>
                                    <?php if ($entity['description']): ?>
                                        <p class="card-text small text-muted mb-3">
                                            <?= e(truncate($entity['description'], 80)) ?>
                                        </p>
                                    <?php endif; ?>
                                    <a href="entity-list.php?entity=<?= e($entity['code']) ?>"
                                       class="btn btn-sm btn-outline-success w-100">
                                        <i class="bi bi-arrow-right-circle"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
.hover-shadow {
    transition: box-shadow 0.3s ease-in-out;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.searchable-item {
    transition: opacity 0.2s ease-in-out;
}
.searchable-item.hidden {
    display: none !important;
}
.category-heading.hidden {
    display: none !important;
}
#dashboardSearch:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('dashboardSearch');
    const clearButton = document.getElementById('clearSearch');
    const searchResultsCount = document.getElementById('searchResultsCount');
    const searchableItems = document.querySelectorAll('.searchable-item');
    const processCategorySections = document.querySelectorAll('.process-category-section');
    const entityDomainSections = document.querySelectorAll('.entity-domain-section');
    const businessProcessesSection = document.getElementById('businessProcessesSection');
    const entitiesSection = document.getElementById('entitiesSection');

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();

        // Show/hide clear button
        clearButton.style.display = searchTerm ? 'block' : 'none';

        if (!searchTerm) {
            // Show all items and sections
            searchableItems.forEach(item => item.classList.remove('hidden'));
            processCategorySections.forEach(section => {
                section.classList.remove('hidden');
                section.querySelector('.category-heading').classList.remove('hidden');
            });
            entityDomainSections.forEach(section => {
                section.classList.remove('hidden');
                section.querySelector('.category-heading').classList.remove('hidden');
            });
            if (businessProcessesSection) businessProcessesSection.classList.remove('hidden');
            if (entitiesSection) entitiesSection.classList.remove('hidden');
            searchResultsCount.textContent = '';
            return;
        }

        let visibleCount = 0;
        let visibleProcesses = 0;
        let visibleEntities = 0;

        // Filter items
        searchableItems.forEach(item => {
            const name = item.dataset.name || '';
            const description = item.dataset.description || '';
            const category = item.dataset.category || '';
            const domain = item.dataset.domain || '';

            const matches = name.includes(searchTerm) ||
                          description.includes(searchTerm) ||
                          category.includes(searchTerm) ||
                          domain.includes(searchTerm);

            if (matches) {
                item.classList.remove('hidden');
                visibleCount++;
                if (item.classList.contains('process-item')) {
                    visibleProcesses++;
                } else if (item.classList.contains('entity-item')) {
                    visibleEntities++;
                }
            } else {
                item.classList.add('hidden');
            }
        });

        // Hide empty category sections
        processCategorySections.forEach(section => {
            const visibleItems = section.querySelectorAll('.searchable-item:not(.hidden)');
            if (visibleItems.length === 0) {
                section.classList.add('hidden');
            } else {
                section.classList.remove('hidden');
            }
        });

        entityDomainSections.forEach(section => {
            const visibleItems = section.querySelectorAll('.searchable-item:not(.hidden)');
            if (visibleItems.length === 0) {
                section.classList.add('hidden');
            } else {
                section.classList.remove('hidden');
            }
        });

        // Hide section headers if no items in that section
        if (businessProcessesSection) {
            businessProcessesSection.classList.toggle('hidden', visibleProcesses === 0);
        }
        if (entitiesSection) {
            entitiesSection.classList.toggle('hidden', visibleEntities === 0);
        }

        // Update results count
        if (visibleCount === 0) {
            searchResultsCount.textContent = 'No results found';
            searchResultsCount.className = 'text-danger ms-2';
        } else {
            const processText = visibleProcesses > 0 ? `${visibleProcesses} process${visibleProcesses !== 1 ? 'es' : ''}` : '';
            const entityText = visibleEntities > 0 ? `${visibleEntities} ${visibleEntities !== 1 ? 'entities' : 'entity'}` : '';
            const parts = [processText, entityText].filter(p => p);
            searchResultsCount.textContent = `Found ${parts.join(' and ')}`;
            searchResultsCount.className = 'text-success ms-2';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Escape') {
            searchInput.value = '';
            performSearch();
            searchInput.blur();
        }
    });

    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
        searchInput.focus();
    });

    // Focus search on Ctrl/Cmd + K
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });
});
</script>

<?php
$content = ob_get_clean();
render('layouts/main', compact('pageTitle', 'content'));
