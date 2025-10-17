# V4L (Vocal 4 Local)

**Tagline:** *Your Community, Your Marketplace*

**Version:** 1.0
**Domain:** https://v4l.app

---

## Overview

V4L (Vocal 4 Local) is a PHP-based web platform designed to connect local organizations with local customers, enabling community-driven commerce and interaction.

### Key Features

- **Metadata-Driven Architecture**: Entities are defined in metadata, enabling dynamic CRUD operations
- **Auto-Generated UI**: List, detail, create, and edit pages are automatically generated from entity definitions
- **Responsive Design**: Bootstrap 5.3+ with mobile-first approach
- **Real-time Updates**: WebSocket support for live entity updates
- **Secure by Design**: Built-in CSRF protection, input validation, and authentication
- **Extensible**: Add new entities purely through metadata without writing code

---

## Requirements

- **PHP**: 8.2 or higher
- **Web Server**: Apache (with mod_rewrite) or Nginx
- **Database**: SQLite (development), PostgreSQL/MySQL (production)
- **Extensions**: PDO, JSON, mbstring

---

## Quick Start

### 1. Installation

```bash
# Clone or download the repository
cd /path/to/v4l

# Copy environment configuration
cp .env.example .env

# Edit .env with your settings
nano .env
```

### 2. Initialize Database

```bash
# Initialize database (contains entity definitions and data)
php database/init-meta-db.php

# This will create database/v4l.sqlite with all entity metadata
```

### 3. Set Permissions

```bash
# Make database directory writable
chmod 755 database/
chmod 666 database/*.sqlite

# Make logs directory writable
mkdir logs
chmod 755 logs/
```

### 4. Configure Web Server

#### Apache

Ensure `mod_rewrite` is enabled and your document root points to the `public/` directory.

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

#### Nginx

```nginx
server {
    listen 80;
    server_name v4l.local;
    root /path/to/v4l/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 5. Create User Account

Visit `http://v4l.local/auth/signup` and create an account.

### 6. Explore

- **Dashboard**: `/dashboard`
- **Continents**: `/entities/continent/list`
- **Countries**: `/entities/country/list`
- **States**: `/entities/state/list`
- **Cities**: `/entities/city/list`

---

## Architecture

### Metadata-Driven Framework

V4L uses a **single database** (v4l.sqlite) that contains both metadata and operational data:

- **Entities**: Tables and their properties
- **Attributes**: Columns with data types, validations, and constraints
- **Relationships**: Foreign keys and entity associations
- **Functions**: CRUD operations and business logic
- **Validation Rules**: Input validation and business rules

### Core Components

1. **EntityManager** (`lib/EntityManager.php`)
   - Reads metadata from the database
   - Dynamically creates database tables
   - Provides CRUD operations for all entities

2. **PageGenerator** (`lib/PageGenerator.php`)
   - Auto-generates list, detail, create, and edit views
   - Renders forms with proper input types and validation
   - Handles foreign key relationships with dropdowns

3. **Router** (`lib/Router.php`)
   - Routes all requests through `public/index.php`
   - Supports dynamic entity routes (`/entities/:entity/:action`)

4. **Auth** (`lib/Auth.php`)
   - Session-based authentication
   - Password hashing with Argon2ID
   - CSRF token generation and validation
   - Account lockout after failed attempts

5. **Database** (`lib/Database.php`)
   - PDO wrapper for safe query execution
   - Supports v4l.sqlite database
   - Prepared statements prevent SQL injection

6. **Validator** (`lib/Validator.php`)
   - Input validation with multiple rules
   - XSS protection through sanitization

---

## Directory Structure

```
v4l/
├── bootstrap.php           # Application bootstrap
├── composer.json           # Dependencies
├── metadata.txt            # Entity metadata (SQL script)
├── requirements.txt        # Technical requirements
├── .env.example            # Environment template
├── config/
│   └── app.php             # Configuration loader
├── database/
│   ├── init-meta-db.php    # Database initializer
│   └── v4l.sqlite          # Main database (created on init)
├── lib/                    # Core framework classes
│   ├── Auth.php
│   ├── Database.php
│   ├── EntityManager.php
│   ├── PageGenerator.php
│   ├── Router.php
│   └── Validator.php
├── public/                 # Web root
│   ├── index.php           # Entry point
│   ├── .htaccess           # Apache rewrite rules
│   ├── assets/             # CSS, JS, images
│   └── pages/              # Page templates
│       ├── auth/           # Login, signup, logout
│       ├── entities/       # Auto-CRUD pages
│       └── dashboard.php   # User dashboard
├── includes/
│   ├── header.php          # HTML header
│   └── footer.php          # HTML footer
└── tests/                  # PHPUnit tests
```

---

## Adding New Entities

To add a new entity, simply insert metadata into the database:

```sql
-- 1. Add entity definition
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES ('...uuid...', 'MY_ENTITY', 'My Entity', 'Description', 'DOMAIN', 'my_entity');

-- 2. Add attributes
INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, description)
VALUES ('...uuid...', '...entity_id...', 'my_field', 'My Field', 'text', 1, 'Field description');

-- 3. Add relationships (optional)
INSERT INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, fk_field)
VALUES ('...uuid...', '...from_id...', '...to_id...', 'ManyToOne', 'parent_id');
```

**That's it!** The framework will automatically:
- Create the database table
- Generate list, detail, create, and edit pages
- Handle CRUD operations
- Validate inputs based on metadata

---

## Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **XSS Prevention**: All output is HTML-escaped
- **SQL Injection Prevention**: PDO prepared statements
- **Password Security**: Argon2ID hashing
- **Session Security**: HTTP-only, regeneration on login
- **Input Validation**: Server-side validation for all inputs
- **Security Headers**: CSP, X-Frame-Options, X-Content-Type-Options

---

## Development

### Running Tests

```bash
# Install PHPUnit
composer install

# Run tests
composer test

# Or directly
vendor/bin/phpunit
```

### Debug Mode

Set `APP_DEBUG=true` in `.env` to enable error display.

---

## Production Deployment

1. **Set Environment**:
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Use Production Database**:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=your-db-host
   DB_DATABASE=v4l_production
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. **Enable HTTPS**:
   - Uncomment HTTPS redirect in `.htaccess`
   - Uncomment HSTS header

4. **Set Encryption Key**:
   ```bash
   php -r "echo bin2hex(random_bytes(32));" > .encryption_key
   ```

5. **Optimize**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

---

## License

MIT License - See LICENSE file for details

---

## Support

For issues and questions:
- GitHub: https://github.com/v4l/vocal-4-local
- Email: support@v4l.app
- Docs: https://docs.v4l.app

---

**Built with PHP 8.2+, Bootstrap 5.3, and modern web standards.**
