# V4L Quick Start Guide

Get up and running with V4L in 5 minutes!

## Prerequisites

- PHP 8.2+
- Apache with mod_rewrite OR Nginx
- Command line access

## Step 1: Environment Setup

```bash
# Navigate to project directory
cd /path/to/v4l

# Copy environment file
cp .env.example .env

# (Optional) Edit .env if needed
```

## Step 2: Initialize Database

```bash
# Run the database initializer
php database/init-meta-db.php
```

Expected output:
```
Initializing Database...
Created new database at: database/v4l.sqlite
Executed metadata SQL script.

Verification:
==================================================
entity_definition           :    8 records
entity_attribute            :   XX records
entity_relationship         :    7 records
entity_function             :   XX records
entity_function_handler     :   XX records
entity_validation_rule      :    3 records
==================================================

Database initialized successfully!
```

## Step 3: Set Permissions

```bash
# Create necessary directories
mkdir -p database logs cache

# Set permissions (Linux/Mac)
chmod 755 database logs cache
chmod 666 database/*.sqlite

# Or for development, make fully writable
chmod 777 database logs cache
```

## Step 4: Configure Web Server

### Option A: Apache

```apache
<VirtualHost *:80>
    ServerName v4l.local
    DocumentRoot /path/to/v4l/public

    <Directory /path/to/v4l/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `/etc/hosts`:
```
127.0.0.1  v4l.local
```

Restart Apache:
```bash
sudo systemctl restart apache2
```

### Option B: PHP Built-in Server (Development Only)

```bash
cd public
php -S localhost:8000
```

## Step 5: Access the Application

Open your browser and navigate to:

- **Apache**: http://v4l.local
- **PHP Server**: http://localhost:8000

## Step 6: Create Your Account

1. Click "Sign Up" in the navigation
2. Fill in the form:
   - Username (3-20 characters)
   - Email
   - Password (min 8 characters)
3. Click "Sign Up"
4. Login with your credentials

## Step 7: Explore

After logging in, you can:

- **View Dashboard**: `/dashboard`
- **Manage Continents**: `/entities/continent/list`
- **Manage Countries**: `/entities/country/list`
- **Manage States**: `/entities/state/list`
- **Manage Cities**: `/entities/city/list`

## Creating Your First Record

1. Go to `/entities/continent/list`
2. Click "Create New"
3. Fill in the form:
   - Continent Name: e.g., "Asia"
   - Continent Code: e.g., "AS"
   - (Other fields optional)
4. Click "Create"
5. You'll be redirected to the detail view

## Troubleshooting

### "Page not found" errors

**Apache**: Check that mod_rewrite is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Nginx**: Ensure your config includes the rewrite rules.

### Database errors

Make sure the database directory is writable:
```bash
chmod 777 database
```

### "Class not found" errors

Check that `bootstrap.php` is being loaded correctly and the autoloader is working.

### Session errors

Ensure session directory is writable (usually `/tmp` or `/var/lib/php/sessions`).

## Next Steps

- **Add Data**: Populate entities with your local data
- **Customize**: Edit CSS in `public/assets/css/style.css`
- **Extend**: Add new entities via metadata
- **Deploy**: Follow production deployment guide in README.md

## Need Help?

- Check README.md for detailed documentation
- Review requirements.txt for technical specifications
- Examine metadata.txt to understand entity structure

---

**You're all set! Welcome to V4L.**
