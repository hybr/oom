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

            // Form event handlers
            document.getElementById('addPersonForm')?.addEventListener('submit', (e) => this.handleAddPerson(e));
            document.getElementById('editPersonForm')?.addEventListener('submit', (e) => this.handleEditPerson(e));

            // Search and filter handlers
            document.getElementById('searchPersons')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter(e));
        }

        async loadPersons() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/Person');
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
            const tableContainer = document.getElementById('personsTable');
            if (!tableContainer) return;

            if (this.persons.length === 0) {
                tableContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">👤</span>
                            <h5 class="mt-3">No persons found</h5>
                            <p>Start by adding your first person using the "Add Person" button above.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Update statistics
            this.updateStatistics();

            // Build responsive HTML
            let html = `
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            // Desktop table rows
            this.persons.forEach(person => {
                const fullName = `${person.first_name || ''} ${person.last_name || ''}`.trim();
                const statusBadge = person.status === 'active'
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const createdDate = person.created_at ? new Date(person.created_at).toLocaleDateString() : 'N/A';

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">👤</div>
                                <div>
                                    <div class="fw-semibold">${fullName || 'N/A'}</div>
                                    ${person.date_of_birth ? `<small class="text-muted">Born: ${person.date_of_birth}</small>` : ''}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>${person.email || 'N/A'}</div>
                            <small class="text-muted">${person.phone || 'No phone'}</small>
                        </td>
                        <td>${statusBadge}</td>
                        <td>${createdDate}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info" onclick="window.personManager.viewPerson(${person.id})" title="View Details">
                                    👁️
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="window.personManager.editPerson(${person.id})" title="Edit">
                                    ✏️
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="window.personManager.deletePerson(${person.id})" title="Delete">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (hidden on desktop) -->
                <div class="d-lg-none">
            `;

            // Mobile card layout
            this.persons.forEach(person => {
                const fullName = `${person.first_name || ''} ${person.last_name || ''}`.trim();
                const statusBadge = person.status === 'active'
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const createdDate = person.created_at ? new Date(person.created_at).toLocaleDateString() : 'N/A';

                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <div class="text-center">
                                        <span style="font-size: 2rem;">👤</span>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">${fullName || 'N/A'}</h6>
                                    <div class="small text-muted mb-1">
                                        ${person.email ? `📧 ${person.email}` : ''}
                                    </div>
                                    <div class="small text-muted mb-1">
                                        ${person.phone ? `📞 ${person.phone}` : ''}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        ${statusBadge}
                                        <small class="text-muted">• ${createdDate}</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            ⚙️
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.personManager.viewPerson(${person.id})">
                                                    👁️ View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.personManager.editPerson(${person.id})">
                                                    ✏️ Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="window.personManager.deletePerson(${person.id})">
                                                    🗑️ Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                </div>
            `;

            tableContainer.innerHTML = html;
        }

        updateStatistics() {
            const totalElement = document.getElementById('totalPersons');
            const activeElement = document.getElementById('activePersons');
            const inactiveElement = document.getElementById('inactivePersons');
            const recentElement = document.getElementById('recentPersons');

            if (totalElement) totalElement.textContent = this.persons.length;

            const activeCount = this.persons.filter(p => p.status === 'active').length;
            const inactiveCount = this.persons.filter(p => p.status === 'inactive').length;

            if (activeElement) activeElement.textContent = activeCount;
            if (inactiveElement) inactiveElement.textContent = inactiveCount;

            // Recent persons (created in last 7 days)
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            const recentCount = this.persons.filter(p => {
                if (!p.created_at) return false;
                return new Date(p.created_at) > weekAgo;
            }).length;

            if (recentElement) recentElement.textContent = recentCount;
        }

        showNewPersonModal() {
            const modal = new bootstrap.Modal(document.getElementById('addPersonModal'));
            modal.show();
        }

        viewPerson(id) {
            const person = this.persons.find(p => p.id == id);
            if (!person) return;

            // Populate view modal
            const fullName = `${person.first_name || ''} ${person.last_name || ''}`.trim();
            document.getElementById('viewFullName').textContent = fullName || 'N/A';
            document.getElementById('viewEmail').textContent = person.email || 'N/A';
            document.getElementById('viewPhone').textContent = person.phone || 'N/A';
            document.getElementById('viewDateOfBirth').textContent = person.date_of_birth || 'N/A';
            document.getElementById('viewGender').textContent = person.gender || 'N/A';
            document.getElementById('viewAddress').textContent = person.address || 'N/A';
            document.getElementById('viewPersonId').textContent = person.id || 'N/A';

            // Calculate and display age
            let ageText = 'N/A';
            if (person.date_of_birth) {
                const birthDate = new Date(person.date_of_birth);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                ageText = age >= 0 ? `${age} years old` : 'N/A';
            }
            document.getElementById('viewAge').textContent = ageText;

            // Status with badge
            const statusElement = document.getElementById('viewStatus');
            if (person.status === 'active') {
                statusElement.innerHTML = '<span class="badge bg-success">Active</span>';
            } else {
                statusElement.innerHTML = '<span class="badge bg-secondary">Inactive</span>';
            }

            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleString();
            };

            document.getElementById('viewCreatedAt').textContent = formatDate(person.created_at);
            document.getElementById('viewUpdatedAt').textContent = formatDate(person.updated_at);

            // Set up action buttons
            const editBtn = document.getElementById('viewEditBtn');
            const deleteBtn = document.getElementById('viewDeleteBtn');

            // Remove existing listeners
            editBtn.onclick = null;
            deleteBtn.onclick = null;

            // Add new listeners
            editBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewPersonModal')).hide();
                this.editPerson(id);
            };

            deleteBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewPersonModal')).hide();
                this.deletePerson(id);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewPersonModal'));
            modal.show();
        }

        editPerson(id) {
            const person = this.persons.find(p => p.id == id);
            if (!person) return;

            // Populate edit form
            document.getElementById('editPersonId').value = person.id;
            document.getElementById('editFirstName').value = person.first_name || '';
            document.getElementById('editLastName').value = person.last_name || '';
            document.getElementById('editEmail').value = person.email || '';
            document.getElementById('editPhone').value = person.phone || '';
            document.getElementById('editDateOfBirth').value = person.date_of_birth || '';
            document.getElementById('editGender').value = person.gender || '';
            document.getElementById('editAddress').value = person.address || '';

            const modal = new bootstrap.Modal(document.getElementById('editPersonModal'));
            modal.show();
        }

        async deletePerson(id) {
            const person = this.persons.find(p => p.id == id);
            if (!person) return;

            const fullName = `${person.first_name || ''} ${person.last_name || ''}`.trim();
            if (!confirm(`Are you sure you want to delete ${fullName || 'this person'}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/Person/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Person deleted successfully', 'success');
                    this.loadPersons(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to delete person');
                }
            } catch (error) {
                handleApiError(error, 'deleting person');
            }
        }

        async handleAddPerson(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/api/entities/Person', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Person added successfully', 'success');
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addPersonModal')).hide();
                    this.loadPersons(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to add person');
                }
            } catch (error) {
                handleApiError(error, 'adding person');
            }
        }

        async handleEditPerson(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const id = data.id;
            delete data.id; // Remove id from data object

            try {
                const response = await fetch(`/api/entities/Person/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Person updated successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editPersonModal')).hide();
                    this.loadPersons(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to update person');
                }
            } catch (error) {
                handleApiError(error, 'updating person');
            }
        }

        handleSearch(e) {
            // Simple client-side search implementation
            this.renderFilteredPersons();
        }

        handleFilter(e) {
            // Simple client-side filter implementation
            this.renderFilteredPersons();
        }

        renderFilteredPersons() {
            const searchTerm = document.getElementById('searchPersons')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';

            let filteredPersons = this.persons;

            // Apply search filter
            if (searchTerm) {
                filteredPersons = filteredPersons.filter(person => {
                    const fullName = `${person.first_name || ''} ${person.last_name || ''}`.toLowerCase();
                    const email = (person.email || '').toLowerCase();
                    const phone = (person.phone || '').toLowerCase();
                    return fullName.includes(searchTerm) || email.includes(searchTerm) || phone.includes(searchTerm);
                });
            }

            // Apply status filter
            if (statusFilter) {
                filteredPersons = filteredPersons.filter(person => person.status === statusFilter);
            }

            // Temporarily replace persons array for rendering
            const originalPersons = this.persons;
            this.persons = filteredPersons;
            this.renderPersons();
            this.persons = originalPersons; // Restore original array
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.personManager = new PersonManager();
    });
</script>
<?php elseif ($current_page === 'personcredentials'): ?>
<script>
    class PersonCredentialManager {
        constructor() {
            this.credentials = [];
            this.persons = [];
            this.filteredCredentials = [];
            this.init();
        }

        init() {
            this.loadPersons();
            this.loadCredentials();
            this.bindEvents();
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadCredentials());
            document.getElementById('newCredentialBtn')?.addEventListener('click', () => this.showNewCredentialModal());

            // Form event handlers
            document.getElementById('addCredentialForm')?.addEventListener('submit', (e) => this.handleAddCredential(e));
            document.getElementById('editCredentialForm')?.addEventListener('submit', (e) => this.handleEditCredential(e));

            // Search and filter handlers
            document.getElementById('searchCredentials')?.addEventListener('input', (e) => this.handleFilter());
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter());
            document.getElementById('securityFilter')?.addEventListener('change', (e) => this.handleFilter());
            document.getElementById('loginFilter')?.addEventListener('change', (e) => this.handleFilter());

            // Password toggle handlers
            document.getElementById('togglePassword')?.addEventListener('click', () => this.togglePasswordVisibility('password'));
            document.getElementById('toggleEditPassword')?.addEventListener('click', () => this.togglePasswordVisibility('editNewPassword'));

            // Password confirmation handlers
            document.getElementById('confirmPassword')?.addEventListener('input', () => this.checkPasswordMatch('add'));
            document.getElementById('editConfirmPassword')?.addEventListener('input', () => this.checkPasswordMatch('edit'));

            // Export handler
            document.getElementById('exportBtn')?.addEventListener('click', () => this.exportCredentials());

            // Reset attempts handler
            document.getElementById('resetLoginAttemptsBtn')?.addEventListener('click', () => this.resetLoginAttempts());

            // Generate reset token handler
            document.getElementById('generateResetTokenBtn')?.addEventListener('click', () => this.generateResetToken());
        }

        async loadPersons() {
            try {
                const response = await fetch('/api/entities/Person');
                const result = await response.json();

                if (result.success) {
                    this.persons = result.data || [];
                    this.populatePersonDropdowns();
                }
            } catch (error) {
                console.error('Failed to load persons:', error);
            }
        }

        populatePersonDropdowns() {
            const selects = ['person_id'];

            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Person</option>';

                    this.persons.forEach(person => {
                        const option = document.createElement('option');
                        option.value = person.id;
                        option.textContent = `${person.first_name} ${person.last_name}`;
                        select.appendChild(option);
                    });
                }
            });
        }

        async loadCredentials() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/PersonCredential');
                const result = await response.json();

                if (result.success) {
                    this.credentials = result.data || [];
                    this.filteredCredentials = [...this.credentials];
                    this.renderCredentials();
                    this.updateStatistics();
                    showToast('User credentials loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load credentials');
                }
            } catch (error) {
                handleApiError(error, 'loading user credentials');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        updateStatistics() {
            const totalCredentials = this.credentials.length;
            const activeCredentials = this.credentials.filter(c => c.is_active == 1).length;
            const lockedCredentials = this.credentials.filter(c => this.isLocked(c)).length;
            const recentLogins = this.credentials.filter(c => this.hasRecentLogin(c)).length;
            const failedAttempts = this.credentials.reduce((sum, c) => sum + (c.login_attempts || 0), 0);
            const passwordResets = this.credentials.filter(c => c.password_reset_token).length;

            document.getElementById('totalCredentials').textContent = totalCredentials;
            document.getElementById('activeCredentials').textContent = activeCredentials;
            document.getElementById('lockedCredentials').textContent = lockedCredentials;
            document.getElementById('recentLogins').textContent = recentLogins;
            document.getElementById('failedAttempts').textContent = failedAttempts;
            document.getElementById('passwordResets').textContent = passwordResets;
        }

        handleFilter() {
            const searchTerm = document.getElementById('searchCredentials')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';
            const securityFilter = document.getElementById('securityFilter')?.value || '';
            const loginFilter = document.getElementById('loginFilter')?.value || '';

            this.filteredCredentials = this.credentials.filter(credential => {
                const person = this.getPersonById(credential.person_id);
                const personName = person ? `${person.first_name} ${person.last_name}` : '';

                const matchesSearch = !searchTerm ||
                    (credential.username?.toLowerCase().includes(searchTerm)) ||
                    (personName.toLowerCase().includes(searchTerm));

                const matchesStatus = !statusFilter ||
                    (statusFilter === 'active' && credential.is_active == 1) ||
                    (statusFilter === 'inactive' && credential.is_active == 0) ||
                    (statusFilter === 'locked' && this.isLocked(credential));

                const matchesSecurity = !securityFilter ||
                    (securityFilter === 'complete' && this.hasCompleteSecurityQuestions(credential)) ||
                    (securityFilter === 'incomplete' && !this.hasCompleteSecurityQuestions(credential));

                const matchesLogin = !loginFilter ||
                    (loginFilter === 'recent' && this.hasRecentLogin(credential)) ||
                    (loginFilter === 'never' && !credential.last_login);

                return matchesSearch && matchesStatus && matchesSecurity && matchesLogin;
            });

            this.renderCredentials();
        }

        renderCredentials() {
            const container = document.getElementById('credentialsDisplay');
            if (!container) return;

            if (this.filteredCredentials.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🔐</span>
                            <h5 class="mt-3">No user credentials found</h5>
                            <p>Start by adding your first user credential using the "Add User" button above.</p>
                        </div>
                    </div>
                `;
                document.getElementById('credentialCount').textContent = '0 users';
                return;
            }

            document.getElementById('credentialCount').textContent = `${this.filteredCredentials.length} user${this.filteredCredentials.length !== 1 ? 's' : ''}`;

            // Build responsive HTML
            let html = `
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Person</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Login Attempts</th>
                                    <th>Security</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${this.filteredCredentials.map(credential => this.renderCredentialRow(credential)).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (visible on mobile only) -->
                <div class="d-lg-none">
                    ${this.filteredCredentials.map(credential => this.renderCredentialCard(credential)).join('')}
                </div>
            `;

            container.innerHTML = html;
        }

        renderCredentialRow(credential) {
            const person = this.getPersonById(credential.person_id);
            const personName = person ? `${person.first_name} ${person.last_name}` : 'Unknown';

            const statusBadge = this.getStatusBadge(credential);
            const securityBadge = this.getSecurityBadge(credential);
            const lastLogin = credential.last_login ? new Date(credential.last_login).toLocaleDateString() : 'Never';

            return `
                <tr>
                    <td>
                        <div class="fw-bold">${escapeHtml(credential.username || '')}</div>
                        ${credential.id ? `<small class="text-muted">ID: ${credential.id}</small>` : ''}
                    </td>
                    <td>${escapeHtml(personName)}</td>
                    <td>${statusBadge}</td>
                    <td>${lastLogin}</td>
                    <td>
                        <span class="badge ${credential.login_attempts > 0 ? 'bg-warning' : 'bg-success'} text-dark">
                            ${credential.login_attempts || 0}
                        </span>
                    </td>
                    <td>${securityBadge}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="window.credentialManager.viewCredential(${credential.id})"
                                    title="View Details">
                                👁️
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="window.credentialManager.editCredential(${credential.id})"
                                    title="Edit">
                                ✏️
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        renderCredentialCard(credential) {
            const person = this.getPersonById(credential.person_id);
            const personName = person ? `${person.first_name} ${person.last_name}` : 'Unknown';

            const statusBadge = this.getStatusBadge(credential);
            const securityBadge = this.getSecurityBadge(credential);
            const lastLogin = credential.last_login ? new Date(credential.last_login).toLocaleDateString() : 'Never';

            return `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">${escapeHtml(credential.username || '')}</h6>
                                <small class="text-muted">${escapeHtml(personName)}</small>
                                <div class="mt-1">
                                    ${statusBadge}
                                    ${securityBadge}
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                    ⋮
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="window.credentialManager.viewCredential(${credential.id})">👁️ View Details</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="window.credentialManager.editCredential(${credential.id})">✏️ Edit</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="window.credentialManager.resetPassword(${credential.id})">🔑 Reset Password</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="window.credentialManager.deleteCredential(${credential.id})">🗑️ Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="small text-muted">
                            <div><strong>Last Login:</strong> ${lastLogin}</div>
                            <div><strong>Login Attempts:</strong> ${credential.login_attempts || 0}</div>
                        </div>
                    </div>
                </div>
            `;
        }

        getStatusBadge(credential) {
            if (this.isLocked(credential)) {
                return '<span class="badge bg-danger">Locked</span>';
            } else if (credential.is_active == 1) {
                return '<span class="badge bg-success">Active</span>';
            } else {
                return '<span class="badge bg-secondary">Inactive</span>';
            }
        }

        getSecurityBadge(credential) {
            if (this.hasCompleteSecurityQuestions(credential)) {
                return '<span class="badge bg-success">Complete</span>';
            } else {
                return '<span class="badge bg-warning text-dark">Incomplete</span>';
            }
        }

        isLocked(credential) {
            return credential.locked_until && new Date(credential.locked_until) > new Date();
        }

        hasRecentLogin(credential) {
            if (!credential.last_login) return false;
            const lastLogin = new Date(credential.last_login);
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            return lastLogin > thirtyDaysAgo;
        }

        hasCompleteSecurityQuestions(credential) {
            return credential.security_question_1 && credential.security_question_2 && credential.security_question_3;
        }

        getPersonById(personId) {
            return this.persons.find(p => p.id == personId);
        }

        showNewCredentialModal() {
            document.getElementById('addCredentialForm').reset();
            const modal = new bootstrap.Modal(document.getElementById('addCredentialModal'));
            modal.show();
        }

        async handleAddCredential(event) {
            event.preventDefault();
            const form = event.target;

            if (!this.checkPasswordMatch('add')) {
                return;
            }

            const formData = new FormData(form);
            const credentialData = {};

            // Convert form data to object
            for (let [key, value] of formData.entries()) {
                if (value !== '') {
                    if (form.elements[key]?.type === 'checkbox') {
                        credentialData[key] = form.elements[key].checked ? 1 : 0;
                    } else {
                        credentialData[key] = value;
                    }
                }
            }

            try {
                const response = await fetch('/api/entities/PersonCredential', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(credentialData)
                });

                const result = await response.json();

                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('addCredentialModal')).hide();
                    form.reset();
                    await this.loadCredentials();
                    showToast('User credential created successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to create credential');
                }
            } catch (error) {
                handleApiError(error, 'creating user credential');
            }
        }

        viewCredential(id) {
            const credential = this.credentials.find(c => c.id == id);
            if (!credential) return;

            const person = this.getPersonById(credential.person_id);

            // Populate view modal
            document.getElementById('viewUsername').textContent = credential.username || '-';
            document.getElementById('viewPersonName').textContent = person ? `${person.first_name} ${person.last_name}` : '-';
            document.getElementById('viewStatus').innerHTML = this.getStatusBadge(credential);
            document.getElementById('viewLastLogin').textContent = credential.last_login ? new Date(credential.last_login).toLocaleString() : 'Never';
            document.getElementById('viewLoginAttempts').textContent = credential.login_attempts || 0;
            document.getElementById('viewLockedUntil').textContent = credential.locked_until ? new Date(credential.locked_until).toLocaleString() : 'Not locked';
            document.getElementById('viewCreatedAt').textContent = new Date(credential.created_at).toLocaleDateString();
            document.getElementById('viewUpdatedAt').textContent = new Date(credential.updated_at).toLocaleDateString();

            // Security questions
            this.populateSecurityQuestionsView(credential);

            // Password reset token
            this.populatePasswordResetView(credential);

            // Bind action buttons
            const editBtn = document.getElementById('viewEditBtn');
            const deleteBtn = document.getElementById('viewDeleteBtn');
            const resetPasswordBtn = document.getElementById('viewResetPasswordBtn');
            const unlockBtn = document.getElementById('viewUnlockBtn');

            editBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewCredentialModal')).hide();
                this.editCredential(id);
            };

            deleteBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewCredentialModal')).hide();
                this.deleteCredential(id);
            };

            resetPasswordBtn.onclick = () => {
                this.resetPassword(id);
            };

            unlockBtn.onclick = () => {
                this.unlockAccount(id);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewCredentialModal'));
            modal.show();
        }

        populateSecurityQuestionsView(credential) {
            const container = document.getElementById('viewSecurityQuestions');
            const questions = [];

            if (credential.security_question_1) {
                questions.push(`<div class="mb-2"><strong>Q1:</strong> ${escapeHtml(credential.security_question_1)}</div>`);
            }
            if (credential.security_question_2) {
                questions.push(`<div class="mb-2"><strong>Q2:</strong> ${escapeHtml(credential.security_question_2)}</div>`);
            }
            if (credential.security_question_3) {
                questions.push(`<div class="mb-2"><strong>Q3:</strong> ${escapeHtml(credential.security_question_3)}</div>`);
            }

            if (questions.length === 0) {
                container.innerHTML = '<p class="text-muted">No security questions configured</p>';
            } else {
                container.innerHTML = questions.join('');
            }
        }

        populatePasswordResetView(credential) {
            const section = document.getElementById('passwordResetSection');

            if (credential.password_reset_token && credential.password_reset_expires) {
                const expiresAt = new Date(credential.password_reset_expires);
                const isExpired = expiresAt < new Date();

                document.getElementById('viewResetToken').textContent = credential.password_reset_token;
                document.getElementById('viewResetExpires').textContent = expiresAt.toLocaleString() + (isExpired ? ' (Expired)' : '');
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        editCredential(id) {
            const credential = this.credentials.find(c => c.id == id);
            if (!credential) return;

            const person = this.getPersonById(credential.person_id);

            // Populate edit form
            document.getElementById('editCredentialId').value = credential.id;
            document.getElementById('editPersonName').value = person ? `${person.first_name} ${person.last_name}` : '';
            document.getElementById('editPersonId').value = credential.person_id;
            document.getElementById('editUsername').value = credential.username || '';
            document.getElementById('editSecurityQuestion1').value = credential.security_question_1 || '';
            document.getElementById('editSecurityQuestion2').value = credential.security_question_2 || '';
            document.getElementById('editSecurityQuestion3').value = credential.security_question_3 || '';
            document.getElementById('editIsActive').checked = credential.is_active == 1;

            const modal = new bootstrap.Modal(document.getElementById('editCredentialModal'));
            modal.show();
        }

        async handleEditCredential(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            const id = formData.get('id');
            const credentialData = {};

            for (let [key, value] of formData.entries()) {
                if (key !== 'id') {
                    if (form.elements[key]?.type === 'checkbox') {
                        credentialData[key] = form.elements[key].checked ? 1 : 0;
                    } else if (value !== '') {
                        credentialData[key] = value;
                    }
                }
            }

            // Handle password change
            if (credentialData.new_password && credentialData.new_password !== credentialData.confirm_password) {
                showToast('Passwords do not match', 'danger');
                return;
            }

            if (credentialData.new_password) {
                credentialData.password = credentialData.new_password;
                delete credentialData.new_password;
                delete credentialData.confirm_password;
            }

            try {
                const response = await fetch(`/api/entities/PersonCredential/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(credentialData)
                });

                const result = await response.json();

                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editCredentialModal')).hide();
                    await this.loadCredentials();
                    showToast('User credential updated successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to update credential');
                }
            } catch (error) {
                handleApiError(error, 'updating user credential');
            }
        }

        async deleteCredential(id) {
            const credential = this.credentials.find(c => c.id == id);
            if (!credential) return;

            if (!confirm(`Are you sure you want to delete the credential for ${credential.username}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/PersonCredential/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    await this.loadCredentials();
                    showToast('User credential deleted successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to delete credential');
                }
            } catch (error) {
                handleApiError(error, 'deleting user credential');
            }
        }

        async resetLoginAttempts() {
            const id = document.getElementById('editCredentialId').value;

            try {
                const response = await fetch(`/api/entities/PersonCredential/${id}/reset-attempts`, {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Login attempts reset successfully', 'success');
                    await this.loadCredentials();
                } else {
                    throw new Error(result.message || 'Failed to reset attempts');
                }
            } catch (error) {
                handleApiError(error, 'resetting login attempts');
            }
        }

        async generateResetToken() {
            const id = document.getElementById('editCredentialId').value;

            try {
                const response = await fetch(`/api/entities/PersonCredential/${id}/generate-token`, {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.success) {
                    showToast(`Reset token generated: ${result.token}`, 'success');
                    await this.loadCredentials();
                } else {
                    throw new Error(result.message || 'Failed to generate token');
                }
            } catch (error) {
                handleApiError(error, 'generating reset token');
            }
        }

        async unlockAccount(id) {
            try {
                const response = await fetch(`/api/entities/PersonCredential/${id}/unlock`, {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Account unlocked successfully', 'success');
                    await this.loadCredentials();
                } else {
                    throw new Error(result.message || 'Failed to unlock account');
                }
            } catch (error) {
                handleApiError(error, 'unlocking account');
            }
        }

        async resetPassword(id) {
            const credential = this.credentials.find(c => c.id == id);
            if (!credential) return;

            const newPassword = prompt('Enter new password (minimum 8 characters):');
            if (!newPassword || newPassword.length < 8) {
                showToast('Password must be at least 8 characters long', 'warning');
                return;
            }

            try {
                const response = await fetch(`/api/entities/PersonCredential/${id}/reset-password`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ new_password: newPassword })
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Password reset successfully', 'success');
                    await this.loadCredentials();
                } else {
                    throw new Error(result.message || 'Failed to reset password');
                }
            } catch (error) {
                handleApiError(error, 'resetting password');
            }
        }

        togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }

        checkPasswordMatch(mode) {
            let passwordField, confirmField;

            if (mode === 'add') {
                passwordField = document.getElementById('password');
                confirmField = document.getElementById('confirmPassword');
            } else {
                passwordField = document.getElementById('editNewPassword');
                confirmField = document.getElementById('editConfirmPassword');
            }

            if (confirmField.value && passwordField.value !== confirmField.value) {
                confirmField.classList.add('is-invalid');
                return false;
            } else {
                confirmField.classList.remove('is-invalid');
                return true;
            }
        }

        exportCredentials() {
            const csvContent = this.generateCSV();
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `user-credentials-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            showToast('Credentials exported successfully', 'success');
        }

        generateCSV() {
            const headers = ['Username', 'Person', 'Status', 'Last Login', 'Login Attempts', 'Security Complete', 'Created'];
            const rows = this.filteredCredentials.map(credential => {
                const person = this.getPersonById(credential.person_id);
                const personName = person ? `${person.first_name} ${person.last_name}` : 'Unknown';

                return [
                    credential.username || '',
                    personName,
                    credential.is_active == 1 ? 'Active' : 'Inactive',
                    credential.last_login ? new Date(credential.last_login).toLocaleDateString() : 'Never',
                    credential.login_attempts || 0,
                    this.hasCompleteSecurityQuestions(credential) ? 'Yes' : 'No',
                    new Date(credential.created_at).toLocaleDateString()
                ];
            });

            return [headers, ...rows].map(row =>
                row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
            ).join('\n');
        }
    }

    // Initialize the manager when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        window.credentialManager = new PersonCredentialManager();

        // Override the global refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                if (window.credentialManager && window.credentialManager.loadCredentials) {
                    window.credentialManager.loadCredentials();
                }
            });
        }
    });
