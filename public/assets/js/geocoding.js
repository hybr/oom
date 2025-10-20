/**
 * Geocoding functionality for postal addresses
 * Uses Google Maps Geocoding API to autofill latitude and longitude
 */

(function() {
    'use strict';

    // Configuration - will be populated from server
    let geocodingConfig = {
        provider: 'google',
        apiKey: '',
        autoFill: false
    };

    // Track if geocoding is in progress
    let isGeocoding = false;

    /**
     * Initialize geocoding on page load
     */
    function init() {
        // Get API key from meta tag (will be added by PHP)
        const apiKeyMeta = document.querySelector('meta[name="google-maps-api-key"]');
        if (apiKeyMeta) {
            geocodingConfig.apiKey = apiKeyMeta.content;
        }

        // Check if this is a postal_address form
        const isPostalAddressForm = window.location.pathname.includes('/postal_address/');

        if (!isPostalAddressForm) {
            return; // Not a postal address form, exit
        }

        // Find latitude and longitude fields
        const latField = document.getElementById('latitude');
        const lonField = document.getElementById('longitude');

        if (!latField || !lonField) {
            return; // Fields not found
        }

        // Add geocoding button if not already present
        addGeocodingButton();

        // Set up auto-fill if enabled (optional)
        if (geocodingConfig.autoFill) {
            setupAutoFill();
        }
    }

    /**
     * Add "Get Coordinates" button next to latitude field
     */
    function addGeocodingButton() {
        const latField = document.getElementById('latitude');
        if (!latField) return;

        // Check if button already exists
        if (document.getElementById('geocoding-btn')) {
            return;
        }

        const wrapper = latField.closest('.col-md-6, .mb-3');
        if (!wrapper) return;

        // Create button
        const button = document.createElement('button');
        button.type = 'button';
        button.id = 'geocoding-btn';
        button.className = 'btn btn-outline-primary btn-sm mt-2';
        button.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Get Coordinates from Address';
        button.onclick = handleGeocodeClick;

        // Insert button after the latitude input
        latField.parentNode.insertBefore(button, latField.nextSibling);

        // Add loading spinner (hidden by default)
        const spinner = document.createElement('div');
        spinner.id = 'geocoding-spinner';
        spinner.className = 'd-none ms-2';
        spinner.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Fetching...';
        button.parentNode.insertBefore(spinner, button.nextSibling);
    }

    /**
     * Handle geocode button click
     */
    async function handleGeocodeClick() {
        if (isGeocoding) {
            return; // Already in progress
        }

        // Validate API key
        if (!geocodingConfig.apiKey) {
            showError('Google Maps API key not configured. Please contact administrator.');
            return;
        }

        // Show loading state early
        isGeocoding = true;
        showLoading(true);

        try {
            // Collect address fields (now async to fetch city details)
            const address = await collectAddressFields();

            if (!address.trim()) {
                showError('Please fill in address fields before getting coordinates.');
                isGeocoding = false;
                showLoading(false);
                return;
            }

            // Perform geocoding
            geocodeAddress(address);
        } catch (error) {
            showError('Error collecting address information: ' + error.message);
            isGeocoding = false;
            showLoading(false);
        }
    }

    /**
     * Collect address fields and build address string
     * Now fetches city, district, state, and country names from the database
     */
    async function collectAddressFields() {
        const fields = [
            'first_street',
            'second_street',
            'area',
            'postal_code'
        ];

        const addressParts = [];

        // Collect basic address fields
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && field.value.trim()) {
                addressParts.push(field.value.trim());
            }
        });

        // Get city_id field
        const cityIdField = document.getElementById('city_id');

        if (cityIdField && cityIdField.value) {
            try {
                // Fetch city details with district, state, and country names
                const cityDetails = await fetchCityDetails(cityIdField.value);

                if (cityDetails) {
                    // Add city, district, state, and country to address
                    if (cityDetails.city_name) {
                        addressParts.push(cityDetails.city_name);
                    }
                    if (cityDetails.district_name) {
                        addressParts.push(cityDetails.district_name);
                    }
                    if (cityDetails.state_name) {
                        addressParts.push(cityDetails.state_name);
                    }
                    if (cityDetails.country_name) {
                        addressParts.push(cityDetails.country_name);
                    }
                }
            } catch (error) {
                console.error('Error fetching city details:', error);
                // Continue with just the basic address fields
            }
        }

        return addressParts.join(', ');
    }

    /**
     * Fetch city details with joined district, state, and country names
     */
    async function fetchCityDetails(cityId) {
        if (!cityId) {
            return null;
        }

        try {
            const response = await fetch(`/api/get-city-details.php?city_id=${encodeURIComponent(cityId)}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success && result.data) {
                return result.data;
            }

            return null;
        } catch (error) {
            console.error('Failed to fetch city details:', error);
            throw error;
        }
    }

    /**
     * Perform geocoding using Google Maps Geocoding API
     */
    function geocodeAddress(address) {
        // Note: isGeocoding and showLoading are already set in handleGeocodeClick
        console.log('Geocoding address:', address);

        const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${geocodingConfig.apiKey}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Geocoding response:', data);
                handleGeocodeResponse(data);
            })
            .catch(error => {
                console.error('Geocoding error:', error);
                showError('Failed to connect to geocoding service: ' + error.message);
            })
            .finally(() => {
                isGeocoding = false;
                showLoading(false);
            });
    }

    /**
     * Handle geocoding API response
     */
    function handleGeocodeResponse(data) {
        if (data.status === 'OK' && data.results && data.results.length > 0) {
            const location = data.results[0].geometry.location;

            // Fill in the latitude and longitude fields
            const latField = document.getElementById('latitude');
            const lonField = document.getElementById('longitude');

            if (latField && lonField) {
                latField.value = location.lat;
                lonField.value = location.lng;

                // Highlight the fields briefly to show they've been updated
                highlightField(latField);
                highlightField(lonField);

                showSuccess('Coordinates retrieved successfully!');
            }
        } else if (data.status === 'ZERO_RESULTS') {
            showError('No coordinates found for this address. Please check the address and try again.');
        } else if (data.status === 'REQUEST_DENIED') {
            showError('Geocoding request denied. Please check API key configuration.');
        } else if (data.status === 'OVER_QUERY_LIMIT') {
            showError('Geocoding quota exceeded. Please try again later.');
        } else {
            showError('Unable to retrieve coordinates. Status: ' + data.status);
        }
    }

    /**
     * Show loading state
     */
    function showLoading(show) {
        const button = document.getElementById('geocoding-btn');
        const spinner = document.getElementById('geocoding-spinner');

        if (button && spinner) {
            if (show) {
                button.disabled = true;
                spinner.classList.remove('d-none');
            } else {
                button.disabled = false;
                spinner.classList.add('d-none');
            }
        }
    }

    /**
     * Highlight field briefly
     */
    function highlightField(field) {
        field.classList.add('border-success');
        setTimeout(() => {
            field.classList.remove('border-success');
        }, 2000);
    }

    /**
     * Show error message
     */
    function showError(message) {
        if (window.V4L && window.V4L.showToast) {
            window.V4L.showToast(message, 'danger');
        } else {
            alert(message);
        }
    }

    /**
     * Show success message
     */
    function showSuccess(message) {
        if (window.V4L && window.V4L.showToast) {
            window.V4L.showToast(message, 'success');
        } else {
            alert(message);
        }
    }

    /**
     * Set up auto-fill on address field changes (optional feature)
     */
    function setupAutoFill() {
        const fields = [
            'first_street',
            'second_street',
            'area',
            'postal_code'
        ];

        // Debounced geocode function
        let autoFillTimeout;

        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => {
                    clearTimeout(autoFillTimeout);
                    autoFillTimeout = setTimeout(() => {
                        const address = collectAddressFields();
                        if (address.trim()) {
                            handleGeocodeClick();
                        }
                    }, 500);
                });
            }
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
