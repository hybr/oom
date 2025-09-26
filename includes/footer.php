    </main>

    <!-- Toast Container for notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light" role="contentinfo">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted">
                        © <?php echo date('Y'); ?> V4L - Vocal 4 Local
                        <span class="d-none d-md-inline">- Community Platform</span>
                    </span>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        <span id="lastUpdated">Last updated: <time id="updateTime"><?php echo date('H:i:s'); ?></time></span>
                        <span class="d-none d-lg-inline ms-2">
                            | <a href="#" class="text-decoration-none" onclick="showSystemInfo()">System Info</a>
                        </span>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- System Info Modal -->
    <div class="modal fade" id="systemInfoModal" tabindex="-1" aria-labelledby="systemInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="systemInfoModalLabel">System Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-4">System:</dt>
                        <dd class="col-sm-8">V4L - Vocal 4 Local v1.0</dd>

                        <dt class="col-sm-4">Database:</dt>
                        <dd class="col-sm-8">SQLite</dd>

                        <dt class="col-sm-4">Framework:</dt>
                        <dd class="col-sm-8">Bootstrap 5.3.2</dd>

                        <dt class="col-sm-4">Last Update:</dt>
                        <dd class="col-sm-8" id="systemLastUpdate"><?php echo date('Y-m-d H:i:s'); ?></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <!-- Common JavaScript -->
    <script>
        // Common theme management
        function initializeTheme() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const savedTheme = localStorage.getItem('theme') || 'light';

            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            if (themeIcon) {
                themeIcon.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    if (themeIcon) {
                        themeIcon.textContent = newTheme === 'dark' ? '☀️' : '🌙';
                    }
                });
            }
        }

        // Common notification system
        function showToast(message, type = 'info', duration = 5000) {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;

            const toastId = 'toast-' + Date.now();
            const toastHTML = `
                <div class="toast" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <span class="toast-icon me-2">${getToastIcon(type)}</span>
                        <strong class="me-auto">${getToastTitle(type)}</strong>
                        <small class="text-muted">${new Date().toLocaleTimeString()}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: duration });
            toast.show();

            // Clean up after toast is hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        function getToastIcon(type) {
            const icons = {
                'success': '✅',
                'error': '❌',
                'warning': '⚠️',
                'info': 'ℹ️'
            };
            return icons[type] || icons['info'];
        }

        function getToastTitle(type) {
            const titles = {
                'success': 'Success',
                'error': 'Error',
                'warning': 'Warning',
                'info': 'Information'
            };
            return titles[type] || titles['info'];
        }

        // System info modal
        function showSystemInfo() {
            const modal = new bootstrap.Modal(document.getElementById('systemInfoModal'));
            modal.show();
        }

        // Update time display
        function updateTime() {
            const timeElement = document.getElementById('updateTime');
            if (timeElement) {
                timeElement.textContent = new Date().toLocaleTimeString();
                timeElement.setAttribute('datetime', new Date().toISOString());
            }
        }

        // Common error handling for API calls
        function handleApiError(error, operation = 'operation') {
            console.error(`Error during ${operation}:`, error);
            showToast(`Failed to complete ${operation}. Please try again.`, 'error');
        }

        // Loading state management
        function setLoadingState(button, isLoading) {
            if (!button) return;

            const spinner = button.querySelector('.loading-spinner');
            const text = button.querySelector('#refreshText') || button;

            if (isLoading) {
                button.disabled = true;
                if (spinner) spinner.style.display = 'inline-block';
                if (text) text.textContent = 'Loading...';
            } else {
                button.disabled = false;
                if (spinner) spinner.style.display = 'none';
                if (text) text.textContent = 'Refresh';
            }
        }

        // Initialize common functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeTheme();
            updateTime();

            // Update time every minute
            setInterval(updateTime, 60000);

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + T for theme toggle
                if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                    e.preventDefault();
                    document.getElementById('themeToggle')?.click();
                }

                // Ctrl/Cmd + R for refresh (prevent default and use custom refresh)
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    document.getElementById('refreshBtn')?.click();
                }
            });
        });
    </script>

    <!-- Page-specific JavaScript will be included here -->
</body>
</html>