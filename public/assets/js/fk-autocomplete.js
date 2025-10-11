/**
 * Foreign Key Autocomplete
 * Handles autocomplete functionality for FK fields with 50+ options
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all FK autocomplete fields
    const autocompleteFields = document.querySelectorAll('.fk-autocomplete');

    autocompleteFields.forEach(field => {
        const fkField = field.dataset.fkField;
        const targetEntity = field.dataset.targetEntity;
        const hiddenInput = document.getElementById(fkField);
        const suggestionsContainer = document.getElementById(fkField + '_suggestions');

        let debounceTimer;

        // Handle input event
        field.addEventListener('input', function() {
            const query = this.value.trim();

            // Clear previous timer
            clearTimeout(debounceTimer);

            // If query is empty, hide suggestions
            if (query.length === 0) {
                suggestionsContainer.style.display = 'none';
                hiddenInput.value = '';
                return;
            }

            // Debounce API call (wait 300ms after user stops typing)
            debounceTimer = setTimeout(() => {
                fetchSuggestions(query, targetEntity, fkField, suggestionsContainer, hiddenInput, field);
            }, 300);
        });

        // Handle focus - show suggestions if there's a query
        field.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                const existingSuggestions = suggestionsContainer.querySelectorAll('li');
                if (existingSuggestions.length > 0) {
                    suggestionsContainer.style.display = 'block';
                }
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!field.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                suggestionsContainer.style.display = 'none';
            }
        });
    });
});

/**
 * Fetch suggestions from API
 */
function fetchSuggestions(query, entity, fkField, container, hiddenInput, displayInput) {
    const url = `/api/fk-autocomplete.php?entity=${encodeURIComponent(entity)}&query=${encodeURIComponent(query)}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch suggestions');
            }
            return response.json();
        })
        .then(data => {
            displaySuggestions(data.results, container, hiddenInput, displayInput);
        })
        .catch(error => {
            console.error('Autocomplete error:', error);
            container.innerHTML = '<li class="list-group-item text-danger">Error loading suggestions</li>';
            container.style.display = 'block';
        });
}

/**
 * Display suggestions in dropdown
 */
function displaySuggestions(results, container, hiddenInput, displayInput) {
    container.innerHTML = '';

    if (results.length === 0) {
        container.innerHTML = '<li class="list-group-item text-muted">No matches found</li>';
        container.style.display = 'block';
        return;
    }

    results.forEach(result => {
        const li = document.createElement('li');
        li.className = 'list-group-item list-group-item-action';
        li.style.cursor = 'pointer';
        li.textContent = result.label;

        // Handle click on suggestion
        li.addEventListener('click', function() {
            displayInput.value = result.label;
            hiddenInput.value = result.id;
            container.style.display = 'none';
        });

        // Handle hover
        li.addEventListener('mouseenter', function() {
            this.classList.add('active');
        });

        li.addEventListener('mouseleave', function() {
            this.classList.remove('active');
        });

        container.appendChild(li);
    });

    container.style.display = 'block';
}