</script>
<?php elseif ($current_page === 'continents'): ?>
<script>
    class ContinentManager {
        constructor() {
            this.continents = [];
            this.currentPage = 1;
            this.itemsPerPage = 10;
            this.init();
        }

        init() {
            this.loadContinents();
            this.bindEvents();
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadContinents());
            document.getElementById('newContinentBtn')?.addEventListener('click', () => this.showNewContinentModal());

            // Form event handlers
            document.getElementById('addContinentForm')?.addEventListener('submit', (e) => this.handleAddContinent(e));
            document.getElementById('editContinentForm')?.addEventListener('submit', (e) => this.handleEditContinent(e));

            // Search and filter handlers
            document.getElementById('searchContinents')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter(e));
        }

        async loadContinents() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/Continent');
                const result = await response.json();

                if (result.success) {
                    this.continents = result.data || [];
                    this.renderContinents();
                    showToast('Continents loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load continents');
                }
            } catch (error) {
                handleApiError(error, 'loading continents');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        renderContinents() {
            const tableContainer = document.getElementById('continentsTable');
            if (!tableContainer) return;

            if (this.continents.length === 0) {
                tableContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🌍</span>
                            <h5 class="mt-3">No continents found</h5>
                            <p>Start by adding your first continent using the "Add Continent" button above.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Update statistics
            this.updateStatistics();

            // Build responsive HTML
            let html = `
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Area & Population</th>
                                    <th>Countries</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            // Desktop table rows
            this.continents.forEach(continent => {
                const statusBadge = continent.is_active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const formattedArea = continent.area_km2 ? this.formatNumber(continent.area_km2) + ' km²' : 'N/A';
                const formattedPopulation = continent.population ? this.formatPopulation(continent.population) : 'N/A';

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">🌍</div>
                                <div>
                                    <div class="fw-semibold">${continent.name || 'N/A'}</div>
                                    <small class="text-muted">${continent.largest_country || ''}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">${continent.code || 'N/A'}</span>
                        </td>
                        <td>
                            <div>${formattedArea}</div>
                            <small class="text-muted">👥 ${formattedPopulation}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">${continent.countries_count || 0} countries</span>
                        </td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info" onclick="window.continentManager.viewContinent(${continent.id})" title="View Details">
                                    👁️
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="window.continentManager.editContinent(${continent.id})" title="Edit">
                                    ✏️
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="window.continentManager.deleteContinent(${continent.id})" title="Delete">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (hidden on desktop) -->
                <div class="d-lg-none">
            `;

            // Mobile card layout
            this.continents.forEach(continent => {
                const statusBadge = continent.is_active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const formattedArea = continent.area_km2 ? this.formatNumber(continent.area_km2) + ' km²' : 'N/A';
                const formattedPopulation = continent.population ? this.formatPopulation(continent.population) : 'N/A';

                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <div class="text-center">
                                        <span style="font-size: 2rem;">🌍</span>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">${continent.name || 'N/A'}</h6>
                                    <div class="small text-muted mb-1">
                                        <span class="badge bg-primary me-1">${continent.code || 'N/A'}</span>
                                        ${statusBadge}
                                    </div>
                                    <div class="small text-muted mb-1">
                                        📏 ${formattedArea} • 👥 ${formattedPopulation}
                                    </div>
                                    <div class="small text-muted">
                                        🏴 ${continent.countries_count || 0} countries
                                        ${continent.largest_country ? ` • 🏆 ${continent.largest_country}` : ''}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            ⚙️
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.continentManager.viewContinent(${continent.id})">
                                                    👁️ View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.continentManager.editContinent(${continent.id})">
                                                    ✏️ Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="window.continentManager.deleteContinent(${continent.id})">
                                                    🗑️ Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                </div>
            `;

            tableContainer.innerHTML = html;
        }

        updateStatistics() {
            const totalElement = document.getElementById('totalContinents');
            const activeElement = document.getElementById('activeContinents');
            const inactiveElement = document.getElementById('inactiveContinents');
            const populationElement = document.getElementById('totalPopulation');

            if (totalElement) totalElement.textContent = this.continents.length;

            const activeCount = this.continents.filter(c => c.is_active == 1).length;
            const inactiveCount = this.continents.filter(c => c.is_active == 0).length;

            if (activeElement) activeElement.textContent = activeCount;
            if (inactiveElement) inactiveElement.textContent = inactiveCount;

            // Calculate total population
            const totalPopulation = this.continents.reduce((sum, c) => sum + (parseInt(c.population) || 0), 0);
            if (populationElement) populationElement.textContent = this.formatPopulation(totalPopulation);
        }

        formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }

        formatPopulation(population) {
            if (!population) return '0';

            const num = parseInt(population);
            if (num >= 1000000000) {
                return (num / 1000000000).toFixed(1) + 'B';
            } else if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        showNewContinentModal() {
            const modal = new bootstrap.Modal(document.getElementById('addContinentModal'));
            modal.show();
        }

        viewContinent(id) {
            const continent = this.continents.find(c => c.id == id);
            if (!continent) return;

            // Populate view modal
            document.getElementById('viewName').textContent = continent.name || 'N/A';
            document.getElementById('viewCode').textContent = continent.code || 'N/A';
            document.getElementById('viewArea').textContent = continent.area_km2 ? this.formatNumber(continent.area_km2) + ' km²' : 'N/A';
            document.getElementById('viewPopulation').textContent = continent.population ? this.formatNumber(continent.population) : 'N/A';
            document.getElementById('viewCountriesCount').textContent = continent.countries_count || '0';
            document.getElementById('viewLargestCountry').textContent = continent.largest_country || 'N/A';
            document.getElementById('viewDescription').textContent = continent.description || 'N/A';
            document.getElementById('viewContinentId').textContent = continent.id || 'N/A';

            // Calculate and display population density
            let densityText = 'N/A';
            if (continent.area_km2 && continent.population) {
                const density = Math.round(continent.population / continent.area_km2);
                densityText = density + ' people/km²';
            }
            document.getElementById('viewDensity').textContent = densityText;

            // Status with badge
            const statusElement = document.getElementById('viewStatus');
            if (continent.is_active == 1) {
                statusElement.innerHTML = '<span class="badge bg-success">Active</span>';
            } else {
                statusElement.innerHTML = '<span class="badge bg-secondary">Inactive</span>';
            }

            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleString();
            };

            document.getElementById('viewCreatedAt').textContent = formatDate(continent.created_at);
            document.getElementById('viewUpdatedAt').textContent = formatDate(continent.updated_at);

            // Set up action buttons
            const editBtn = document.getElementById('viewEditBtn');
            const deleteBtn = document.getElementById('viewDeleteBtn');

            // Remove existing listeners
            editBtn.onclick = null;
            deleteBtn.onclick = null;

            // Add new listeners
            editBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewContinentModal')).hide();
                this.editContinent(id);
            };

            deleteBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewContinentModal')).hide();
                this.deleteContinent(id);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewContinentModal'));
            modal.show();
        }

        editContinent(id) {
            const continent = this.continents.find(c => c.id == id);
            if (!continent) return;

            // Populate edit form
            document.getElementById('editContinentId').value = continent.id;
            document.getElementById('editName').value = continent.name || '';
            document.getElementById('editCode').value = continent.code || '';
            document.getElementById('editArea_km2').value = continent.area_km2 || '';
            document.getElementById('editPopulation').value = continent.population || '';
            document.getElementById('editCountries_count').value = continent.countries_count || '';
            document.getElementById('editLargest_country').value = continent.largest_country || '';
            document.getElementById('editDescription').value = continent.description || '';
            document.getElementById('editIs_active').checked = continent.is_active == 1;

            const modal = new bootstrap.Modal(document.getElementById('editContinentModal'));
            modal.show();
        }

        async deleteContinent(id) {
            const continent = this.continents.find(c => c.id == id);
            if (!continent) return;

            if (!confirm(`Are you sure you want to delete ${continent.name || 'this continent'}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/Continent/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Continent deleted successfully', 'success');
                    this.loadContinents(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to delete continent');
                }
            } catch (error) {
                handleApiError(error, 'deleting continent');
            }
        }

        async handleAddContinent(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Convert checkbox to integer
            data.is_active = data.is_active ? 1 : 0;

            try {
                const response = await fetch('/api/entities/Continent', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Continent added successfully', 'success');
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addContinentModal')).hide();
                    this.loadContinents(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to add continent');
                }
            } catch (error) {
                handleApiError(error, 'adding continent');
            }
        }

        async handleEditContinent(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const id = data.id;
            delete data.id; // Remove id from data object

            // Convert checkbox to integer
            data.is_active = data.is_active ? 1 : 0;

            try {
                const response = await fetch(`/api/entities/Continent/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Continent updated successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editContinentModal')).hide();
                    this.loadContinents(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to update continent');
                }
            } catch (error) {
                handleApiError(error, 'updating continent');
            }
        }

        handleSearch(e) {
            // Simple client-side search implementation
            this.renderFilteredContinents();
        }

        handleFilter(e) {
            // Simple client-side filter implementation
            this.renderFilteredContinents();
        }

        renderFilteredContinents() {
            const searchTerm = document.getElementById('searchContinents')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';

            let filteredContinents = this.continents;

            // Apply search filter
            if (searchTerm) {
                filteredContinents = filteredContinents.filter(continent => {
                    const name = (continent.name || '').toLowerCase();
                    const code = (continent.code || '').toLowerCase();
                    const largestCountry = (continent.largest_country || '').toLowerCase();
                    return name.includes(searchTerm) || code.includes(searchTerm) || largestCountry.includes(searchTerm);
                });
            }

            // Apply status filter
            if (statusFilter) {
                const isActive = statusFilter === 'active' ? 1 : 0;
                filteredContinents = filteredContinents.filter(continent => continent.is_active == isActive);
            }

            // Temporarily replace continents array for rendering
            const originalContinents = this.continents;
            this.continents = filteredContinents;
            this.renderContinents();
            this.continents = originalContinents; // Restore original array
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.continentManager = new ContinentManager();
    });
</script>
<?php elseif ($current_page === 'languages'): ?>
<script>
    class LanguageManager {
        constructor() {
            this.languages = [];
            this.languageFamilies = [];
            this.currentPage = 1;
            this.itemsPerPage = 10;
            this.init();
        }

        init() {
            this.loadLanguages();
            this.bindEvents();
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadLanguages());
            document.getElementById('newLanguageBtn')?.addEventListener('click', () => this.showNewLanguageModal());

            // Form event handlers
            document.getElementById('addLanguageForm')?.addEventListener('submit', (e) => this.handleAddLanguage(e));
            document.getElementById('editLanguageForm')?.addEventListener('submit', (e) => this.handleEditLanguage(e));

            // Search and filter handlers
            document.getElementById('searchLanguages')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('typeFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('familyFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('activeFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('sortFilter')?.addEventListener('change', (e) => this.handleSort(e));
        }

        async loadLanguages() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/Language');
                const result = await response.json();

                if (result.success) {
                    this.languages = result.data || [];
                    this.extractLanguageFamilies();
                    this.populateFamilyFilter();
                    this.renderLanguages();
                    showToast('Languages loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load languages');
                }
            } catch (error) {
                handleApiError(error, 'loading languages');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        extractLanguageFamilies() {
            const families = [...new Set(this.languages
                .map(lang => lang.language_family)
                .filter(family => family && family.trim() !== '')
            )].sort();
            this.languageFamilies = families;
        }

        populateFamilyFilter() {
            const familySelect = document.getElementById('familyFilter');
            if (!familySelect) return;

            // Clear existing options except the first one
            familySelect.innerHTML = '<option value="">All Families</option>';

            this.languageFamilies.forEach(family => {
                const option = document.createElement('option');
                option.value = family;
                option.textContent = family;
                familySelect.appendChild(option);
            });
        }

        renderLanguages() {
            const tableContainer = document.getElementById('languagesTable');
            if (!tableContainer) return;

            if (this.languages.length === 0) {
                tableContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🗣️</span>
                            <h5 class="mt-3">No languages found</h5>
                            <p>Start by adding your first language using the "Add Language" button above.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Update statistics
            this.updateStatistics();

            // Build responsive HTML
            let html = `
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Language</th>
                                    <th>Family & Type</th>
                                    <th>ISO Codes</th>
                                    <th>Speakers</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            // Desktop table rows
            this.languages.forEach(language => {
                const displayName = language.native_name && language.native_name !== language.name
                    ? `${language.name} (${language.native_name})`
                    : language.name || 'N/A';

                const statusBadge = this.getStatusBadge(language.status);
                const typeBadge = this.getTypeBadge(language.language_type);
                const activeBadge = language.is_active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const isoCodes = this.getISOCodesDisplay(language);
                const speakersDisplay = this.getSpeakersDisplay(language);

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-semibold">${displayName}</div>
                                    <small class="text-muted">${language.writing_system || 'No writing system'}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>${language.language_family || 'Unknown Family'}</div>
                            <small class="text-muted">${typeBadge}</small>
                        </td>
                        <td>
                            <small>${isoCodes}</small>
                        </td>
                        <td>
                            ${speakersDisplay}
                        </td>
                        <td>
                            ${statusBadge}<br>
                            ${activeBadge}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info" onclick="window.languageManager.viewLanguage(${language.id})" title="View Details">
                                    👁️
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="window.languageManager.editLanguage(${language.id})" title="Edit">
                                    ✏️
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="window.languageManager.deleteLanguage(${language.id})" title="Delete">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (hidden on desktop) -->
                <div class="d-lg-none">
            `;

            // Mobile card layout
            this.languages.forEach(language => {
                const displayName = language.native_name && language.native_name !== language.name
                    ? `${language.name} (${language.native_name})`
                    : language.name || 'N/A';

                const statusBadge = this.getStatusBadge(language.status);
                const typeBadge = this.getTypeBadge(language.language_type);
                const activeBadge = language.is_active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const speakersDisplay = this.getSpeakersDisplay(language, true);

                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <div class="text-center">
                                        <span style="font-size: 2rem;">🗣️</span>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">${displayName}</h6>
                                    <div class="small text-muted mb-1">
                                        <span class="badge bg-secondary me-1">${language.language_family || 'Unknown'}</span>
                                        ${statusBadge}
                                    </div>
                                    <div class="small text-muted mb-1">
                                        ${typeBadge} • ${activeBadge}
                                    </div>
                                    <div class="small text-muted">
                                        ${speakersDisplay}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            ⚙️
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.languageManager.viewLanguage(${language.id})">
                                                    👁️ View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.languageManager.editLanguage(${language.id})">
                                                    ✏️ Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="window.languageManager.deleteLanguage(${language.id})">
                                                    🗑️ Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                </div>
            `;

            tableContainer.innerHTML = html;
        }

        getStatusBadge(status) {
            const statusMap = {
                'living': '<span class="badge bg-success">Living</span>',
                'endangered': '<span class="badge bg-warning">Endangered</span>',
                'dormant': '<span class="badge bg-secondary">Dormant</span>',
                'extinct': '<span class="badge bg-dark">Extinct</span>',
                'revitalized': '<span class="badge bg-info">Revitalized</span>'
            };
            return statusMap[status] || '<span class="badge bg-light">Unknown</span>';
        }

        getTypeBadge(type) {
            const typeMap = {
                'natural': '<span class="badge bg-primary">Natural</span>',
                'constructed': '<span class="badge bg-purple">Constructed</span>',
                'sign': '<span class="badge bg-info">Sign</span>',
                'pidgin': '<span class="badge bg-warning">Pidgin</span>',
                'creole': '<span class="badge bg-orange">Creole</span>'
            };
            return typeMap[type] || '<span class="badge bg-light">Unknown</span>';
        }

        getISOCodesDisplay(language) {
            const codes = [];
            if (language.iso_639_1) codes.push(language.iso_639_1);
            if (language.iso_639_2) codes.push(language.iso_639_2);
            if (language.iso_639_3) codes.push(language.iso_639_3);
            return codes.length > 0 ? codes.join(' • ') : 'No ISO codes';
        }

        getSpeakersDisplay(language, mobile = false) {
            const native = language.speakers_native ? this.formatPopulation(language.speakers_native) : '0';
            const total = language.speakers_total ? this.formatPopulation(language.speakers_total) : '0';

            if (mobile) {
                return `👥 ${total} total speakers`;
            } else {
                return `
                    <div>👥 ${total} total</div>
                    <small class="text-muted">🏠 ${native} native</small>
                `;
            }
        }

        updateStatistics() {
            const totalElement = document.getElementById('totalLanguages');
            const livingElement = document.getElementById('livingLanguages');
            const endangeredElement = document.getElementById('endangeredLanguages');
            const extinctElement = document.getElementById('extinctLanguages');
            const speakersElement = document.getElementById('totalSpeakers');
            const familiesElement = document.getElementById('totalFamilies');

            if (totalElement) totalElement.textContent = this.languages.length;

            const livingCount = this.languages.filter(l => l.status === 'living').length;
            const endangeredCount = this.languages.filter(l => ['endangered', 'dormant'].includes(l.status)).length;
            const extinctCount = this.languages.filter(l => l.status === 'extinct').length;

            if (livingElement) livingElement.textContent = livingCount;
            if (endangeredElement) endangeredElement.textContent = endangeredCount;
            if (extinctElement) extinctElement.textContent = extinctCount;

            // Calculate total speakers
            const totalSpeakers = this.languages.reduce((sum, l) => sum + (parseInt(l.speakers_total) || 0), 0);
            if (speakersElement) speakersElement.textContent = this.formatPopulation(totalSpeakers);

            if (familiesElement) familiesElement.textContent = this.languageFamilies.length;
        }

        formatPopulation(population) {
            if (!population) return '0';

            const num = parseInt(population);
            if (num >= 1000000000) {
                return (num / 1000000000).toFixed(1) + 'B';
            } else if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        showNewLanguageModal() {
            const modal = new bootstrap.Modal(document.getElementById('addLanguageModal'));
            modal.show();
        }

        viewLanguage(id) {
            const language = this.languages.find(l => l.id == id);
            if (!language) return;

            // Populate view modal
            const displayName = language.native_name && language.native_name !== language.name
                ? `${language.name} (${language.native_name})`
                : language.name || 'N/A';

            document.getElementById('viewLanguageName').textContent = displayName;
            document.getElementById('viewNativeName').textContent = language.native_name || 'N/A';
            document.getElementById('viewLanguageFamily').textContent = language.language_family || 'N/A';
            document.getElementById('viewWritingSystem').textContent = language.writing_system || 'N/A';
            document.getElementById('viewLanguageType').textContent = this.capitalizeFirst(language.language_type || 'N/A');
            document.getElementById('viewDescription').textContent = language.description || 'N/A';

            // ISO codes
            document.getElementById('viewIso639_1').textContent = language.iso_639_1 || 'N/A';
            document.getElementById('viewIso639_2').textContent = language.iso_639_2 || 'N/A';
            document.getElementById('viewIso639_3').textContent = language.iso_639_3 || 'N/A';

            // Speakers
            document.getElementById('viewNativeSpeakers').textContent = language.speakers_native
                ? this.formatPopulation(language.speakers_native) : 'Unknown';
            document.getElementById('viewTotalSpeakers').textContent = language.speakers_total
                ? this.formatPopulation(language.speakers_total) : 'Unknown';

            // Status
            const statusElement = document.getElementById('viewLanguageStatus');
            statusElement.innerHTML = this.getStatusBadge(language.status);

            const activeElement = document.getElementById('viewActiveStatus');
            if (language.is_active == 1) {
                activeElement.innerHTML = '<span class="badge bg-success">Active</span>';
            } else {
                activeElement.innerHTML = '<span class="badge bg-secondary">Inactive</span>';
            }

            // System info
            document.getElementById('viewLanguageId').textContent = language.id || 'N/A';

            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleString();
            };

            document.getElementById('viewCreatedAt').textContent = formatDate(language.created_at);
            document.getElementById('viewUpdatedAt').textContent = formatDate(language.updated_at);

            // Set up action buttons
            const editBtn = document.getElementById('viewEditBtn');
            const deleteBtn = document.getElementById('viewDeleteBtn');

            // Remove existing listeners
            editBtn.onclick = null;
            deleteBtn.onclick = null;

            // Add new listeners
            editBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewLanguageModal')).hide();
                this.editLanguage(id);
            };

            deleteBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewLanguageModal')).hide();
                this.deleteLanguage(id);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewLanguageModal'));
            modal.show();
        }

        editLanguage(id) {
            const language = this.languages.find(l => l.id == id);
            if (!language) return;

            // Populate edit form
            document.getElementById('editLanguageId').value = language.id;
            document.getElementById('editName').value = language.name || '';
            document.getElementById('editNative_name').value = language.native_name || '';
            document.getElementById('editLanguage_family').value = language.language_family || '';
            document.getElementById('editWriting_system').value = language.writing_system || '';
            document.getElementById('editIso_639_1').value = language.iso_639_1 || '';
            document.getElementById('editIso_639_2').value = language.iso_639_2 || '';
            document.getElementById('editIso_639_3').value = language.iso_639_3 || '';
            document.getElementById('editLanguage_type').value = language.language_type || 'natural';
            document.getElementById('editStatus').value = language.status || 'living';
            document.getElementById('editSpeakers_native').value = language.speakers_native || '';
            document.getElementById('editSpeakers_total').value = language.speakers_total || '';
            document.getElementById('editDescription').value = language.description || '';
            document.getElementById('editIs_active').checked = language.is_active == 1;

            const modal = new bootstrap.Modal(document.getElementById('editLanguageModal'));
            modal.show();
        }

        async deleteLanguage(id) {
            const language = this.languages.find(l => l.id == id);
            if (!language) return;

            const displayName = language.native_name && language.native_name !== language.name
                ? `${language.name} (${language.native_name})`
                : language.name || 'this language';

            if (!confirm(`Are you sure you want to delete ${displayName}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/Language/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Language deleted successfully', 'success');
                    this.loadLanguages(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to delete language');
                }
            } catch (error) {
                handleApiError(error, 'deleting language');
            }
        }

        async handleAddLanguage(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Convert checkbox to integer
            data.is_active = data.is_active ? 1 : 0;

            try {
                const response = await fetch('/api/entities/Language', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Language added successfully', 'success');
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addLanguageModal')).hide();
                    this.loadLanguages(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to add language');
                }
            } catch (error) {
                handleApiError(error, 'adding language');
            }
        }

        async handleEditLanguage(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const id = data.id;
            delete data.id; // Remove id from data object

            // Convert checkbox to integer
            data.is_active = data.is_active ? 1 : 0;

            try {
                const response = await fetch(`/api/entities/Language/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Language updated successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editLanguageModal')).hide();
                    this.loadLanguages(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to update language');
                }
            } catch (error) {
                handleApiError(error, 'updating language');
            }
        }

        handleSearch(e) {
            this.renderFilteredLanguages();
        }

        handleFilter(e) {
            this.renderFilteredLanguages();
        }

        handleSort(e) {
            this.renderFilteredLanguages();
        }

        renderFilteredLanguages() {
            const searchTerm = document.getElementById('searchLanguages')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';
            const typeFilter = document.getElementById('typeFilter')?.value || '';
            const familyFilter = document.getElementById('familyFilter')?.value || '';
            const activeFilter = document.getElementById('activeFilter')?.value || '';
            const sortFilter = document.getElementById('sortFilter')?.value || '';

            let filteredLanguages = this.languages;

            // Apply search filter
            if (searchTerm) {
                filteredLanguages = filteredLanguages.filter(language => {
                    const name = (language.name || '').toLowerCase();
                    const nativeName = (language.native_name || '').toLowerCase();
                    const family = (language.language_family || '').toLowerCase();
                    const iso1 = (language.iso_639_1 || '').toLowerCase();
                    const iso2 = (language.iso_639_2 || '').toLowerCase();
                    const iso3 = (language.iso_639_3 || '').toLowerCase();
                    const writingSystem = (language.writing_system || '').toLowerCase();
                    return name.includes(searchTerm) || nativeName.includes(searchTerm) ||
                           family.includes(searchTerm) || iso1.includes(searchTerm) ||
                           iso2.includes(searchTerm) || iso3.includes(searchTerm) ||
                           writingSystem.includes(searchTerm);
                });
            }

            // Apply status filter
            if (statusFilter) {
                filteredLanguages = filteredLanguages.filter(language => language.status === statusFilter);
            }

            // Apply type filter
            if (typeFilter) {
                filteredLanguages = filteredLanguages.filter(language => language.language_type === typeFilter);
            }

            // Apply family filter
            if (familyFilter) {
                filteredLanguages = filteredLanguages.filter(language => language.language_family === familyFilter);
            }

            // Apply active filter
            if (activeFilter) {
                const isActive = activeFilter === 'active' ? 1 : 0;
                filteredLanguages = filteredLanguages.filter(language => language.is_active == isActive);
            }

            // Apply sorting
            if (sortFilter) {
                filteredLanguages.sort((a, b) => {
                    switch (sortFilter) {
                        case 'name':
                            return (a.name || '').localeCompare(b.name || '');
                        case 'speakers':
                            return (parseInt(b.speakers_total) || 0) - (parseInt(a.speakers_total) || 0);
                        case 'family':
                            return (a.language_family || '').localeCompare(b.language_family || '');
                        default:
                            return 0;
                    }
                });
            }

            // Temporarily replace languages array for rendering
            const originalLanguages = this.languages;
            this.languages = filteredLanguages;
            this.renderLanguages();
            this.languages = originalLanguages; // Restore original array
        }

        capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.languageManager = new LanguageManager();
    });
</script>
<?php elseif ($current_page === 'countries'): ?>
<script>
    class CountryManager {
        constructor() {
            this.countries = [];
            this.continents = [];
            this.currentPage = 1;
            this.itemsPerPage = 10;
            this.init();
        }

        init() {
            this.loadContinents();
            this.loadCountries();
            this.bindEvents();
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadCountries());
            document.getElementById('newCountryBtn')?.addEventListener('click', () => this.showNewCountryModal());

            // Form event handlers
            document.getElementById('addCountryForm')?.addEventListener('submit', (e) => this.handleAddCountry(e));
            document.getElementById('editCountryForm')?.addEventListener('submit', (e) => this.handleEditCountry(e));

            // Search and filter handlers
            document.getElementById('searchCountries')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('continentFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('developmentFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('sortFilter')?.addEventListener('change', (e) => this.handleSort(e));
        }

        async loadContinents() {
            try {
                const response = await fetch('/api/entities/Continent');
                const result = await response.json();

                if (result.success) {
                    this.continents = result.data || [];
                    this.populateContinentDropdowns();
                }
            } catch (error) {
                console.error('Error loading continents:', error);
            }
        }

        populateContinentDropdowns() {
            const selects = ['continent_id', 'editContinent_id', 'continentFilter'];

            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select && selectId !== 'continentFilter') {
                    // Clear existing options except the first one
                    select.innerHTML = select.firstElementChild.outerHTML;
                }

                if (select && selectId === 'continentFilter') {
                    // Clear filter options except the first one
                    select.innerHTML = '<option value="">All Continents</option>';
                }

                if (select) {
                    this.continents.forEach(continent => {
                        const option = document.createElement('option');
                        option.value = continent.id;
                        option.textContent = continent.name;
                        select.appendChild(option);
                    });
                }
            });
        }

        async loadCountries() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/Country');
                const result = await response.json();

                if (result.success) {
                    this.countries = result.data || [];
                    this.renderCountries();
                    showToast('Countries loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load countries');
                }
            } catch (error) {
                handleApiError(error, 'loading countries');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        renderCountries() {
            const tableContainer = document.getElementById('countriesTable');
            if (!tableContainer) return;

            if (this.countries.length === 0) {
                tableContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🏴</span>
                            <h5 class="mt-3">No countries found</h5>
                            <p>Start by adding your first country using the "Add Country" button above.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Update statistics
            this.updateStatistics();

            // Build responsive HTML
            let html = `
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>Continent</th>
                                    <th>Population & Area</th>
                                    <th>Economy</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            // Desktop table rows
            this.countries.forEach(country => {
                const countryName = country.flag_emoji ?
                    `${country.flag_emoji} ${country.name}` :
                    country.name || 'N/A';

                const continent = this.getContinentName(country.continent_id);

                const statusBadge = country.is_active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const developmentBadge = country.is_developed == 1
                    ? '<span class="badge bg-primary">Developed</span>'
                    : '<span class="badge bg-info">Developing</span>';

                const landlockedBadge = country.is_landlocked == 1
                    ? '<span class="badge bg-warning">Landlocked</span>' : '';

                const formattedArea = country.area_km2 ? this.formatNumber(country.area_km2) + ' km²' : 'N/A';
                const formattedPopulation = country.population ? this.formatPopulation(country.population) : 'N/A';
                const formattedGDP = country.gdp_usd ? '$' + this.formatPopulation(country.gdp_usd) : 'N/A';
                const formattedGDPPerCapita = country.gdp_per_capita ? '$' + this.formatNumber(country.gdp_per_capita) : 'N/A';

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-semibold">${countryName}</div>
                                    <small class="text-muted">${country.capital || 'No capital'}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">${continent}</span>
                        </td>
                        <td>
                            <div>👥 ${formattedPopulation}</div>
                            <small class="text-muted">📏 ${formattedArea}</small>
                        </td>
                        <td>
                            <div>💰 ${formattedGDP}</div>
                            <small class="text-muted">👤 ${formattedGDPPerCapita}</small>
                        </td>
                        <td>
                            ${statusBadge}<br>
                            ${developmentBadge}
                            ${landlockedBadge}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info" onclick="window.countryManager.viewCountry(${country.id})" title="View Details">
                                    👁️
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="window.countryManager.editCountry(${country.id})" title="Edit">
                                    ✏️
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="window.countryManager.deleteCountry(${country.id})" title="Delete">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (hidden on desktop) -->
                <div class="d-lg-none">
            `;

            // Mobile card layout
            this.countries.forEach(country => {
                const countryName = country.flag_emoji ?
                    `${country.flag_emoji} ${country.name}` :
                    country.name || 'N/A';

                const continent = this.getContinentName(country.continent_id);

                const statusBadge = country.is_active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';

                const developmentBadge = country.is_developed == 1
                    ? '<span class="badge bg-primary">Developed</span>'
                    : '<span class="badge bg-info">Developing</span>';

                const formattedArea = country.area_km2 ? this.formatNumber(country.area_km2) + ' km²' : 'N/A';
                const formattedPopulation = country.population ? this.formatPopulation(country.population) : 'N/A';

                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <div class="text-center">
                                        <span style="font-size: 2rem;">${country.flag_emoji || '🏴'}</span>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">${countryName}</h6>
                                    <div class="small text-muted mb-1">
                                        <span class="badge bg-secondary me-1">${continent}</span>
                                        ${statusBadge}
                                    </div>
                                    <div class="small text-muted mb-1">
                                        🏛️ ${country.capital || 'No capital'} • ${developmentBadge}
                                    </div>
                                    <div class="small text-muted">
                                        👥 ${formattedPopulation} • 📏 ${formattedArea}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            ⚙️
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.countryManager.viewCountry(${country.id})">
                                                    👁️ View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="window.countryManager.editCountry(${country.id})">
                                                    ✏️ Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="window.countryManager.deleteCountry(${country.id})">
                                                    🗑️ Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                </div>
            `;

            tableContainer.innerHTML = html;
        }

        updateStatistics() {
            const totalElement = document.getElementById('totalCountries');
            const activeElement = document.getElementById('activeCountries');
            const developedElement = document.getElementById('developedCountries');
            const landlockedElement = document.getElementById('landlockedCountries');
            const populationElement = document.getElementById('totalPopulation');
            const gdpElement = document.getElementById('totalGDP');

            if (totalElement) totalElement.textContent = this.countries.length;

            const activeCount = this.countries.filter(c => c.is_active == 1).length;
            const developedCount = this.countries.filter(c => c.is_developed == 1).length;
            const landlockedCount = this.countries.filter(c => c.is_landlocked == 1).length;

            if (activeElement) activeElement.textContent = activeCount;
            if (developedElement) developedElement.textContent = developedCount;
            if (landlockedElement) landlockedElement.textContent = landlockedCount;

            // Calculate totals
            const totalPopulation = this.countries.reduce((sum, c) => sum + (parseInt(c.population) || 0), 0);
            const totalGDP = this.countries.reduce((sum, c) => sum + (parseInt(c.gdp_usd) || 0), 0);

            if (populationElement) populationElement.textContent = this.formatPopulation(totalPopulation);
            if (gdpElement) gdpElement.textContent = '$' + this.formatPopulation(totalGDP);
        }

        getContinentName(continentId) {
            const continent = this.continents.find(c => c.id == continentId);
            return continent ? continent.name : 'Unknown';
        }

        formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }

        formatPopulation(population) {
            if (!population) return '0';

            const num = parseInt(population);
            if (num >= 1000000000000) {
                return (num / 1000000000000).toFixed(1) + 'T';
            } else if (num >= 1000000000) {
                return (num / 1000000000).toFixed(1) + 'B';
            } else if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        showNewCountryModal() {
            const modal = new bootstrap.Modal(document.getElementById('addCountryModal'));
            modal.show();
        }

        viewCountry(id) {
            const country = this.countries.find(c => c.id == id);
            if (!country) return;

            // Populate view modal
            const countryName = country.flag_emoji ?
                `${country.flag_emoji} ${country.name}` :
                country.name || 'N/A';

            document.getElementById('viewCountryName').textContent = countryName;
            document.getElementById('viewOfficialName').textContent = country.official_name || 'N/A';
            document.getElementById('viewContinent').textContent = this.getContinentName(country.continent_id);
            document.getElementById('viewCapital').textContent = country.capital || 'N/A';
            document.getElementById('viewPopulation').textContent = country.population ? this.formatNumber(country.population) : 'N/A';
            document.getElementById('viewArea').textContent = country.area_km2 ? this.formatNumber(country.area_km2) + ' km²' : 'N/A';
            document.getElementById('viewGDP').textContent = country.gdp_usd ? '$' + this.formatNumber(country.gdp_usd) : 'N/A';
            document.getElementById('viewGDPPerCapita').textContent = country.gdp_per_capita ? '$' + this.formatNumber(country.gdp_per_capita) : 'N/A';
            document.getElementById('viewLanguages').textContent = country.official_languages || 'N/A';
            document.getElementById('viewGovernmentType').textContent = country.government_type || 'N/A';
            document.getElementById('viewHeadOfState').textContent = country.head_of_state || 'N/A';
            document.getElementById('viewHeadOfGovernment').textContent = country.head_of_government || 'N/A';
            document.getElementById('viewCallingCode').textContent = country.calling_code || 'N/A';
            document.getElementById('viewInternetTLD').textContent = country.internet_tld || 'N/A';
            document.getElementById('viewTimezone').textContent = country.timezone || 'N/A';
            document.getElementById('viewIndependence').textContent = country.independence_date || 'N/A';

            // ISO codes
            const isoCodes = [];
            if (country.iso_alpha_2) isoCodes.push(`α2: ${country.iso_alpha_2}`);
            if (country.iso_alpha_3) isoCodes.push(`α3: ${country.iso_alpha_3}`);
            if (country.iso_numeric) isoCodes.push(`№: ${country.iso_numeric}`);
            document.getElementById('viewIsoCodes').textContent = isoCodes.length ? isoCodes.join(' • ') : 'N/A';

            // Region
            const regionParts = [];
            if (country.region) regionParts.push(country.region);
            if (country.subregion) regionParts.push(country.subregion);
            document.getElementById('viewRegion').textContent = regionParts.length ? regionParts.join(' → ') : 'N/A';

            // Currency
            const currency = country.currency_code && country.currency_name ?
                `${country.currency_code} (${country.currency_name})` :
                country.currency_code || country.currency_name || 'N/A';
            document.getElementById('viewCurrency').textContent = currency;

            // Calculate and display population density
            let densityText = 'N/A';
            if (country.area_km2 && country.population) {
                const density = Math.round(country.population / country.area_km2);
                densityText = density + ' people/km²';
            }
            document.getElementById('viewDensity').textContent = densityText;

            // Status badges
            const statusElement = document.getElementById('viewStatus');
            if (country.is_active == 1) {
                statusElement.innerHTML = '<span class="badge bg-success">Active</span>';
            } else {
                statusElement.innerHTML = '<span class="badge bg-secondary">Inactive</span>';
            }

            const developmentElement = document.getElementById('viewDevelopment');
            if (country.is_developed == 1) {
                developmentElement.innerHTML = '<span class="badge bg-primary">Developed</span>';
            } else {
                developmentElement.innerHTML = '<span class="badge bg-info">Developing</span>';
            }

            const landlockedElement = document.getElementById('viewLandlocked');
            if (country.is_landlocked == 1) {
                landlockedElement.innerHTML = '<span class="badge bg-warning">Yes</span>';
            } else {
                landlockedElement.innerHTML = '<span class="badge bg-success">No</span>';
            }

            // Set up action buttons
            const editBtn = document.getElementById('viewEditBtn');
            const deleteBtn = document.getElementById('viewDeleteBtn');

            // Remove existing listeners
            editBtn.onclick = null;
            deleteBtn.onclick = null;

            // Add new listeners
            editBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewCountryModal')).hide();
                this.editCountry(id);
            };

            deleteBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewCountryModal')).hide();
                this.deleteCountry(id);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewCountryModal'));
            modal.show();
        }

        editCountry(id) {
            const country = this.countries.find(c => c.id == id);
            if (!country) return;

            // Populate edit form
            document.getElementById('editCountryId').value = country.id;
            document.getElementById('editName').value = country.name || '';
            document.getElementById('editOfficial_name').value = country.official_name || '';
            document.getElementById('editContinent_id').value = country.continent_id || '';
            document.getElementById('editCapital').value = country.capital || '';
            document.getElementById('editFlag_emoji').value = country.flag_emoji || '';
            document.getElementById('editIso_alpha_2').value = country.iso_alpha_2 || '';
            document.getElementById('editIso_alpha_3').value = country.iso_alpha_3 || '';
            document.getElementById('editIso_numeric').value = country.iso_numeric || '';
            document.getElementById('editLatitude').value = country.latitude || '';
            document.getElementById('editLongitude').value = country.longitude || '';
            document.getElementById('editRegion').value = country.region || '';
            document.getElementById('editSubregion').value = country.subregion || '';
            document.getElementById('editArea_km2').value = country.area_km2 || '';
            document.getElementById('editPopulation').value = country.population || '';
            document.getElementById('editGdp_usd').value = country.gdp_usd || '';
            document.getElementById('editGdp_per_capita').value = country.gdp_per_capita || '';
            document.getElementById('editCurrency_code').value = country.currency_code || '';
            document.getElementById('editCurrency_name').value = country.currency_name || '';
            document.getElementById('editGovernment_type').value = country.government_type || '';
            document.getElementById('editHead_of_state').value = country.head_of_state || '';
            document.getElementById('editHead_of_government').value = country.head_of_government || '';
            document.getElementById('editCalling_code').value = country.calling_code || '';
            document.getElementById('editInternet_tld').value = country.internet_tld || '';
            document.getElementById('editOfficial_languages').value = country.official_languages || '';
            document.getElementById('editIndependence_date').value = country.independence_date || '';
            document.getElementById('editTimezone').value = country.timezone || '';
            document.getElementById('editIs_landlocked').checked = country.is_landlocked == 1;
            document.getElementById('editIs_developed').checked = country.is_developed == 1;
            document.getElementById('editIs_active').checked = country.is_active == 1;

            const modal = new bootstrap.Modal(document.getElementById('editCountryModal'));
            modal.show();
        }

        async deleteCountry(id) {
            const country = this.countries.find(c => c.id == id);
            if (!country) return;

            const countryName = country.flag_emoji ?
                `${country.flag_emoji} ${country.name}` :
                country.name || 'this country';

            if (!confirm(`Are you sure you want to delete ${countryName}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/Country/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Country deleted successfully', 'success');
                    this.loadCountries(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to delete country');
                }
            } catch (error) {
                handleApiError(error, 'deleting country');
            }
        }

        async handleAddCountry(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Convert checkboxes to integers
            data.is_landlocked = data.is_landlocked ? 1 : 0;
            data.is_developed = data.is_developed ? 1 : 0;
            data.is_active = data.is_active ? 1 : 0;

            try {
                const response = await fetch('/api/entities/Country', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Country added successfully', 'success');
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addCountryModal')).hide();
                    this.loadCountries(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to add country');
                }
            } catch (error) {
                handleApiError(error, 'adding country');
            }
        }

        async handleEditCountry(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const id = data.id;
            delete data.id; // Remove id from data object

            // Convert checkboxes to integers
            data.is_landlocked = data.is_landlocked ? 1 : 0;
            data.is_developed = data.is_developed ? 1 : 0;
            data.is_active = data.is_active ? 1 : 0;

            try {
                const response = await fetch(`/api/entities/Country/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast('Country updated successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editCountryModal')).hide();
                    this.loadCountries(); // Reload the list
                } else {
                    throw new Error(result.error || 'Failed to update country');
                }
            } catch (error) {
                handleApiError(error, 'updating country');
            }
        }

        handleSearch(e) {
            this.renderFilteredCountries();
        }

        handleFilter(e) {
            this.renderFilteredCountries();
        }

        handleSort(e) {
            this.renderFilteredCountries();
        }

        renderFilteredCountries() {
            const searchTerm = document.getElementById('searchCountries')?.value.toLowerCase() || '';
            const continentFilter = document.getElementById('continentFilter')?.value || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';
            const developmentFilter = document.getElementById('developmentFilter')?.value || '';
            const sortFilter = document.getElementById('sortFilter')?.value || '';

            let filteredCountries = this.countries;

            // Apply search filter
            if (searchTerm) {
                filteredCountries = filteredCountries.filter(country => {
                    const name = (country.name || '').toLowerCase();
                    const officialName = (country.official_name || '').toLowerCase();
                    const capital = (country.capital || '').toLowerCase();
                    const iso2 = (country.iso_alpha_2 || '').toLowerCase();
                    const iso3 = (country.iso_alpha_3 || '').toLowerCase();
                    const region = (country.region || '').toLowerCase();
                    const subregion = (country.subregion || '').toLowerCase();
                    return name.includes(searchTerm) || officialName.includes(searchTerm) ||
                           capital.includes(searchTerm) || iso2.includes(searchTerm) ||
                           iso3.includes(searchTerm) || region.includes(searchTerm) ||
                           subregion.includes(searchTerm);
                });
            }

            // Apply continent filter
            if (continentFilter) {
                filteredCountries = filteredCountries.filter(country => country.continent_id == continentFilter);
            }

            // Apply status filter
            if (statusFilter) {
                const isActive = statusFilter === 'active' ? 1 : 0;
                filteredCountries = filteredCountries.filter(country => country.is_active == isActive);
            }

            // Apply development filter
            if (developmentFilter) {
                const isDeveloped = developmentFilter === 'developed' ? 1 : 0;
                filteredCountries = filteredCountries.filter(country => country.is_developed == isDeveloped);
            }

            // Apply sorting
            if (sortFilter) {
                filteredCountries.sort((a, b) => {
                    switch (sortFilter) {
                        case 'name':
                            return (a.name || '').localeCompare(b.name || '');
                        case 'population':
                            return (parseInt(b.population) || 0) - (parseInt(a.population) || 0);
                        case 'area':
                            return (parseInt(b.area_km2) || 0) - (parseInt(a.area_km2) || 0);
                        case 'gdp':
                            return (parseInt(b.gdp_usd) || 0) - (parseInt(a.gdp_usd) || 0);
                        default:
                            return 0;
                    }
                });
            }

            // Temporarily replace countries array for rendering
            const originalCountries = this.countries;
            this.countries = filteredCountries;
            this.renderCountries();
            this.countries = originalCountries; // Restore original array
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.countryManager = new CountryManager();
    });
</script>
<?php elseif ($current_page === 'industry_categories'): ?>
<script>
    class IndustryCategoryManager {
        constructor() {
            this.categories = [];
            this.filteredCategories = [];
            this.treeData = [];
            this.currentViewMode = 'tree';
            this.currentParent = null;
            this.init();
        }

        init() {
            this.loadCategories();
            this.bindEvents();
        }

        bindEvents() {
            // Basic events
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadCategories());
            document.getElementById('newCategoryBtn')?.addEventListener('click', () => this.showAddModal());

            // Form handlers
            document.getElementById('addCategoryForm')?.addEventListener('submit', (e) => this.handleAdd(e));
            document.getElementById('editCategoryForm')?.addEventListener('submit', (e) => this.handleEdit(e));
            document.getElementById('moveCategoryForm')?.addEventListener('submit', (e) => this.handleMove(e));

            // Search and filters
            document.getElementById('searchCategories')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('statusFilter')?.addEventListener('change', () => this.applyFilters());
            document.getElementById('levelFilter')?.addEventListener('change', () => this.applyFilters());
            document.getElementById('featuredFilter')?.addEventListener('change', () => this.applyFilters());

            // View mode toggles
            document.getElementById('treeView')?.addEventListener('change', () => this.switchViewMode('tree'));
            document.getElementById('listView')?.addEventListener('change', () => this.switchViewMode('list'));

            // Modal events
            this.bindModalEvents();
        }

        bindModalEvents() {
            // View modal actions
            document.getElementById('viewEditBtn')?.addEventListener('click', () => {
                const id = document.getElementById('viewCategoryId').textContent;
                this.editCategory(parseInt(id));
            });

            document.getElementById('viewAddChildBtn')?.addEventListener('click', () => {
                const id = document.getElementById('viewCategoryId').textContent;
                this.showAddModal(parseInt(id));
            });

            document.getElementById('viewDeleteBtn')?.addEventListener('click', () => {
                const id = document.getElementById('viewCategoryId').textContent;
                this.deleteCategory(parseInt(id));
            });

            document.getElementById('viewMoveBtn')?.addEventListener('click', () => {
                const id = document.getElementById('viewCategoryId').textContent;
                this.showMoveModal(parseInt(id));
            });
        }

        async loadCategories() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/IndustryCategory');
                const result = await response.json();

                if (result.success) {
                    this.categories = result.data || [];
                    this.buildTreeData();
                    this.renderCategories();
                    this.updateStatistics();
                    this.populateParentSelects();
                    showToast('Industry categories loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load categories');
                }
            } catch (error) {
                handleApiError(error, 'loading industry categories');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        buildTreeData() {
            // Build hierarchical tree structure
            this.treeData = this.buildTree(this.categories);

            // Build level filter options
            const maxLevel = Math.max(...this.categories.map(c => c.level || 0));
            this.updateLevelFilter(maxLevel);
        }

        buildTree(categories, parentId = null) {
            return categories
                .filter(cat => cat.parent_id == parentId)
                .sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0))
                .map(cat => ({
                    ...cat,
                    children: this.buildTree(categories, cat.id)
                }));
        }

        updateLevelFilter(maxLevel) {
            const levelFilter = document.getElementById('levelFilter');
            if (!levelFilter) return;

            // Clear existing options except "All Levels"
            levelFilter.innerHTML = '<option value="">All Levels</option>';

            for (let i = 0; i <= maxLevel; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i === 0 ? 'Root Level' : `Level ${i}`;
                levelFilter.appendChild(option);
            }
        }

        renderCategories() {
            const container = document.getElementById('categoriesDisplay');
            if (!container) return;

            if (this.categories.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🏭</span>
                            <h5 class="mt-3">No industry categories found</h5>
                            <p>Start by adding your first category using the "Add Category" button above.</p>
                        </div>
                    </div>
                `;
                return;
            }

            if (this.currentViewMode === 'tree') {
                this.renderTreeView(container);
            } else {
                this.renderListView(container);
            }

            this.updateCategoryCount();
        }

        renderTreeView(container) {
            let html = '<div class="tree-container">';
            html += this.renderTreeNode(this.treeData, 0);
            html += '</div>';
            container.innerHTML = html;
        }

        renderTreeNode(nodes, level) {
            let html = '';

            nodes.forEach(node => {
                const hasChildren = node.children && node.children.length > 0;
                const statusBadge = this.getStatusBadge(node);
                const featuredBadge = node.is_featured ? '<span class="badge bg-warning text-dark ms-1">★</span>' : '';
                const icon = node.icon || '🏭';
                const indent = '  '.repeat(level);

                html += `
                    <div class="category-item mb-2 p-2 border rounded" style="margin-left: ${level * 20}px; border-left: 3px solid ${node.color || '#6c757d'};">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                ${hasChildren ?
                                    `<span class="tree-toggle me-2" onclick="industryManager.toggleNode(this)">🔽</span>` :
                                    '<span class="me-2" style="width: 20px;"></span>'
                                }
                                <span class="me-2">${icon}</span>
                                <div>
                                    <strong>${node.name}</strong>
                                    ${featuredBadge}
                                    <div class="small text-muted">
                                        ${node.naics_code ? `NAICS: ${node.naics_code}` : ''}
                                        ${node.sic_code ? `• SIC: ${node.sic_code}` : ''}
                                        ${hasChildren ? `• ${node.children.length} children` : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                ${statusBadge}
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info btn-sm" onclick="industryManager.viewCategory(${node.id})" title="View Details">
                                        👁️
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="industryManager.editCategory(${node.id})" title="Edit">
                                        ✏️
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" onclick="industryManager.showAddModal(${node.id})" title="Add Child">
                                        ➕
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="industryManager.deleteCategory(${node.id})" title="Delete">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </div>
                        ${hasChildren ? `<div class="children mt-2">${this.renderTreeNode(node.children, level + 1)}</div>` : ''}
                    </div>
                `;
            });

            return html;
        }

        renderListView(container) {
            let html = `
                <!-- Desktop Table View -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Codes</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            const flatCategories = this.flattenCategories(this.treeData);
            flatCategories.forEach(category => {
                const statusBadge = this.getStatusBadge(category);
                const featuredBadge = category.is_featured ? '<span class="badge bg-warning text-dark ms-1">★</span>' : '';
                const icon = category.icon || '🏭';
                const indent = '  '.repeat(category.level || 0);

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2">${icon}</span>
                                <div>
                                    <div class="fw-semibold">${indent}${category.name}${featuredBadge}</div>
                                    ${category.description ? `<small class="text-muted">${category.description.substring(0, 60)}...</small>` : ''}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                ${category.naics_code ? `<div>NAICS: ${category.naics_code}</div>` : ''}
                                ${category.sic_code ? `<div>SIC: ${category.sic_code}</div>` : ''}
                                ${category.isic_code ? `<div>ISIC: ${category.isic_code}</div>` : ''}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">Level ${category.level || 0}</span>
                        </td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="industryManager.viewCategory(${category.id})" title="View">👁️</button>
                                <button class="btn btn-outline-primary" onclick="industryManager.editCategory(${category.id})" title="Edit">✏️</button>
                                <button class="btn btn-outline-danger" onclick="industryManager.deleteCategory(${category.id})" title="Delete">🗑️</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="d-lg-none">
            `;

            // Mobile cards
            flatCategories.forEach(category => {
                const statusBadge = this.getStatusBadge(category);
                const featuredBadge = category.is_featured ? '<span class="badge bg-warning text-dark ms-1">★</span>' : '';
                const icon = category.icon || '🏭';

                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <div class="text-center" style="font-size: 2rem;">${icon}</div>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">${category.name}${featuredBadge}</h6>
                                    <div class="small text-muted mb-1">Level ${category.level || 0}</div>
                                    ${category.naics_code ? `<div class="small text-muted">NAICS: ${category.naics_code}</div>` : ''}
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        ${statusBadge}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">⚙️</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="industryManager.viewCategory(${category.id})">👁️ View Details</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="industryManager.editCategory(${category.id})">✏️ Edit</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="industryManager.showAddModal(${category.id})">➕ Add Child</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="industryManager.deleteCategory(${category.id})">🗑️ Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        flattenCategories(nodes, result = []) {
            nodes.forEach(node => {
                result.push(node);
                if (node.children && node.children.length > 0) {
                    this.flattenCategories(node.children, result);
                }
            });
            return result;
        }

        getStatusBadge(category) {
            return category.is_active ?
                '<span class="badge bg-success">Active</span>' :
                '<span class="badge bg-secondary">Inactive</span>';
        }

        switchViewMode(mode) {
            this.currentViewMode = mode;
            this.renderCategories();
        }

        toggleNode(toggleElement) {
            const categoryItem = toggleElement.closest('.category-item');
            const childrenContainer = categoryItem.querySelector('.children');

            if (childrenContainer) {
                const isCollapsed = childrenContainer.style.display === 'none';
                childrenContainer.style.display = isCollapsed ? 'block' : 'none';
                toggleElement.textContent = isCollapsed ? '🔽' : '▶️';
            }
        }

        updateStatistics() {
            const totalElement = document.getElementById('totalCategories');
            const rootElement = document.getElementById('rootCategories');
            const activeElement = document.getElementById('activeCategories');
            const featuredElement = document.getElementById('featuredCategories');
            const maxLevelElement = document.getElementById('maxLevel');
            const leafElement = document.getElementById('leafCategories');

            if (totalElement) totalElement.textContent = this.categories.length;

            const rootCount = this.categories.filter(c => !c.parent_id).length;
            const activeCount = this.categories.filter(c => c.is_active).length;
            const featuredCount = this.categories.filter(c => c.is_featured).length;
            const maxLevel = Math.max(...this.categories.map(c => c.level || 0));
            const leafCount = this.categories.filter(c => !this.categories.some(child => child.parent_id === c.id)).length;

            if (rootElement) rootElement.textContent = rootCount;
            if (activeElement) activeElement.textContent = activeCount;
            if (featuredElement) featuredElement.textContent = featuredCount;
            if (maxLevelElement) maxLevelElement.textContent = maxLevel;
            if (leafElement) leafElement.textContent = leafCount;
        }

        updateCategoryCount() {
            const countElement = document.getElementById('categoryCount');
            if (countElement) {
                const count = this.filteredCategories.length || this.categories.length;
                countElement.textContent = `${count} categories`;
            }
        }

        populateParentSelects() {
            const selects = [
                document.getElementById('parent_id'),
                document.getElementById('editParent_id'),
                document.getElementById('moveNewParent')
            ];

            selects.forEach(select => {
                if (!select) return;

                // Keep first option (usually "Root Level")
                const firstOption = select.firstElementChild?.cloneNode(true);
                select.innerHTML = '';
                if (firstOption) select.appendChild(firstOption);

                this.addParentOptions(select, this.treeData, 0);
            });
        }

        addParentOptions(select, nodes, level) {
            nodes.forEach(node => {
                const option = document.createElement('option');
                option.value = node.id;
                option.textContent = '  '.repeat(level) + (node.icon || '🏭') + ' ' + node.name;
                select.appendChild(option);

                if (node.children && node.children.length > 0) {
                    this.addParentOptions(select, node.children, level + 1);
                }
            });
        }

        handleSearch(e) {
            const query = e.target.value.toLowerCase().trim();

            if (!query) {
                this.filteredCategories = [];
                this.renderCategories();
                return;
            }

            this.filteredCategories = this.categories.filter(category => {
                return category.name?.toLowerCase().includes(query) ||
                       category.description?.toLowerCase().includes(query) ||
                       category.naics_code?.toLowerCase().includes(query) ||
                       category.sic_code?.toLowerCase().includes(query) ||
                       category.isic_code?.toLowerCase().includes(query);
            });

            this.renderFilteredResults();
        }

        applyFilters() {
            const statusFilter = document.getElementById('statusFilter')?.value;
            const levelFilter = document.getElementById('levelFilter')?.value;
            const featuredFilter = document.getElementById('featuredFilter')?.value;

            let filtered = [...this.categories];

            if (statusFilter === 'active') {
                filtered = filtered.filter(c => c.is_active);
            } else if (statusFilter === 'inactive') {
                filtered = filtered.filter(c => !c.is_active);
            }

            if (levelFilter !== '') {
                filtered = filtered.filter(c => (c.level || 0) == parseInt(levelFilter));
            }

            if (featuredFilter === 'featured') {
                filtered = filtered.filter(c => c.is_featured);
            } else if (featuredFilter === 'regular') {
                filtered = filtered.filter(c => !c.is_featured);
            }

            this.filteredCategories = filtered;
            this.renderFilteredResults();
        }

        renderFilteredResults() {
            // For filtered results, always show list view
            const container = document.getElementById('categoriesDisplay');
            if (!container) return;

            if (this.filteredCategories.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🔍</span>
                            <h5 class="mt-3">No categories found</h5>
                            <p>Try adjusting your search or filter criteria.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Build simple list for filtered results
            let html = '<div class="filtered-results">';

            this.filteredCategories.forEach(category => {
                const statusBadge = this.getStatusBadge(category);
                const featuredBadge = category.is_featured ? '<span class="badge bg-warning text-dark ms-1">★</span>' : '';
                const icon = category.icon || '🏭';

                html += `
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="me-3" style="font-size: 1.5rem;">${icon}</span>
                                    <div>
                                        <h6 class="mb-1">${category.name}${featuredBadge}</h6>
                                        <div class="small text-muted">
                                            Level ${category.level || 0}
                                            ${category.naics_code ? ` • NAICS: ${category.naics_code}` : ''}
                                            ${category.sic_code ? ` • SIC: ${category.sic_code}` : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    ${statusBadge}
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" onclick="industryManager.viewCategory(${category.id})">👁️</button>
                                        <button class="btn btn-outline-primary" onclick="industryManager.editCategory(${category.id})">✏️</button>
                                        <button class="btn btn-outline-danger" onclick="industryManager.deleteCategory(${category.id})">🗑️</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
            this.updateCategoryCount();
        }

        showAddModal(parentId = null) {
            const modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));

            // Reset form
            document.getElementById('addCategoryForm').reset();

            // Set parent if provided
            if (parentId) {
                document.getElementById('parent_id').value = parentId;
            }

            modal.show();
        }

        async handleAdd(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            // Convert checkboxes
            data.is_active = document.getElementById('is_active').checked ? 1 : 0;
            data.is_featured = document.getElementById('is_featured').checked ? 1 : 0;

            try {
                const response = await fetch('/api/entities/IndustryCategory', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
                    showToast('Industry category added successfully', 'success');
                    this.loadCategories();
                } else {
                    throw new Error(result.message || 'Failed to add category');
                }
            } catch (error) {
                handleApiError(error, 'adding industry category');
            }
        }

        editCategory(id) {
            const category = this.categories.find(c => c.id == id);
            if (!category) return;

            // Populate edit form
            document.getElementById('editCategoryId').value = category.id;
            document.getElementById('editName').value = category.name || '';
            document.getElementById('editParent_id').value = category.parent_id || '';
            document.getElementById('editNaics_code').value = category.naics_code || '';
            document.getElementById('editSic_code').value = category.sic_code || '';
            document.getElementById('editIsic_code').value = category.isic_code || '';
            document.getElementById('editIcon').value = category.icon || '';
            document.getElementById('editColor').value = category.color || '#6c757d';
            document.getElementById('editSort_order').value = category.sort_order || 0;
            document.getElementById('editDescription').value = category.description || '';
            document.getElementById('editIs_active').checked = !!category.is_active;
            document.getElementById('editIs_featured').checked = !!category.is_featured;

            const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            modal.show();
        }

        async handleEdit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            // Convert checkboxes
            data.is_active = document.getElementById('editIs_active').checked ? 1 : 0;
            data.is_featured = document.getElementById('editIs_featured').checked ? 1 : 0;

            try {
                const response = await fetch(`/api/entities/IndustryCategory/${data.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
                    showToast('Industry category updated successfully', 'success');
                    this.loadCategories();
                } else {
                    throw new Error(result.message || 'Failed to update category');
                }
            } catch (error) {
                handleApiError(error, 'updating industry category');
            }
        }

        viewCategory(id) {
            const category = this.categories.find(c => c.id == id);
            if (!category) return;

            // Basic information
            document.getElementById('viewName').textContent = category.name || 'N/A';
            document.getElementById('viewFullName').textContent = this.getFullPath(category) || 'N/A';
            document.getElementById('viewLevel').textContent = category.level || 0;
            document.getElementById('viewIcon').textContent = category.icon || 'N/A';
            document.getElementById('viewSortOrder').textContent = category.sort_order || 0;
            document.getElementById('viewNaicsCode').textContent = category.naics_code || 'N/A';
            document.getElementById('viewSicCode').textContent = category.sic_code || 'N/A';
            document.getElementById('viewIsicCode').textContent = category.isic_code || 'N/A';
            document.getElementById('viewSlug').textContent = category.slug || 'N/A';
            document.getElementById('viewDescription').textContent = category.description || 'N/A';
            document.getElementById('viewCategoryId').textContent = category.id;

            // Color display
            const colorElement = document.getElementById('viewColor');
            colorElement.innerHTML = `<span class="badge" style="background-color: ${category.color || '#6c757d'}">${category.color || '#6c757d'}</span>`;

            // Status and metadata
            const statusElement = document.getElementById('viewStatus');
            statusElement.innerHTML = this.getStatusBadge(category);

            const featuredElement = document.getElementById('viewFeatured');
            featuredElement.innerHTML = category.is_featured ?
                '<span class="badge bg-warning text-dark">★ Featured</span>' :
                '<span class="badge bg-secondary">Regular</span>';

            const typeElement = document.getElementById('viewCategoryType');
            const children = this.categories.filter(c => c.parent_id === category.id);
            const hasParent = !!category.parent_id;
            let typeText = 'Leaf Category';
            if (!hasParent && children.length > 0) typeText = 'Root Category';
            else if (hasParent && children.length > 0) typeText = 'Branch Category';
            typeElement.textContent = typeText;

            document.getElementById('viewChildrenCount').textContent = children.length;

            // Dates
            document.getElementById('viewCreatedAt').textContent = category.created_at ?
                new Date(category.created_at).toLocaleDateString() : 'N/A';
            document.getElementById('viewUpdatedAt').textContent = category.updated_at ?
                new Date(category.updated_at).toLocaleDateString() : 'N/A';

            // Breadcrumb
            this.renderBreadcrumb(category);

            // Children list
            this.renderChildrenList(category);

            const modal = new bootstrap.Modal(document.getElementById('viewCategoryModal'));
            modal.show();
        }

        getFullPath(category) {
            const path = [];
            let current = category;

            while (current) {
                path.unshift(current.name);
                current = this.categories.find(c => c.id === current.parent_id);
            }

            return path.join(' > ');
        }

        renderBreadcrumb(category) {
            const breadcrumbContainer = document.getElementById('viewCategoryBreadcrumb');
            if (!breadcrumbContainer) return;

            const path = [];
            let current = category;

            while (current) {
                path.unshift(current);
                current = this.categories.find(c => c.id === current.parent_id);
            }

            let html = '<li class="breadcrumb-item"><a href="#" onclick="industryManager.showRootLevel()">🏠 Root</a></li>';

            path.forEach((cat, index) => {
                if (index === path.length - 1) {
                    html += `<li class="breadcrumb-item active">${cat.icon || '🏭'} ${cat.name}</li>`;
                } else {
                    html += `<li class="breadcrumb-item"><a href="#" onclick="industryManager.viewCategory(${cat.id})">${cat.icon || '🏭'} ${cat.name}</a></li>`;
                }
            });

            breadcrumbContainer.innerHTML = html;
        }

        renderChildrenList(category) {
            const childrenContainer = document.getElementById('viewChildrenList');
            if (!childrenContainer) return;

            const children = this.categories.filter(c => c.parent_id === category.id);

            if (children.length === 0) {
                childrenContainer.innerHTML = '<p class="text-muted">No child categories</p>';
                return;
            }

            let html = '<div class="row">';
            children.forEach(child => {
                const statusBadge = this.getStatusBadge(child);
                const featuredBadge = child.is_featured ? '<span class="badge bg-warning text-dark ms-1">★</span>' : '';

                html += `
                    <div class="col-md-6 mb-2">
                        <div class="card card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="me-2">${child.icon || '🏭'}</span>
                                    <div>
                                        <small class="fw-semibold">${child.name}${featuredBadge}</small>
                                        <div class="small text-muted">${child.naics_code || 'No NAICS'}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    ${statusBadge}
                                    <button class="btn btn-outline-info btn-sm" onclick="industryManager.viewCategory(${child.id})" title="View">👁️</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            childrenContainer.innerHTML = html;
        }

        showMoveModal(id) {
            const category = this.categories.find(c => c.id == id);
            if (!category) return;

            document.getElementById('moveCategoryId').value = id;

            // Populate move options (exclude the category itself and its descendants)
            const moveSelect = document.getElementById('moveNewParent');
            moveSelect.innerHTML = '<option value="">Root Level</option>';

            const excludeIds = this.getDescendantIds(id);
            excludeIds.push(id);

            const availableCategories = this.categories.filter(c => !excludeIds.includes(c.id));
            this.addParentOptions(moveSelect, this.buildTree(availableCategories), 0);

            const modal = new bootstrap.Modal(document.getElementById('moveCategoryModal'));
            modal.show();
        }

        getDescendantIds(parentId) {
            const descendants = [];
            const children = this.categories.filter(c => c.parent_id === parentId);

            children.forEach(child => {
                descendants.push(child.id);
                descendants.push(...this.getDescendantIds(child.id));
            });

            return descendants;
        }

        async handleMove(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(`/api/entities/IndustryCategory/${data.id}/move`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ new_parent_id: data.new_parent_id || null })
                });

                const result = await response.json();
                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('moveCategoryModal')).hide();
                    showToast('Category moved successfully', 'success');
                    this.loadCategories();
                } else {
                    throw new Error(result.message || 'Failed to move category');
                }
            } catch (error) {
                handleApiError(error, 'moving category');
            }
        }

        async deleteCategory(id) {
            const category = this.categories.find(c => c.id == id);
            if (!category) return;

            const hasChildren = this.categories.some(c => c.parent_id === id);

            let message = `Are you sure you want to delete "${category.name}"?`;
            if (hasChildren) {
                message += '\n\nWarning: This category has child categories. They will also be deleted.';
            }

            if (!confirm(message)) return;

            try {
                const response = await fetch(`/api/entities/IndustryCategory/${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                if (result.success) {
                    showToast('Industry category deleted successfully', 'success');
                    this.loadCategories();
                } else {
                    throw new Error(result.message || 'Failed to delete category');
                }
            } catch (error) {
                handleApiError(error, 'deleting industry category');
            }
        }

        showRootLevel() {
            this.currentParent = null;
            const breadcrumbRow = document.getElementById('breadcrumbRow');
            if (breadcrumbRow) breadcrumbRow.style.display = 'none';
            this.renderCategories();
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.industryManager = new IndustryCategoryManager();
    });
</script>
<?php elseif ($current_page === 'organization_legal_types'): ?>
<script>
    class OrganizationLegalTypeManager {
        constructor() {
            this.legalTypes = [];
            this.countries = [];
            this.currentPage = 1;
            this.itemsPerPage = 10;
            this.filteredLegalTypes = [];
            this.init();
        }

        init() {
            this.loadCountries();
            this.loadLegalTypes();
            this.bindEvents();
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadLegalTypes());
            document.getElementById('newLegalTypeBtn')?.addEventListener('click', () => this.showNewLegalTypeModal());

            // Form event handlers
            document.getElementById('addLegalTypeForm')?.addEventListener('submit', (e) => this.handleAddLegalType(e));
            document.getElementById('editLegalTypeForm')?.addEventListener('submit', (e) => this.handleEditLegalType(e));

            // Search and filter handlers
            document.getElementById('searchLegalTypes')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('countryFilter')?.addEventListener('change', (e) => this.handleFilter());
            document.getElementById('categoryFilter')?.addEventListener('change', (e) => this.handleFilter());
            document.getElementById('liabilityFilter')?.addEventListener('change', (e) => this.handleFilter());
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter());
            document.getElementById('usageFilter')?.addEventListener('change', (e) => this.handleFilter());
        }

        async loadCountries() {
            try {
                const response = await fetch('/api/entities/Country');
                const result = await response.json();

                if (result.success) {
                    this.countries = result.data || [];
                    this.populateCountryDropdowns();
                }
            } catch (error) {
                console.error('Failed to load countries:', error);
            }
        }

        populateCountryDropdowns() {
            const selects = ['country_id', 'editCountry_id', 'countryFilter'];

            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    const currentValue = select.value;
                    if (selectId === 'countryFilter') {
                        select.innerHTML = '<option value="">All Countries</option>';
                    } else {
                        select.innerHTML = '<option value="">Select Country</option>';
                    }

                    this.countries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.id;
                        option.textContent = country.name;
                        select.appendChild(option);
                    });

                    if (currentValue) select.value = currentValue;
                }
            });
        }

        async loadLegalTypes() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/OrganizationLegalType');
                const result = await response.json();

                if (result.success) {
                    this.legalTypes = result.data || [];
                    this.filteredLegalTypes = [...this.legalTypes];
                    this.renderLegalTypes();
                    this.updateStatistics();
                    showToast('Legal types loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load legal types');
                }
            } catch (error) {
                handleApiError(error, 'loading legal types');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        updateStatistics() {
            const totalTypes = this.legalTypes.length;
            const countryCount = new Set(this.legalTypes.map(lt => lt.country_id)).size;
            const activeTypes = this.legalTypes.filter(lt => lt.is_active == 1).length;
            const commonTypes = this.legalTypes.filter(lt => lt.is_commonly_used == 1).length;
            const publicTypes = this.legalTypes.filter(lt => lt.is_public_company == 1).length;
            const foreignAllowed = this.legalTypes.filter(lt => lt.allows_foreign_ownership == 1).length;

            document.getElementById('totalLegalTypes').textContent = totalTypes;
            document.getElementById('totalCountries').textContent = countryCount;
            document.getElementById('activeLegalTypes').textContent = activeTypes;
            document.getElementById('commonLegalTypes').textContent = commonTypes;
            document.getElementById('publicLegalTypes').textContent = publicTypes;
            document.getElementById('foreignAllowed').textContent = foreignAllowed;
        }

        handleSearch(event) {
            this.handleFilter();
        }

        handleFilter() {
            const searchTerm = document.getElementById('searchLegalTypes')?.value.toLowerCase() || '';
            const countryFilter = document.getElementById('countryFilter')?.value || '';
            const categoryFilter = document.getElementById('categoryFilter')?.value || '';
            const liabilityFilter = document.getElementById('liabilityFilter')?.value || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';
            const usageFilter = document.getElementById('usageFilter')?.value || '';

            this.filteredLegalTypes = this.legalTypes.filter(legalType => {
                const matchesSearch = !searchTerm ||
                    (legalType.name?.toLowerCase().includes(searchTerm)) ||
                    (legalType.abbreviation?.toLowerCase().includes(searchTerm)) ||
                    (legalType.description?.toLowerCase().includes(searchTerm)) ||
                    (legalType.category?.toLowerCase().includes(searchTerm)) ||
                    (legalType.jurisdiction?.toLowerCase().includes(searchTerm));

                const matchesCountry = !countryFilter || legalType.country_id == countryFilter;
                const matchesCategory = !categoryFilter || legalType.category === categoryFilter;
                const matchesLiability = !liabilityFilter || legalType.liability_type === liabilityFilter;

                const matchesStatus = !statusFilter ||
                    (statusFilter === 'active' && legalType.is_active == 1) ||
                    (statusFilter === 'inactive' && legalType.is_active == 0);

                const matchesUsage = !usageFilter ||
                    (usageFilter === 'common' && legalType.is_commonly_used == 1) ||
                    (usageFilter === 'uncommon' && legalType.is_commonly_used == 0);

                return matchesSearch && matchesCountry && matchesCategory && matchesLiability && matchesStatus && matchesUsage;
            });

            this.renderLegalTypes();
        }

        renderLegalTypes() {
            const container = document.getElementById('legalTypesDisplay');
            if (!container) return;

            if (this.filteredLegalTypes.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <span style="font-size: 3rem;">🏢</span>
                            <h5 class="mt-3">No legal types found</h5>
                            <p>Start by adding your first legal type using the "Add Legal Type" button above.</p>
                        </div>
                    </div>
                `;
                document.getElementById('legalTypeCount').textContent = '0 legal types';
                return;
            }

            document.getElementById('legalTypeCount').textContent = `${this.filteredLegalTypes.length} legal type${this.filteredLegalTypes.length !== 1 ? 's' : ''}`;

            // Build responsive HTML
            let html = `
                <!-- Desktop Table View (hidden on mobile) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Liability</th>
                                    <th>Tax Structure</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${this.filteredLegalTypes.map(legalType => this.renderLegalTypeRow(legalType)).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View (visible on mobile only) -->
                <div class="d-lg-none">
                    ${this.filteredLegalTypes.map(legalType => this.renderLegalTypeCard(legalType)).join('')}
                </div>
            `;

            container.innerHTML = html;
        }

        renderLegalTypeRow(legalType) {
            const country = this.countries.find(c => c.id == legalType.country_id);
            const countryName = country ? country.name : 'Unknown';

            const statusBadge = legalType.is_active == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';

            const usageBadge = legalType.is_commonly_used == 1
                ? '<span class="badge bg-primary">Common</span>'
                : '';

            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="fw-bold">${escapeHtml(legalType.name || '')}</div>
                                ${legalType.abbreviation ? `<small class="text-muted">${escapeHtml(legalType.abbreviation)}</small>` : ''}
                            </div>
                            ${usageBadge ? `<div class="ms-2">${usageBadge}</div>` : ''}
                        </div>
                    </td>
                    <td>${escapeHtml(countryName)}</td>
                    <td>
                        <span class="badge bg-info">${escapeHtml(legalType.category?.replace('_', ' ') || '')}</span>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark">${escapeHtml(legalType.liability_type || '')}</span>
                    </td>
                    <td>${escapeHtml(legalType.tax_structure?.replace('_', ' ') || '')}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="window.legalTypeManager.viewLegalType(${legalType.id})"
                                    title="View Details">
                                👁️
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="window.legalTypeManager.editLegalType(${legalType.id})"
                                    title="Edit">
                                ✏️
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        renderLegalTypeCard(legalType) {
            const country = this.countries.find(c => c.id == legalType.country_id);
            const countryName = country ? country.name : 'Unknown';

            const statusBadge = legalType.is_active == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';

            const usageBadge = legalType.is_commonly_used == 1
                ? '<span class="badge bg-primary">Common</span>'
                : '';

            return `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">${escapeHtml(legalType.name || '')}</h6>
                                ${legalType.abbreviation ? `<small class="text-muted">${escapeHtml(legalType.abbreviation)}</small>` : ''}
                                <div class="mt-1">
                                    ${statusBadge}
                                    ${usageBadge}
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                    ⋮
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="window.legalTypeManager.viewLegalType(${legalType.id})">👁️ View Details</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="window.legalTypeManager.editLegalType(${legalType.id})">✏️ Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="window.legalTypeManager.deleteLegalType(${legalType.id})">🗑️ Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="small text-muted">
                            <div><strong>Country:</strong> ${escapeHtml(countryName)}</div>
                            <div><strong>Category:</strong> ${escapeHtml(legalType.category?.replace('_', ' ') || '')}</div>
                            <div><strong>Liability:</strong> ${escapeHtml(legalType.liability_type || '')}</div>
                        </div>
                    </div>
                </div>
            `;
        }

        showNewLegalTypeModal() {
            document.getElementById('addLegalTypeForm').reset();
            const modal = new bootstrap.Modal(document.getElementById('addLegalTypeModal'));
            modal.show();
        }

        async handleAddLegalType(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            // Convert form data to object
            const legalTypeData = {};
            for (let [key, value] of formData.entries()) {
                if (value !== '') {
                    // Handle checkboxes
                    if (form.elements[key]?.type === 'checkbox') {
                        legalTypeData[key] = form.elements[key].checked ? 1 : 0;
                    } else {
                        legalTypeData[key] = value;
                    }
                }
            }

            try {
                const response = await fetch('/api/entities/OrganizationLegalType', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(legalTypeData)
                });

                const result = await response.json();

                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('addLegalTypeModal')).hide();
                    form.reset();
                    await this.loadLegalTypes();
                    showToast('Legal type created successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to create legal type');
                }
            } catch (error) {
                handleApiError(error, 'creating legal type');
            }
        }

        viewLegalType(id) {
            const legalType = this.legalTypes.find(lt => lt.id == id);
            if (!legalType) return;

            const country = this.countries.find(c => c.id == legalType.country_id);

            // Populate view modal
            document.getElementById('viewName').textContent = legalType.name || '-';
            document.getElementById('viewAbbreviation').textContent = legalType.abbreviation || '-';
            document.getElementById('viewCountry').textContent = country ? country.name : '-';
            document.getElementById('viewJurisdiction').textContent = legalType.jurisdiction || '-';
            document.getElementById('viewCategory').textContent = legalType.category?.replace('_', ' ') || '-';
            document.getElementById('viewLiabilityType').textContent = legalType.liability_type || '-';
            document.getElementById('viewTaxStructure').textContent = legalType.tax_structure?.replace('_', ' ') || '-';
            document.getElementById('viewFormationTime').textContent = this.getFormationTimeDescription(legalType);
            document.getElementById('viewFormationCost').textContent = legalType.formation_cost_range || '-';
            document.getElementById('viewMinCapital').textContent = this.getFormattedMinCapital(legalType);
            document.getElementById('viewDescription').textContent = legalType.description || '-';

            // Status information
            document.getElementById('viewStatus').innerHTML = legalType.is_active == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
            document.getElementById('viewUsage').innerHTML = legalType.is_commonly_used == 1
                ? '<span class="badge bg-primary">Common</span>'
                : '<span class="badge bg-light text-dark">Uncommon</span>';
            document.getElementById('viewCompanyType').innerHTML = legalType.is_public_company == 1
                ? '<span class="badge bg-info">Public</span>'
                : '<span class="badge bg-light text-dark">Private</span>';
            document.getElementById('viewForeignOwnership').textContent = this.getForeignOwnershipDescription(legalType);

            // Other details
            document.getElementById('viewLegalTypeId').textContent = legalType.id;
            document.getElementById('viewCreatedAt').textContent = new Date(legalType.created_at).toLocaleDateString();
            document.getElementById('viewUpdatedAt').textContent = new Date(legalType.updated_at).toLocaleDateString();

            // Ranges
            document.getElementById('viewShareholderRange').textContent = this.getShareholderRange(legalType);
            document.getElementById('viewDirectorRange').textContent = this.getDirectorRange(legalType);

            // Requirements
            this.populateRequirements(legalType);

            // Advantages & Disadvantages
            this.populateAdvantages(legalType);
            this.populateDisadvantages(legalType);

            // Usage & Examples
            document.getElementById('viewCommonUsage').textContent = legalType.common_usage || '-';
            this.populateExamples(legalType);

            // Bind action buttons
            const editBtn = document.getElementById('viewEditBtn');
            const deleteBtn = document.getElementById('viewDeleteBtn');

            editBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewLegalTypeModal')).hide();
                this.editLegalType(id);
            };

            deleteBtn.onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewLegalTypeModal')).hide();
                this.deleteLegalType(id);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewLegalTypeModal'));
            modal.show();
        }

        editLegalType(id) {
            const legalType = this.legalTypes.find(lt => lt.id == id);
            if (!legalType) return;

            // Populate edit form
            document.getElementById('editLegalTypeId').value = legalType.id;
            document.getElementById('editName').value = legalType.name || '';
            document.getElementById('editAbbreviation').value = legalType.abbreviation || '';
            document.getElementById('editSort_order').value = legalType.sort_order || '';
            document.getElementById('editCountry_id').value = legalType.country_id || '';
            document.getElementById('editJurisdiction').value = legalType.jurisdiction || '';
            document.getElementById('editDescription').value = legalType.description || '';
            document.getElementById('editCategory').value = legalType.category || '';
            document.getElementById('editLiability_type').value = legalType.liability_type || '';
            document.getElementById('editTax_structure').value = legalType.tax_structure || '';
            document.getElementById('editMin_shareholders').value = legalType.min_shareholders || '';
            document.getElementById('editMax_shareholders').value = legalType.max_shareholders || '';
            document.getElementById('editMin_directors').value = legalType.min_directors || '';
            document.getElementById('editMax_directors').value = legalType.max_directors || '';
            document.getElementById('editMin_capital_required').value = legalType.min_capital_required || '';
            document.getElementById('editCurrency_code').value = legalType.currency_code || '';
            document.getElementById('editFormation_time_days').value = legalType.formation_time_days || '';
            document.getElementById('editFormation_cost_range').value = legalType.formation_cost_range || '';
            document.getElementById('editAllows_foreign_ownership').checked = legalType.allows_foreign_ownership == 1;
            document.getElementById('editForeign_ownership_limit').value = legalType.foreign_ownership_limit || '';
            document.getElementById('editRequires_local_director').checked = legalType.requires_local_director == 1;
            document.getElementById('editRequires_company_secretary').checked = legalType.requires_company_secretary == 1;
            document.getElementById('editRequires_registered_office').checked = legalType.requires_registered_office == 1;
            document.getElementById('editAllows_single_director').checked = legalType.allows_single_director == 1;
            document.getElementById('editAllows_nominee_directors').checked = legalType.allows_nominee_directors == 1;
            document.getElementById('editIs_public_company').checked = legalType.is_public_company == 1;
            document.getElementById('editIs_active').checked = legalType.is_active == 1;
            document.getElementById('editIs_commonly_used').checked = legalType.is_commonly_used == 1;
            document.getElementById('editRegulatory_authority').value = legalType.regulatory_authority || '';
            document.getElementById('editLegal_code').value = legalType.legal_code || '';
            document.getElementById('editAdvantages').value = legalType.advantages || '';
            document.getElementById('editDisadvantages').value = legalType.disadvantages || '';
            document.getElementById('editCommon_usage').value = legalType.common_usage || '';
            document.getElementById('editExamples').value = legalType.examples || '';

            const modal = new bootstrap.Modal(document.getElementById('editLegalTypeModal'));
            modal.show();
        }

        async handleEditLegalType(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            const id = formData.get('id');
            const legalTypeData = {};

            for (let [key, value] of formData.entries()) {
                if (key !== 'id') {
                    if (form.elements[key]?.type === 'checkbox') {
                        legalTypeData[key] = form.elements[key].checked ? 1 : 0;
                    } else {
                        legalTypeData[key] = value;
                    }
                }
            }

            try {
                const response = await fetch(`/api/entities/OrganizationLegalType/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(legalTypeData)
                });

                const result = await response.json();

                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editLegalTypeModal')).hide();
                    await this.loadLegalTypes();
                    showToast('Legal type updated successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to update legal type');
                }
            } catch (error) {
                handleApiError(error, 'updating legal type');
            }
        }

        async deleteLegalType(id) {
            const legalType = this.legalTypes.find(lt => lt.id == id);
            if (!legalType) return;

            if (!confirm(`Are you sure you want to delete ${legalType.name || 'this legal type'}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/OrganizationLegalType/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    await this.loadLegalTypes();
                    showToast('Legal type deleted successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to delete legal type');
                }
            } catch (error) {
                handleApiError(error, 'deleting legal type');
            }
        }

        // Helper methods
        getFormationTimeDescription(legalType) {
            const days = legalType.formation_time_days;
            if (!days || days <= 0) return 'Formation time varies';
            if (days === 1) return '1 day';
            if (days <= 7) return `${days} days (1 week)`;
            if (days <= 30) {
                const weeks = Math.ceil(days / 7);
                return `${days} days (${weeks} weeks)`;
            }
            const months = Math.ceil(days / 30);
            return `${days} days (${months} months)`;
        }

        getFormattedMinCapital(legalType) {
            if (!legalType.min_capital_required || legalType.min_capital_required <= 0) {
                return 'No minimum capital required';
            }
            const currency = legalType.currency_code || 'USD';
            return `${currency} ${Number(legalType.min_capital_required).toLocaleString()}`;
        }

        getForeignOwnershipDescription(legalType) {
            if (legalType.allows_foreign_ownership != 1) {
                return 'Foreign ownership not allowed';
            }
            if (legalType.foreign_ownership_limit >= 100) {
                return '100% foreign ownership allowed';
            }
            return `Up to ${legalType.foreign_ownership_limit}% foreign ownership allowed`;
        }

        getShareholderRange(legalType) {
            const min = legalType.min_shareholders || 1;
            const max = legalType.max_shareholders;

            if (!max) return `Minimum ${min} shareholder(s), no maximum`;
            if (min === max) return `Exactly ${min} shareholder(s)`;
            return `Between ${min} and ${max} shareholders`;
        }

        getDirectorRange(legalType) {
            const min = legalType.min_directors || 1;
            const max = legalType.max_directors;

            if (!max) return `Minimum ${min} director(s), no maximum`;
            if (min === max) return `Exactly ${min} director(s)`;
            return `Between ${min} and ${max} directors`;
        }

        populateRequirements(legalType) {
            const container = document.getElementById('viewRequirements');
            const requirements = [];

            if (legalType.requires_local_director == 1) requirements.push('Local director required');
            if (legalType.requires_company_secretary == 1) requirements.push('Company secretary required');
            if (legalType.requires_registered_office == 1) requirements.push('Registered office required');
            if (legalType.min_capital_required > 0) requirements.push(`Minimum capital: ${this.getFormattedMinCapital(legalType)}`);
            if (legalType.allows_foreign_ownership != 1) requirements.push('Local ownership only');
            else if (legalType.foreign_ownership_limit < 100) requirements.push(`Foreign ownership up to ${legalType.foreign_ownership_limit}%`);

            if (requirements.length === 0) {
                container.innerHTML = '<p class="text-muted">No specific requirements</p>';
            } else {
                container.innerHTML = '<ul class="list-unstyled">' +
                    requirements.map(req => `<li><i class="text-primary">•</i> ${req}</li>`).join('') +
                    '</ul>';
            }
        }

        populateAdvantages(legalType) {
            const container = document.getElementById('viewAdvantages');
            if (!legalType.advantages) {
                container.innerHTML = '<p class="text-muted">No advantages listed</p>';
                return;
            }

            const advantages = legalType.advantages.split('\n').filter(a => a.trim());
            container.innerHTML = '<ul class="list-unstyled">' +
                advantages.map(adv => `<li><i class="text-success">✓</i> ${escapeHtml(adv.trim())}</li>`).join('') +
                '</ul>';
        }

        populateDisadvantages(legalType) {
            const container = document.getElementById('viewDisadvantages');
            if (!legalType.disadvantages) {
                container.innerHTML = '<p class="text-muted">No disadvantages listed</p>';
                return;
            }

            const disadvantages = legalType.disadvantages.split('\n').filter(d => d.trim());
            container.innerHTML = '<ul class="list-unstyled">' +
                disadvantages.map(dis => `<li><i class="text-danger">×</i> ${escapeHtml(dis.trim())}</li>`).join('') +
                '</ul>';
        }

        populateExamples(legalType) {
            const container = document.getElementById('viewExamples');
            if (!legalType.examples) {
                container.innerHTML = '<p class="text-muted">No examples provided</p>';
                return;
            }

            const examples = legalType.examples.split(',').filter(e => e.trim());
            container.innerHTML = '<ul class="list-unstyled">' +
                examples.map(ex => `<li><i class="text-info">•</i> ${escapeHtml(ex.trim())}</li>`).join('') +
                '</ul>';
        }
    }

    // Initialize the manager when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        window.legalTypeManager = new OrganizationLegalTypeManager();

        // Override the global refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                if (window.legalTypeManager && window.legalTypeManager.loadLegalTypes) {
                    window.legalTypeManager.loadLegalTypes();
                }
            });
        }
    });
</script>
<?php elseif ($current_page === 'organizations'): ?>
<script>
    class OrganizationManager {
        constructor() {
            this.organizations = [];
            this.industryCategories = [];
            this.legalTypes = [];
            this.persons = [];
            this.currentPage = 1;
            this.itemsPerPage = 10;
            this.currentFilters = {
                search: '',
                status: '',
                verification_status: '',
                industry_category: '',
                legal_type: '',
                business_model: ''
            };
            this.init();
        }

        init() {
            this.loadInitialData();
            this.bindEvents();
        }

        async loadInitialData() {
            await Promise.all([
                this.loadOrganizations(),
                this.loadIndustryCategories(),
                this.loadLegalTypes(),
                this.loadPersons()
            ]);
        }

        bindEvents() {
            document.getElementById('refreshBtn')?.addEventListener('click', () => this.loadOrganizations());
            document.getElementById('newOrganizationBtn')?.addEventListener('click', () => this.showNewOrganizationModal());

            // Form event handlers
            document.getElementById('addOrganizationForm')?.addEventListener('submit', (e) => this.handleAddOrganization(e));
            document.getElementById('editOrganizationForm')?.addEventListener('submit', (e) => this.handleEditOrganization(e));

            // Search and filter handlers
            document.getElementById('searchOrganizations')?.addEventListener('input', (e) => this.handleSearch(e));
            document.getElementById('statusFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('verificationFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('industryFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('legalTypeFilter')?.addEventListener('change', (e) => this.handleFilter(e));
            document.getElementById('businessModelFilter')?.addEventListener('change', (e) => this.handleFilter(e));

            // Subdomain validation
            document.getElementById('addSubdomain')?.addEventListener('input', (e) => this.validateSubdomain(e.target, 'add'));
            document.getElementById('editSubdomain')?.addEventListener('input', (e) => this.validateSubdomain(e.target, 'edit'));

            // Clear filters
            document.getElementById('clearFilters')?.addEventListener('click', () => this.clearFilters());
        }

        async loadOrganizations() {
            const refreshBtn = document.getElementById('refreshBtn');
            setLoadingState(refreshBtn, true);

            try {
                const response = await fetch('/api/entities/Organization');
                const result = await response.json();

                if (result.success) {
                    this.organizations = result.data || [];
                    this.renderOrganizations();
                    this.updateStatistics();
                    showToast('Organizations loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load organizations');
                }
            } catch (error) {
                handleApiError(error, 'loading organizations');
            } finally {
                setLoadingState(refreshBtn, false);
            }
        }

        async loadIndustryCategories() {
            try {
                const response = await fetch('/api/entities/IndustryCategory');
                const result = await response.json();

                if (result.success) {
                    this.industryCategories = result.data || [];
                    this.populateIndustryFilters();
                }
            } catch (error) {
                console.error('Error loading industry categories:', error);
            }
        }

        async loadLegalTypes() {
            try {
                const response = await fetch('/api/entities/OrganizationLegalType');
                const result = await response.json();

                if (result.success) {
                    this.legalTypes = result.data || [];
                    this.populateLegalTypeFilters();
                }
            } catch (error) {
                console.error('Error loading legal types:', error);
            }
        }

        async loadPersons() {
            try {
                const response = await fetch('/api/entities/Person');
                const result = await response.json();

                if (result.success) {
                    this.persons = result.data || [];
                    this.populatePersonSelects();
                }
            } catch (error) {
                console.error('Error loading persons:', error);
            }
        }

        populateIndustryFilters() {
            const filter = document.getElementById('industryFilter');
            const addSelect = document.getElementById('addIndustryCategory');
            const editSelect = document.getElementById('editIndustryCategory');

            if (filter) {
                filter.innerHTML = '<option value="">All Industries</option>';
                this.industryCategories.forEach(category => {
                    filter.innerHTML += `<option value="${category.id}">${escapeHtml(category.name)}</option>`;
                });
            }

            [addSelect, editSelect].forEach(select => {
                if (select) {
                    select.innerHTML = '<option value="">Select Industry Category</option>';
                    this.industryCategories.forEach(category => {
                        select.innerHTML += `<option value="${category.id}">${escapeHtml(category.name)}</option>`;
                    });
                }
            });
        }

        populateLegalTypeFilters() {
            const filter = document.getElementById('legalTypeFilter');
            const addSelect = document.getElementById('addLegalType');
            const editSelect = document.getElementById('editLegalType');

            if (filter) {
                filter.innerHTML = '<option value="">All Legal Types</option>';
                this.legalTypes.forEach(type => {
                    filter.innerHTML += `<option value="${type.id}">${escapeHtml(type.name)}</option>`;
                });
            }

            [addSelect, editSelect].forEach(select => {
                if (select) {
                    select.innerHTML = '<option value="">Select Legal Type</option>';
                    this.legalTypes.forEach(type => {
                        select.innerHTML += `<option value="${type.id}">${escapeHtml(type.name)}</option>`;
                    });
                }
            });
        }

        populatePersonSelects() {
            const addSelect = document.getElementById('addWebsiteAdmin');
            const editSelect = document.getElementById('editWebsiteAdmin');

            [addSelect, editSelect].forEach(select => {
                if (select) {
                    select.innerHTML = '<option value="">Select Website Admin</option>';
                    this.persons.forEach(person => {
                        const displayName = `${person.first_name || ''} ${person.last_name || ''}`.trim() || person.email || `Person #${person.id}`;
                        select.innerHTML += `<option value="${person.id}">${escapeHtml(displayName)}</option>`;
                    });
                }
            });
        }

        updateStatistics() {
            const stats = this.calculateStatistics();

            document.getElementById('totalOrganizations').textContent = stats.total;
            document.getElementById('activeOrganizations').textContent = stats.active;
            document.getElementById('verifiedOrganizations').textContent = stats.verified;
            document.getElementById('pendingOrganizations').textContent = stats.pending;
            document.getElementById('suspendedOrganizations').textContent = stats.suspended;
            document.getElementById('uniqueSubdomains').textContent = stats.uniqueSubdomains;
        }

        calculateStatistics() {
            const stats = {
                total: this.organizations.length,
                active: 0,
                verified: 0,
                pending: 0,
                suspended: 0,
                uniqueSubdomains: new Set()
            };

            this.organizations.forEach(org => {
                if (org.status === 'active') stats.active++;
                if (org.verification_status === 'verified') stats.verified++;
                if (org.verification_status === 'pending') stats.pending++;
                if (org.status === 'suspended') stats.suspended++;
                if (org.subdomain) stats.uniqueSubdomains.add(org.subdomain);
            });

            stats.uniqueSubdomains = stats.uniqueSubdomains.size;

            return stats;
        }

        renderOrganizations() {
            const filteredOrganizations = this.getFilteredOrganizations();
            const paginatedOrganizations = this.getPaginatedData(filteredOrganizations);

            this.renderDesktopTable(paginatedOrganizations);
            this.renderMobileCards(paginatedOrganizations);
            this.renderPagination(filteredOrganizations.length);
        }

        getFilteredOrganizations() {
            return this.organizations.filter(org => {
                const searchMatch = !this.currentFilters.search ||
                    Object.values(org).some(value =>
                        value && value.toString().toLowerCase().includes(this.currentFilters.search.toLowerCase())
                    );

                const statusMatch = !this.currentFilters.status || org.status === this.currentFilters.status;
                const verificationMatch = !this.currentFilters.verification_status || org.verification_status === this.currentFilters.verification_status;
                const industryMatch = !this.currentFilters.industry_category || org.industry_category_id == this.currentFilters.industry_category;
                const legalTypeMatch = !this.currentFilters.legal_type || org.organization_legal_type_id == this.currentFilters.legal_type;
                const businessModelMatch = !this.currentFilters.business_model || org.business_model === this.currentFilters.business_model;

                return searchMatch && statusMatch && verificationMatch && industryMatch && legalTypeMatch && businessModelMatch;
            });
        }

        getPaginatedData(data) {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = startIndex + this.itemsPerPage;
            return data.slice(startIndex, endIndex);
        }

        renderDesktopTable(organizations) {
            const tableBody = document.getElementById('organizationsTableBody');
            if (!tableBody) return;

            if (organizations.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-building fa-2x mb-2"></i>
                            <p>No organizations found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = organizations.map(org => {
                const industryName = this.getIndustryCategoryName(org.industry_category_id);
                const legalTypeName = this.getLegalTypeName(org.organization_legal_type_id);
                const websiteAdminName = this.getPersonName(org.website_admin_id);
                const verificationBadge = this.getVerificationBadge(org.verification_status);
                const statusBadge = this.getStatusBadge(org.status);

                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-bold">${escapeHtml(org.name || '')}</div>
                                    <div class="small text-muted">${escapeHtml(org.subdomain || '')}.v4l.app</div>
                                </div>
                            </div>
                        </td>
                        <td>${escapeHtml(industryName)}</td>
                        <td>${escapeHtml(legalTypeName)}</td>
                        <td>${statusBadge}</td>
                        <td>${verificationBadge}</td>
                        <td>
                            <span class="badge bg-info">${escapeHtml(org.business_model || 'N/A')}</span>
                        </td>
                        <td>${escapeHtml(websiteAdminName)}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Organization actions">
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        onclick="window.organizationManager.viewOrganization(${org.id})"
                                        title="View Details">
                                    👁️
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="window.organizationManager.editOrganization(${org.id})"
                                        title="Edit">
                                    ✏️
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="window.organizationManager.deleteOrganization(${org.id})"
                                        title="Delete">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        renderMobileCards(organizations) {
            const mobileContainer = document.getElementById('organizationsMobile');
            if (!mobileContainer) return;

            if (organizations.length === 0) {
                mobileContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-building fa-2x mb-2"></i>
                        <p>No organizations found</p>
                    </div>
                `;
                return;
            }

            mobileContainer.innerHTML = organizations.map(org => {
                const industryName = this.getIndustryCategoryName(org.industry_category_id);
                const legalTypeName = this.getLegalTypeName(org.organization_legal_type_id);
                const websiteAdminName = this.getPersonName(org.website_admin_id);
                const verificationBadge = this.getVerificationBadge(org.verification_status);
                const statusBadge = this.getStatusBadge(org.status);

                return `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">${escapeHtml(org.name || '')}</h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="window.organizationManager.viewOrganization(${org.id})">👁️ View Details</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="window.organizationManager.editOrganization(${org.id})">✏️ Edit</a></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="window.organizationManager.deleteOrganization(${org.id})">🗑️ Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="small text-muted mb-2">
                                <div><strong>Subdomain:</strong> ${escapeHtml(org.subdomain || '')}.v4l.app</div>
                                <div><strong>Industry:</strong> ${escapeHtml(industryName)}</div>
                                <div><strong>Legal Type:</strong> ${escapeHtml(legalTypeName)}</div>
                                <div><strong>Admin:</strong> ${escapeHtml(websiteAdminName)}</div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                ${statusBadge}
                                ${verificationBadge}
                                <span class="badge bg-info">${escapeHtml(org.business_model || 'N/A')}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        getIndustryCategoryName(id) {
            const category = this.industryCategories.find(c => c.id == id);
            return category ? category.name : 'Unknown';
        }

        getLegalTypeName(id) {
            const type = this.legalTypes.find(t => t.id == id);
            return type ? type.name : 'Unknown';
        }

        getPersonName(id) {
            const person = this.persons.find(p => p.id == id);
            if (!person) return 'Unknown';
            return `${person.first_name || ''} ${person.last_name || ''}`.trim() || person.email || `Person #${person.id}`;
        }

        getVerificationBadge(status) {
            const badges = {
                pending: '<span class="badge bg-warning">⏳ Pending</span>',
                verified: '<span class="badge bg-success">✅ Verified</span>',
                rejected: '<span class="badge bg-danger">❌ Rejected</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">❓ Unknown</span>';
        }

        getStatusBadge(status) {
            const badges = {
                active: '<span class="badge bg-success">🟢 Active</span>',
                inactive: '<span class="badge bg-secondary">⚫ Inactive</span>',
                suspended: '<span class="badge bg-danger">🔴 Suspended</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">❓ Unknown</span>';
        }

        renderPagination(totalItems) {
            const pagination = document.getElementById('organizationsPagination');
            if (!pagination) return;

            const totalPages = Math.ceil(totalItems / this.itemsPerPage);

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = `
                <li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="window.organizationManager.goToPage(${this.currentPage - 1})">Previous</a>
                </li>
            `;

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= this.currentPage - 1 && i <= this.currentPage + 1)) {
                    paginationHTML += `
                        <li class="page-item ${i === this.currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="window.organizationManager.goToPage(${i})">${i}</a>
                        </li>
                    `;
                } else if (i === this.currentPage - 2 || i === this.currentPage + 2) {
                    paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            paginationHTML += `
                <li class="page-item ${this.currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="window.organizationManager.goToPage(${this.currentPage + 1})">Next</a>
                </li>
            `;

            pagination.innerHTML = paginationHTML;
        }

        goToPage(page) {
            const totalPages = Math.ceil(this.getFilteredOrganizations().length / this.itemsPerPage);
            if (page >= 1 && page <= totalPages) {
                this.currentPage = page;
                this.renderOrganizations();
            }
        }

        handleSearch(event) {
            this.currentFilters.search = event.target.value;
            this.currentPage = 1;
            this.renderOrganizations();
        }

        handleFilter(event) {
            const filterType = event.target.id.replace('Filter', '').replace('Filter', '');

            if (event.target.id === 'verificationFilter') {
                this.currentFilters.verification_status = event.target.value;
            } else if (event.target.id === 'industryFilter') {
                this.currentFilters.industry_category = event.target.value;
            } else if (event.target.id === 'legalTypeFilter') {
                this.currentFilters.legal_type = event.target.value;
            } else if (event.target.id === 'businessModelFilter') {
                this.currentFilters.business_model = event.target.value;
            } else {
                this.currentFilters[filterType] = event.target.value;
            }

            this.currentPage = 1;
            this.renderOrganizations();
        }

        clearFilters() {
            this.currentFilters = {
                search: '',
                status: '',
                verification_status: '',
                industry_category: '',
                legal_type: '',
                business_model: ''
            };

            document.getElementById('searchOrganizations').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('verificationFilter').value = '';
            document.getElementById('industryFilter').value = '';
            document.getElementById('legalTypeFilter').value = '';
            document.getElementById('businessModelFilter').value = '';

            this.currentPage = 1;
            this.renderOrganizations();
        }

        showNewOrganizationModal() {
            document.getElementById('addOrganizationForm').reset();
            document.getElementById('addSubdomainFeedback').textContent = '';

            const modal = new bootstrap.Modal(document.getElementById('addOrganizationModal'));
            modal.show();
        }

        async validateSubdomain(input, context) {
            const subdomain = input.value.toLowerCase();
            const feedbackElement = document.getElementById(`${context}SubdomainFeedback`);

            if (!subdomain) {
                feedbackElement.textContent = '';
                input.classList.remove('is-valid', 'is-invalid');
                return;
            }

            // Basic validation
            if (subdomain.length < 3 || subdomain.length > 30) {
                feedbackElement.textContent = 'Subdomain must be between 3 and 30 characters';
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                return;
            }

            if (!/^[a-z0-9-]+$/.test(subdomain)) {
                feedbackElement.textContent = 'Subdomain can only contain lowercase letters, numbers, and hyphens';
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                return;
            }

            if (subdomain.startsWith('-') || subdomain.endsWith('-')) {
                feedbackElement.textContent = 'Subdomain cannot start or end with a hyphen';
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                return;
            }

            // Check availability
            try {
                const response = await fetch(`/api/entities/Organization/validateSubdomain?subdomain=${encodeURIComponent(subdomain)}`);
                const result = await response.json();

                if (result.available) {
                    feedbackElement.textContent = 'Subdomain is available';
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    feedbackElement.textContent = result.message || 'Subdomain is not available';
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            } catch (error) {
                feedbackElement.textContent = 'Error checking subdomain availability';
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        }

        async handleAddOrganization(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const organizationData = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/api/entities/Organization', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(organizationData)
                });

                const result = await response.json();

                if (result.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addOrganizationModal'));
                    modal.hide();
                    await this.loadOrganizations();
                    showToast('Organization added successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to add organization');
                }
            } catch (error) {
                handleApiError(error, 'adding organization');
            }
        }

        viewOrganization(id) {
            const org = this.organizations.find(o => o.id == id);
            if (!org) return;

            const industryName = this.getIndustryCategoryName(org.industry_category_id);
            const legalTypeName = this.getLegalTypeName(org.organization_legal_type_id);
            const websiteAdminName = this.getPersonName(org.website_admin_id);

            document.getElementById('viewName').textContent = org.name || '';
            document.getElementById('viewSubdomain').textContent = (org.subdomain || '') + '.v4l.app';
            document.getElementById('viewDescription').textContent = org.description || 'No description';
            document.getElementById('viewBusinessModel').textContent = org.business_model || 'Not specified';
            document.getElementById('viewIndustryCategory').textContent = industryName;
            document.getElementById('viewLegalType').textContent = legalTypeName;
            document.getElementById('viewWebsiteAdmin').textContent = websiteAdminName;
            document.getElementById('viewStatus').innerHTML = this.getStatusBadge(org.status);
            document.getElementById('viewVerificationStatus').innerHTML = this.getVerificationBadge(org.verification_status);
            document.getElementById('viewCreatedAt').textContent = new Date(org.created_at).toLocaleString();
            document.getElementById('viewUpdatedAt').textContent = new Date(org.updated_at).toLocaleString();

            const modal = new bootstrap.Modal(document.getElementById('viewOrganizationModal'));
            modal.show();
        }

        editOrganization(id) {
            const org = this.organizations.find(o => o.id == id);
            if (!org) return;

            document.getElementById('editOrganizationId').value = org.id;
            document.getElementById('editName').value = org.name || '';
            document.getElementById('editSubdomain').value = org.subdomain || '';
            document.getElementById('editDescription').value = org.description || '';
            document.getElementById('editBusinessModel').value = org.business_model || '';
            document.getElementById('editIndustryCategory').value = org.industry_category_id || '';
            document.getElementById('editLegalType').value = org.organization_legal_type_id || '';
            document.getElementById('editWebsiteAdmin').value = org.website_admin_id || '';
            document.getElementById('editStatus').value = org.status || '';
            document.getElementById('editVerificationStatus').value = org.verification_status || '';

            document.getElementById('editSubdomainFeedback').textContent = '';

            const modal = new bootstrap.Modal(document.getElementById('editOrganizationModal'));
            modal.show();
        }

        async handleEditOrganization(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const organizationData = Object.fromEntries(formData.entries());
            const id = organizationData.id;
            delete organizationData.id;

            try {
                const response = await fetch(`/api/entities/Organization/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(organizationData)
                });

                const result = await response.json();

                if (result.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editOrganizationModal'));
                    modal.hide();
                    await this.loadOrganizations();
                    showToast('Organization updated successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to update organization');
                }
            } catch (error) {
                handleApiError(error, 'updating organization');
            }
        }

        async deleteOrganization(id) {
            const org = this.organizations.find(o => o.id == id);
            if (!org) return;

            if (!confirm(`Are you sure you want to delete ${org.name || 'this organization'}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/entities/Organization/${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    await this.loadOrganizations();
                    showToast('Organization deleted successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to delete organization');
                }
            } catch (error) {
                handleApiError(error, 'deleting organization');
            }
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        window.organizationManager = new OrganizationManager();

        // Override the global refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                if (window.organizationManager && window.organizationManager.loadOrganizations) {
                    window.organizationManager.loadOrganizations();
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

<!-- Common Utility Functions -->
<script>
    // Utility function to escape HTML characters
    function escapeHtml(text) {
        if (typeof text !== 'string') return text;
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Set loading state for buttons
    function setLoadingState(button, isLoading) {
        if (!button) return;

        const loadingIndicator = button.querySelector('.loading-spinner');
        const refreshText = button.querySelector('#refreshText');

        if (isLoading) {
            button.disabled = true;
            if (loadingIndicator) loadingIndicator.style.display = 'inline-block';
            if (refreshText) refreshText.textContent = 'Loading...';
        } else {
            button.disabled = false;
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (refreshText) refreshText.textContent = 'Refresh';
        }
    }

    // Show toast notifications
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '1090';
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toastId = 'toast-' + Date.now();
        const iconMap = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        const colorMap = {
            success: 'text-bg-success',
            error: 'text-bg-danger',
            warning: 'text-bg-warning',
            info: 'text-bg-primary'
        };

        const toastHtml = `
            <div id="${toastId}" class="toast ${colorMap[type] || colorMap.info}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <span class="me-2">${iconMap[type] || iconMap.info}</span>
                    <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${escapeHtml(message)}
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

        // Initialize and show toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: type === 'error' ? 8000 : 4000
        });

        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Handle API errors consistently
    function handleApiError(error, operation = 'operation') {
        console.error(`Error during ${operation}:`, error);

        let message = `Failed to perform ${operation}`;
        if (error.message) {
            message += `: ${error.message}`;
        }

        showToast(message, 'error');
    }

    // Initialize tooltips if Bootstrap is available
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>