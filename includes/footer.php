    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="bi bi-shop"></i> V4L</h5>
                    <p class="text-muted">Your Community, Your Marketplace</p>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white-50">About</a></li>
                        <li><a href="/contact" class="text-white-50">Contact</a></li>
                        <li><a href="/privacy" class="text-white-50">Privacy Policy</a></li>
                        <li><a href="/terms" class="text-white-50">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Connect</h6>
                    <div>
                        <a href="#" class="text-white-50 me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white-50 me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white-50 me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white-50"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <small>&copy; <?php echo date('Y'); ?> V4L (Vocal 4 Local). All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>

    <!-- FK Autocomplete -->
    <script src="/assets/js/fk-autocomplete.js"></script>

    <!-- Geocoding for Postal Address -->
    <script src="/assets/js/geocoding.js"></script>

    <!-- Form Validation -->
    <script>
        // Bootstrap form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })();

        // Organization Switcher
        <?php if (Auth::check()): ?>
        (function () {
            let userOrganizations = [];

            // Load user organizations when dropdown is shown
            document.getElementById('orgSelectorContainer')?.addEventListener('show.bs.dropdown', async function() {
                if (userOrganizations.length > 0) {
                    return; // Already loaded
                }

                try {
                    const response = await fetch('/api/organization/my-organizations.php');
                    const data = await response.json();

                    if (data.success) {
                        userOrganizations = data.organizations;
                        renderOrganizationList(userOrganizations, data.current_organization_id);
                    } else {
                        showError('Failed to load organizations');
                    }
                } catch (error) {
                    showError('Error loading organizations: ' + error.message);
                }
            });

            function renderOrganizationList(organizations, currentOrgId) {
                const menu = document.getElementById('orgDropdownMenu');

                if (organizations.length === 0) {
                    menu.innerHTML = `
                        <li><h6 class="dropdown-header">Switch Organization</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-3 py-2 text-muted">
                            <small>You are not affiliated with any organizations</small>
                        </li>
                    `;
                    return;
                }

                let html = '<li><h6 class="dropdown-header">Switch Organization</h6></li>';
                html += '<li><hr class="dropdown-divider"></li>';

                organizations.forEach(org => {
                    const isActive = org.id === currentOrgId;
                    const activeClass = isActive ? 'active' : '';
                    const checkIcon = isActive ? '<i class="bi bi-check-circle-fill text-success me-2"></i>' : '';

                    // Get permission badge based on highest level
                    // Following guide: @guides/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md
                    const badge = getPermissionBadge(org.highest_level);

                    // Get all membership types as pills
                    const membershipPills = org.memberships.map(m => {
                        return getMembershipPill(m.type, m.role, m.job_title);
                    }).join(' ');

                    html += `
                        <li>
                            <a class="dropdown-item ${activeClass}" href="#" onclick="switchOrganization('${org.id}', '${escapeHtml(org.name)}'); return false;">
                                ${checkIcon}
                                <div>
                                    <strong>${escapeHtml(org.name)}</strong>
                                    ${badge}
                                    <br>
                                    <small>${membershipPills}</small>
                                </div>
                            </a>
                        </li>
                    `;
                });

                menu.innerHTML = html;
            }

            function getPermissionBadge(level) {
                // Permission badges following the guide's hierarchy
                const badges = {
                    'MAIN_ADMIN': '<span class="badge bg-danger ms-1"><i class="bi bi-gem"></i> Owner</span>',
                    'SUPER_ADMIN': '<span class="badge bg-danger ms-1"><i class="bi bi-shield-fill-check"></i> Super Admin</span>',
                    'ADMIN': '<span class="badge bg-warning text-dark ms-1"><i class="bi bi-shield-check"></i> Admin</span>',
                    'MODERATOR': '<span class="badge bg-info ms-1"><i class="bi bi-eye"></i> Moderator</span>',
                    'EMPLOYEE': '<span class="badge bg-secondary ms-1"><i class="bi bi-person-badge"></i> Employee</span>'
                };
                return badges[level] || '';
            }

            function getMembershipPill(type, role, jobTitle) {
                // Small pills showing all membership types
                if (type === 'MAIN_ADMIN') {
                    return '<span class="badge rounded-pill bg-danger" style="font-size:0.7em;">Main Admin</span>';
                } else if (type === 'ORGANIZATION_ADMIN') {
                    const roleText = role || 'Admin';
                    return `<span class="badge rounded-pill bg-warning text-dark" style="font-size:0.7em;">${roleText}</span>`;
                } else if (type === 'EMPLOYEE') {
                    const title = jobTitle || 'Employee';
                    return `<span class="badge rounded-pill bg-secondary" style="font-size:0.7em;">${escapeHtml(title)}</span>`;
                }
                return '';
            }

            function showError(message) {
                const menu = document.getElementById('orgDropdownMenu');
                menu.innerHTML = `
                    <li><h6 class="dropdown-header">Error</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="px-3 py-2 text-danger">
                        <small>${escapeHtml(message)}</small>
                    </li>
                `;
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Make switchOrganization available globally
            window.switchOrganization = async function(orgId, orgName) {
                try {
                    const response = await fetch('/api/organization/switch-organization.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ organization_id: orgId })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update the displayed organization name
                        document.getElementById('currentOrgName').textContent = orgName;

                        // Reload the page to reflect changes
                        window.location.reload();
                    } else {
                        alert('Failed to switch organization: ' + data.error);
                    }
                } catch (error) {
                    alert('Error switching organization: ' + error.message);
                }
            };
        })();
        <?php endif; ?>
    </script>
</body>
</html>
