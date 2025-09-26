<?php
// Page-specific JavaScript includes
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<!-- Page-specific JavaScript -->
<?php if ($current_page === 'index'): ?>
<script src="../js/app.js"></script>
<script>
    // Page-specific initialization for index
    document.addEventListener('DOMContentLoaded', function() {
        // Override the global refresh button to refresh orders
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                if (typeof refreshOrders === 'function') {
                    refreshOrders();
                } else if (typeof loadOrders === 'function') {
                    loadOrders();
                }
            });
        }
    });
</script>
<?php elseif ($current_page === 'persons'): ?>
<script>
    class PersonManager {
        constructor() {
            this.persons = [];
            this.currentPage = 1;
            this.itemsPerPage = 10;
            this.init();
        }

        init() {
            this.loadPersons();
            this.bindEvents();
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadPersons());
            document.getElementById('newPersonBtn')?.addEventListener('click', () => this.showNewPersonModal());
        }

        async loadPersons() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entity.php?entity=Person');
                const result = await response.json();

                if (result.success) {
                    this.persons = result.data || [];
                    this.renderPersons();
                    showToast('Persons loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load persons');
                }
            } catch (error) {
                handleApiError(error, 'loading persons');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        renderPersons() {
            // Implementation would be page-specific
            console.log('Rendering persons:', this.persons);
        }

        showNewPersonModal() {
            // Implementation would be page-specific
            console.log('Show new person modal');
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.personManager = new PersonManager();
    });
</script>
<?php elseif ($current_page === 'personcredentials'): ?>
<script>
    // PersonCredential management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Implementation for refreshing credentials
                showToast('Refreshing user credentials...', 'info');
            });
        }
    });
</script>
<?php elseif ($current_page === 'continents'): ?>
<script>
    // Continent management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Implementation for refreshing continents
                showToast('Refreshing continents...', 'info');
            });
        }
    });
</script>
<?php elseif ($current_page === 'languages'): ?>
<script>
    // Language management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Implementation for refreshing languages
                showToast('Refreshing languages...', 'info');
            });
        }
    });
</script>
<?php elseif ($current_page === 'countries'): ?>
<script>
    // Country management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Implementation for refreshing countries
                showToast('Refreshing countries...', 'info');
            });
        }
    });
</script>
<?php elseif ($current_page === 'industry_categories'): ?>
<script>
    // Industry Categories management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                if (typeof window.categoryManager !== 'undefined' && window.categoryManager.loadCategories) {
                    window.categoryManager.loadCategories();
                } else {
                    showToast('Refreshing industry categories...', 'info');
                }
            });
        }
    });
</script>
<?php elseif ($current_page === 'organization_legal_types'): ?>
<script>
    // Organization Legal Types management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                if (typeof window.legalTypeManager !== 'undefined' && window.legalTypeManager.loadLegalTypes) {
                    window.legalTypeManager.loadLegalTypes();
                } else {
                    showToast('Refreshing legal types...', 'info');
                }
            });
        }
    });
</script>
<?php endif; ?>

<!-- Additional page-specific scripts can be included here -->
<?php
// Allow pages to include additional scripts
if (isset($additional_scripts)) {
    echo $additional_scripts;
}
?>