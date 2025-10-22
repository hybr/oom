<?php
$pageTitle = 'Job Opportunities';
require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../../includes/header.php';

// Get current person_id from auth
$currentPersonId = null;
if (Auth::check()) {
    $user = Auth::user();
    $currentPersonId = $user['person_id'] ?? null;
}
?>

<div class="container mt-5">
    <h1><i class="bi bi-briefcase"></i> Job Opportunities</h1>
    <p class="lead">Find local employment opportunities in your area.</p>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="mb-3">
                <button id="searchBtn" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search Open Vacancies
                </button>
            </div>

            <div id="loadingSpinner" class="text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="errorMessage" class="alert alert-danger d-none"></div>

            <div id="vacanciesList"></div>

            <div id="applicationFormContainer" class="d-none mt-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Apply for Position</h5>
                    </div>
                    <div class="card-body">
                        <iframe id="applicationFrame" style="width: 100%; height: 600px; border: none;"></iframe>
                    </div>
                    <div class="card-footer">
                        <button id="closeFormBtn" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const currentPersonId = <?php echo json_encode($currentPersonId); ?>;

document.getElementById('searchBtn').addEventListener('click', searchVacancies);
document.getElementById('closeFormBtn').addEventListener('click', closeApplicationForm);

async function searchVacancies() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const errorMessage = document.getElementById('errorMessage');
    const vacanciesList = document.getElementById('vacanciesList');

    loadingSpinner.classList.remove('d-none');
    errorMessage.classList.add('d-none');
    vacanciesList.innerHTML = '';

    try {
        const response = await fetch('/entities/organization_vacancy/list');
        if (!response.ok) {
            throw new Error('Failed to fetch vacancies');
        }

        const html = await response.text();

        // Parse the HTML to extract vacancy data
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Get the table from the response
        const table = doc.querySelector('table');
        if (!table) {
            vacanciesList.innerHTML = '<div class="alert alert-info">No vacancies found.</div>';
            return;
        }

        // Clone and inject the table
        vacanciesList.innerHTML = '<h4 class="mb-3">Open Vacancies</h4>';
        const tableClone = table.cloneNode(true);
        tableClone.classList.add('table', 'table-hover', 'table-striped');
        vacanciesList.appendChild(tableClone);

        // Add click handlers to table rows
        const rows = tableClone.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function() {
                const firstCell = this.querySelector('td');
                if (firstCell) {
                    const link = firstCell.querySelector('a');
                    if (link) {
                        const href = link.getAttribute('href');
                        const match = href.match(/detail\?id=([^&]+)/);
                        if (match) {
                            const vacancyId = match[1];
                            showApplicationForm(vacancyId);
                        }
                    }
                }
            });
        });

    } catch (error) {
        errorMessage.textContent = 'Error loading vacancies: ' + error.message;
        errorMessage.classList.remove('d-none');
    } finally {
        loadingSpinner.classList.add('d-none');
    }
}

function showApplicationForm(vacancyId) {
    if (!currentPersonId) {
        alert('Please login to apply for jobs');
        window.location.href = '/auth/login';
        return;
    }

    const container = document.getElementById('applicationFormContainer');
    const frame = document.getElementById('applicationFrame');

    // Build URL with pre-populated fields
    const url = `/entities/vacancy_application/create?organization_vacancy_id=${encodeURIComponent(vacancyId)}&person_id=${encodeURIComponent(currentPersonId)}`;

    frame.src = url;
    container.classList.remove('d-none');

    // Scroll to form
    container.scrollIntoView({ behavior: 'smooth' });
}

function closeApplicationForm() {
    const container = document.getElementById('applicationFormContainer');
    const frame = document.getElementById('applicationFrame');

    container.classList.add('d-none');
    frame.src = '';
}
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
