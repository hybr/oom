# Geocoding Setup Guide

This guide explains how to set up and use the geocoding feature for postal addresses.

## Overview

The geocoding feature automatically fills latitude and longitude fields when creating or editing postal addresses. It uses the Google Maps Geocoding API to convert street addresses into geographic coordinates.

## Features

- Manual geocoding with "Get Coordinates from Address" button
- Reads address from: first_street, second_street, area, and postal_code fields
- Auto-fills latitude and longitude fields
- User-friendly error messages
- Loading indicators during API calls
- Fields remain editable (manual override possible)

## Setup Instructions

### 1. Get Google Maps API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **Geocoding API**:
   - Navigate to "APIs & Services" > "Library"
   - Search for "Geocoding API"
   - Click "Enable"
4. Create an API key:
   - Navigate to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "API Key"
   - Copy your API key
5. (Optional) Restrict your API key:
   - Click on your API key
   - Under "Application restrictions", select "HTTP referrers"
   - Add your domain (e.g., `localhost:8000/*` for development)
   - Under "API restrictions", select "Restrict key"
   - Choose "Geocoding API"

### 2. Configure the Application

Add your API key to the `.env` file:

```env
# Geocoding (for postal addresses)
GEOCODING_PROVIDER=google
GOOGLE_MAPS_API_KEY=your_api_key_here
GEOCODING_AUTO_FILL=false
```

Replace `your_api_key_here` with the API key you obtained from Google Cloud Console.

### 3. Optional: Enable Auto-Fill

If you want coordinates to be fetched automatically when address fields are filled (not recommended for production due to API usage):

```env
GEOCODING_AUTO_FILL=true
```

**Note**: Auto-fill is disabled by default to avoid unnecessary API calls. The manual button approach is recommended.

## Usage

### Creating a Postal Address

1. Navigate to http://localhost:8000/entities/postal_address/create
2. Fill in the address fields:
   - First Street (required)
   - Second Street (optional)
   - Area / Locality (required)
   - Postal Code / PIN (required)
3. Click the "Get Coordinates from Address" button
4. The latitude and longitude fields will be automatically filled
5. You can manually adjust the coordinates if needed
6. Complete the rest of the form and submit

### Editing a Postal Address

1. Navigate to an existing postal address
2. Click "Edit"
3. Modify address fields if needed
4. Click "Get Coordinates from Address" to update coordinates
5. Save your changes

## API Usage & Costs

- Google Maps Geocoding API has a generous free tier
- Free tier: 28,500 requests per month (approx. 950 per day)
- After free tier: $5.00 per 1,000 requests
- Monitor usage in Google Cloud Console

**Best Practice**: Use the manual button approach to minimize API calls.

## Troubleshooting

### "Google Maps API key not configured"

- Ensure you've added `GOOGLE_MAPS_API_KEY` to your `.env` file
- Restart your web server after modifying `.env`

### "Geocoding request denied"

- Check that the Geocoding API is enabled in Google Cloud Console
- Verify your API key is correct
- Check API key restrictions aren't blocking the request

### "No coordinates found for this address"

- Verify the address is complete and accurate
- Try adding more details to the address fields
- Check the postal code is correct

### "Geocoding quota exceeded"

- You've exceeded the free tier limit
- Wait for the quota to reset (daily/monthly)
- Consider upgrading your Google Cloud plan

## Technical Details

### Files Modified/Created

1. **config/app.php** - Added geocoding configuration
2. **.env.example** - Added geocoding environment variables
3. **public/assets/js/geocoding.js** - Geocoding JavaScript functionality
4. **public/pages/entities/create.php** - Added API key meta tag for postal_address
5. **public/pages/entities/edit.php** - Added API key meta tag for postal_address
6. **includes/footer.php** - Load geocoding.js script
7. **includes/header.php** - Updated CSP to allow Google Maps API

### How It Works

1. When a postal_address form loads, the geocoding.js script initializes
2. The script reads the Google Maps API key from a meta tag
3. A "Get Coordinates" button is added to the form
4. When clicked, the script:
   - Collects values from address fields
   - Sends request to Google Maps Geocoding API
   - Parses the response
   - Fills latitude and longitude fields

### Security

- API key is served via meta tag (only on postal_address forms)
- Content Security Policy updated to allow maps.googleapis.com
- API calls are made client-side with proper error handling
- Recommend restricting API key to your domain in production

## Future Enhancements

Possible improvements:

1. Support for alternative geocoding providers (Nominatim, HERE, etc.)
2. Reverse geocoding (coordinates to address)
3. Address validation
4. Map preview showing the location
5. Batch geocoding for multiple addresses

## Support

For issues or questions:
- Check the browser console for error messages
- Verify your Google Maps API key is valid
- Ensure all environment variables are set correctly
- Check Google Cloud Console for API usage and errors
