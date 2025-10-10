/**
 * V4L Application JavaScript
 * Core client-side functionality
 */

// Theme Management
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('theme-icon');
    if (icon) {
        icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
    }
}

// Initialize theme from localStorage
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(savedTheme);
});

// Delete Record Confirmation
function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        // Get current path
        const path = window.location.pathname;
        const parts = path.split('/');
        const entityCode = parts[2];

        // Send DELETE request
        fetch(`/entities/${entityCode}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: id,
                csrf_token: getCSRFToken()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Record deleted successfully', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast('Failed to delete record: ' + data.error, 'danger');
            }
        })
        .catch(error => {
            showToast('Error: ' + error.message, 'danger');
        });
    }
}

// Get CSRF Token
function getCSRFToken() {
    const input = document.querySelector('input[name="csrf_token"]');
    return input ? input.value : '';
}

// Show Toast Notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();

    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${escapeHtml(message)}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    toastContainer.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();

    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Create toast container if it doesn't exist
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// WebSocket Connection
let ws = null;
let wsReconnectAttempts = 0;
const wsMaxReconnectAttempts = 5;

function connectWebSocket() {
    if (ws && ws.readyState === WebSocket.OPEN) {
        return; // Already connected
    }

    const wsHost = window.location.hostname;
    const wsPort = 8080; // From config

    try {
        ws = new WebSocket(`ws://${wsHost}:${wsPort}`);

        ws.onopen = function() {
            console.log('WebSocket connected');
            wsReconnectAttempts = 0;

            // Authenticate
            ws.send(JSON.stringify({
                type: 'auth',
                token: getSessionToken()
            }));
        };

        ws.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                handleWebSocketMessage(data);
            } catch (e) {
                console.error('WebSocket message parse error:', e);
            }
        };

        ws.onerror = function(error) {
            console.error('WebSocket error:', error);
        };

        ws.onclose = function() {
            console.log('WebSocket disconnected');

            // Attempt to reconnect with exponential backoff
            if (wsReconnectAttempts < wsMaxReconnectAttempts) {
                const delay = Math.min(1000 * Math.pow(2, wsReconnectAttempts), 30000);
                wsReconnectAttempts++;

                console.log(`Reconnecting in ${delay}ms... (attempt ${wsReconnectAttempts})`);
                setTimeout(connectWebSocket, delay);
            }
        };
    } catch (e) {
        console.error('Failed to create WebSocket:', e);
    }
}

// Handle WebSocket messages
function handleWebSocketMessage(data) {
    switch (data.type) {
        case 'entity_update':
            handleEntityUpdate(data);
            break;
        case 'notification':
            showToast(data.message, data.level || 'info');
            break;
        case 'presence':
            handlePresence(data);
            break;
        default:
            console.log('Unknown WebSocket message type:', data.type);
    }
}

// Handle entity updates
function handleEntityUpdate(data) {
    // Reload current view if watching this entity
    const path = window.location.pathname;
    if (path.includes(data.entity_code.toLowerCase())) {
        showToast('Data has been updated. Refreshing...', 'info');
        setTimeout(() => window.location.reload(), 1000);
    }
}

// Handle user presence
function handlePresence(data) {
    console.log('User presence update:', data);
    // Update UI to show active users
}

// Get session token for WebSocket auth
function getSessionToken() {
    // In a real implementation, this would come from the session
    return document.cookie.replace(/(?:(?:^|.*;\s*)session_token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
}

// Initialize WebSocket on page load (if authenticated)
document.addEventListener('DOMContentLoaded', function() {
    // Only connect if user is authenticated
    const userNav = document.querySelector('.navbar-nav .bi-person-circle');
    if (userNav) {
        connectWebSocket();
    }
});

// AJAX Form Submit Helper
function ajaxSubmit(formId, successCallback) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        fetch(form.action, {
            method: form.method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast(result.message || 'Success!', 'success');
                if (successCallback) {
                    successCallback(result);
                }
            } else {
                showToast(result.error || 'An error occurred', 'danger');
            }
        })
        .catch(error => {
            showToast('Error: ' + error.message, 'danger');
        });
    });
}

// Debounce function for search/filter
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Live search functionality
function setupLiveSearch(inputId, targetSelector) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const handleSearch = debounce(function() {
        const query = input.value.toLowerCase();
        const targets = document.querySelectorAll(targetSelector);

        targets.forEach(target => {
            const text = target.textContent.toLowerCase();
            target.style.display = text.includes(query) ? '' : 'none';
        });
    }, 300);

    input.addEventListener('input', handleSearch);
}

// Export functions for global use
window.V4L = {
    toggleTheme,
    deleteRecord,
    showToast,
    ajaxSubmit,
    setupLiveSearch,
    connectWebSocket
};
