# Google Maps API Setup Guide

This guide will help you set up Google Maps Places API for address autocomplete functionality in the quote forms.

## Prerequisites

You need a Google Cloud Platform account to obtain a Google Maps API key.

## Step 1: Get a Google Maps API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **Places API**:
   - Navigate to "APIs & Services" > "Library"
   - Search for "Places API"
   - Click "Enable"
4. Create credentials:
   - Navigate to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "API Key"
   - Copy the generated API key

## Step 2: Configure API Key Restrictions (Recommended)

For security, it's recommended to restrict your API key:

1. In the Google Cloud Console, go to "APIs & Services" > "Credentials"
2. Click on your API key
3. Under "Application restrictions":
   - Select "HTTP referrers (websites)"
   - Add your website domains:
     - For local development: `http://localhost:8080/*`
     - For production: `https://yourdomain.com/*`, `https://www.yourdomain.com/*`
4. Under "API restrictions":
   - Select "Restrict key"
   - Choose "Places API" and "Maps JavaScript API"
5. Click "Save"

## Step 3: Add API Key to Your Environment

1. Copy the `.env.example` file to create `.env`:
   ```bash
   cp .env.example .env
   ```

2. Open the `.env` file and update the Google Maps API key:
   ```
   GOOGLE_MAPS_API_KEY=your_actual_google_maps_api_key_here
   ```

3. Save the file

## Step 4: Verify Setup

1. Start your application
2. Navigate to the quote form
3. Start typing an address in the "Complete Address" field
4. You should see Google address suggestions appear as you type

## Features

The address autocomplete includes:
- **Country Restriction**: Limited to Canadian addresses only (can be modified in the code)
- **Auto-fill City**: Automatically populates the Suburb/City field when an address is selected
- **Formatted Address**: Provides properly formatted addresses

## Troubleshooting

### No address suggestions appear
- Check that your API key is correctly set in the `.env` file
- Verify that the Places API is enabled in your Google Cloud Console
- Check browser console for any JavaScript errors
- Ensure your domain is whitelisted in the API key restrictions

### "This page can't load Google Maps correctly" error
- Your API key might be invalid or restricted
- Check API key restrictions match your current domain
- Verify billing is enabled for your Google Cloud project (required for Places API)

## Cost Information

Google Maps Places API offers a free tier:
- First $200 of usage per month is free
- Autocomplete requests: $2.83 per 1,000 requests
- Most small to medium websites stay within the free tier

For more information, visit: https://cloud.google.com/maps-platform/pricing

## Support

If you encounter issues, please check:
- [Google Maps JavaScript API Documentation](https://developers.google.com/maps/documentation/javascript)
- [Places Autocomplete Documentation](https://developers.google.com/maps/documentation/javascript/places-autocomplete)
