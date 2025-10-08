# Authentication System Setup

## Overview
The authentication system has been successfully implemented. The system now protects all entity pages while keeping the Home Dashboard and Marketplace publicly accessible.

## Public Pages (No Login Required)
- **Home Dashboard** (`/`)
- **Jobs/Vacancies Listing** (`/organization_vacancies`)
- **Marketplace Catalog** (`/catalog_categories`)
- **Login/Signup Pages** (`/login`, `/signup`)

## Protected Pages (Login Required)
- All entity CRUD operations (create, edit, update, delete)
- All entity listings except those marked as public
- Personal profile and credential management
- Organization management
- Application and hiring workflows

## Key Components

### 1. Authentication Middleware (`lib/Auth.php`)
- `Auth::require()` - Redirects to login if not authenticated
- `Auth::check()` - Returns true/false if user is authenticated
- `Auth::user()` - Returns the authenticated user object

### 2. Router Updates (`lib/Router.php`)
- Added middleware support to routes
- Middleware runs before route handlers
- Supports multiple middleware per route

### 3. Route Protection (`public/index.php`)
- Public entities defined in `$publicEntities` array
- Generic entity routes check authentication based on entity type
- All create/edit/update/delete actions require authentication

### 4. Header Navigation (`includes/header.php`)
- Shows Login/Signup buttons when not authenticated
- Shows user dropdown with Logout when authenticated
- Protected menu items (My, Organization, Market, Common) only visible to logged-in users
- Public navigation (Home, Jobs, Marketplace) visible to everyone

## Usage

### Protecting a New Route
```php
$router->get('/protected-page', function () {
    require __DIR__ . '/pages/protected.php';
}, [[\App\Auth::class, 'require']]);
```

### Protecting a Page Directly
```php
<?php
require_once __DIR__ . '/../../../bootstrap.php';
\App\Auth::require();

// Rest of your page code
?>
```

### Adding a New Public Entity
Add the entity to the `$publicEntities` array in `public/index.php`:
```php
$publicEntities = ['catalog_categories', 'your_new_entity'];
```

## Authentication Flow
1. User tries to access protected page
2. Middleware checks if user is authenticated
3. If not authenticated:
   - Current URL is stored in session
   - User is redirected to login page
   - Error message is shown
4. After successful login:
   - User is redirected to originally requested page
   - Success message is shown

## Features
- ✅ Login/Logout functionality
- ✅ User registration
- ✅ Password hashing (Argon2ID)
- ✅ Remember me functionality
- ✅ Account lockout after failed attempts
- ✅ Password reset token system
- ✅ CSRF protection
- ✅ Session management
- ✅ Redirect after login to intended page
