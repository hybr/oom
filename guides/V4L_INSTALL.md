# V4L Installation & Deployment Guide

Complete guide for installing and deploying V4L (Vocal 4 Local).

---

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Development Setup](#development-setup)
3. [Production Deployment](#production-deployment)
4. [Database Configuration](#database-configuration)
5. [Web Server Configuration](#web-server-configuration)
6. [Troubleshooting](#troubleshooting)

---

## System Requirements

### Minimum Requirements

- **PHP**: 8.1 or higher
- **Web Server**: Apache 2.4+ with mod_rewrite OR Nginx 1.18+
- **Database**: SQLite 3.x (development), PostgreSQL 12+ or MySQL 8+ (production)
- **Memory**: 256 MB RAM minimum
- **Disk Space**: 100 MB minimum

### Required PHP Extensions

- `pdo` - Database abstraction
- `pdo_sqlite` - SQLite support
- `pdo_pgsql` - PostgreSQL support (production)
- `pdo_mysql` - MySQL support (production)
- `json` - JSON handling
- `mbstring` - Multibyte string support
- `session` - Session management
- `openssl` - Encryption support

### Recommended PHP Extensions

- `opcache` - Performance optimization
- `apcu` - User cache
- `gd` or `imagick` - Image processing
- `zip` - File compression

---

## Development Setup

### Option 1: Automated Setup (Recommended)

```bash
# Navigate to project directory
cd /path/to/v4l

# Run setup script
php setup.php
```

This will:
1. Check PHP version and extensions
2. Create necessary directories
3. Copy .env.example to .env
4. Initialize meta database

### Option 2: Manual Setup

```bash
# 1. Create directories
mkdir -p database logs cache

# 2. Set permissions
chmod 755 database logs cache
chmod 666 database/*.sqlite

# 3. Create environment file
cp .env.example .env

# 4. Initialize meta database
php database/init-meta-db.php
```

### Development Server

For quick testing, use PHP's built-in server:

```bash
cd public
php -S localhost:8000
```

Visit: http://localhost:8000

---

## Production Deployment

### 1. Server Preparation

```bash
# Update system
sudo apt update && sudo apt upgrade

# Install PHP and extensions
sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-common \
  php8.2-pdo php8.2-pgsql php8.2-mbstring php8.2-json \
  php8.2-opcache php8.2-apcu

# Install web server (Apache)
sudo apt install apache2

# OR install Nginx
sudo apt install nginx
```

### 2. Application Deployment

```bash
# Create application directory
sudo mkdir -p /var/www/v4l
sudo chown www-data:www-data /var/www/v4l

# Clone/upload application
cd /var/www/v4l
# Upload files or git clone

# Set ownership
sudo chown -R www-data:www-data /var/www/v4l

# Set permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Make writable directories
chmod 775 database logs cache
chmod 664 database/*.sqlite
```

### 3. Environment Configuration

```bash
# Copy environment template
cp .env.example .env

# Edit environment file
nano .env
```

Set production values:

```env
APP_NAME="V4L - Production"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://v4l.yourdomain.com

# Use PostgreSQL or MySQL
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=v4l_production
DB_USERNAME=v4l_user
DB_PASSWORD=secure_password_here

SESSION_SECURE=true
```

### 4. Initialize Databases

```bash
# Initialize meta database
php database/init-meta-db.php

# For production DB, ensure it's created first
sudo -u postgres psql
CREATE DATABASE v4l_production;
CREATE USER v4l_user WITH PASSWORD 'secure_password_here';
GRANT ALL PRIVILEGES ON DATABASE v4l_production TO v4l_user;
\q
```

### 5. Security Hardening

```bash
# Generate encryption key
php -r "echo bin2hex(random_bytes(32));" > .encryption_key
chmod 600 .encryption_key

# Secure .env file
chmod 600 .env

# Disable directory listing
# (Already in .htaccess)
```

---

## Database Configuration

### SQLite (Development)

No additional configuration needed. Database files are created automatically.

### PostgreSQL (Production)

```bash
# Install PostgreSQL
sudo apt install postgresql postgresql-contrib

# Create database and user
sudo -u postgres psql
CREATE DATABASE v4l_production;
CREATE USER v4l_user WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE v4l_production TO v4l_user;
\q

# Update .env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=v4l_production
DB_USERNAME=v4l_user
DB_PASSWORD=your_password
```

### MySQL (Production)

```bash
# Install MySQL
sudo apt install mysql-server

# Secure installation
sudo mysql_secure_installation

# Create database and user
sudo mysql
CREATE DATABASE v4l_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'v4l_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON v4l_production.* TO 'v4l_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Update .env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=v4l_production
DB_USERNAME=v4l_user
DB_PASSWORD=your_password
```

---

## Web Server Configuration

### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName v4l.yourdomain.com
    ServerAdmin admin@yourdomain.com

    DocumentRoot /var/www/v4l/public

    <Directory /var/www/v4l/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/v4l_error.log
    CustomLog ${APACHE_LOG_DIR}/v4l_access.log combined

    # Security headers (additional to .htaccess)
    Header always set X-Frame-Options "DENY"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
</VirtualHost>

# SSL Configuration (HTTPS)
<VirtualHost *:443>
    ServerName v4l.yourdomain.com
    ServerAdmin admin@yourdomain.com

    DocumentRoot /var/www/v4l/public

    <Directory /var/www/v4l/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/v4l.crt
    SSLCertificateKeyFile /etc/ssl/private/v4l.key
    SSLCertificateChainFile /etc/ssl/certs/chain.crt

    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

    ErrorLog ${APACHE_LOG_DIR}/v4l_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/v4l_ssl_access.log combined
</VirtualHost>
```

Enable required modules:

```bash
sudo a2enmod rewrite headers ssl
sudo systemctl restart apache2
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name v4l.yourdomain.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name v4l.yourdomain.com;

    root /var/www/v4l/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/ssl/certs/v4l.crt;
    ssl_certificate_key /etc/ssl/private/v4l.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Logging
    access_log /var/log/nginx/v4l_access.log;
    error_log /var/log/nginx/v4l_error.log;

    # PHP-FPM
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }

    location ~ \.(log|sqlite|db)$ {
        deny all;
    }

    # Static file caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2?)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Test and reload Nginx:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## SSL/TLS Configuration

### Using Let's Encrypt (Free SSL)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get certificate (Apache)
sudo certbot --apache -d v4l.yourdomain.com

# OR for Nginx
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d v4l.yourdomain.com

# Auto-renewal is configured automatically
# Test renewal
sudo certbot renew --dry-run
```

---

## Troubleshooting

### 404 Errors / Routing Not Working

**Apache**:
- Ensure mod_rewrite is enabled: `sudo a2enmod rewrite`
- Check .htaccess is in public/ directory
- Verify AllowOverride is set to "All"

**Nginx**:
- Check try_files directive is correct
- Ensure fastcgi_pass points to correct socket

### Database Connection Errors

- Verify database credentials in .env
- Check database server is running
- Ensure PHP extension is installed (pdo_pgsql, pdo_mysql, pdo_sqlite)
- Check database user has correct permissions

### Permission Denied Errors

```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/v4l

# Fix permissions
chmod 775 database logs cache
chmod 664 database/*.sqlite .env
```

### Session Errors

- Check session directory is writable
- Verify session.save_path in php.ini
- Ensure cookies are enabled in browser

### "Headers already sent" Error

- Check no output before session_start()
- Look for BOM in PHP files
- Ensure no whitespace before `<?php`

---

## Performance Optimization

### Enable OPcache

Edit `/etc/php/8.2/fpm/php.ini`:

```ini
[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

Restart PHP-FPM:

```bash
sudo systemctl restart php8.2-fpm
```

### Enable APCu Cache

```bash
sudo apt install php8.2-apcu

# Update .env
CACHE_DRIVER=apcu
```

### Database Query Caching

For PostgreSQL:

```sql
ALTER SYSTEM SET shared_buffers = '256MB';
ALTER SYSTEM SET effective_cache_size = '1GB';
SELECT pg_reload_conf();
```

---

## Backup Strategy

### Automated Backup Script

```bash
#!/bin/bash
# /usr/local/bin/v4l-backup.sh

BACKUP_DIR="/var/backups/v4l"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
cp /var/www/v4l/database/v4l.sqlite $BACKUP_DIR/v4l_$DATE.sqlite

# Backup PostgreSQL (if using production database)
sudo -u postgres pg_dump v4l_production > $BACKUP_DIR/v4l_prod_$DATE.sql

# Backup application files
tar -czf $BACKUP_DIR/v4l_files_$DATE.tar.gz /var/www/v4l

# Keep only last 7 days
find $BACKUP_DIR -mtime +7 -delete

echo "Backup completed: $DATE"
```

Add to crontab:

```bash
sudo crontab -e

# Daily backup at 2 AM
0 2 * * * /usr/local/bin/v4l-backup.sh
```

---

## Monitoring

### Application Logs

```bash
# View error logs
tail -f /var/www/v4l/logs/app.log

# View web server logs
tail -f /var/log/apache2/v4l_error.log
# OR
tail -f /var/log/nginx/v4l_error.log
```

### Health Check Endpoint

Create `/public/health.php`:

```php
<?php
header('Content-Type: application/json');

$status = [
    'status' => 'healthy',
    'timestamp' => date('c'),
    'php_version' => PHP_VERSION,
];

// Check database
try {
    $db = new PDO('sqlite:../database/v4l.sqlite');
    $status['database'] = 'connected';
} catch (PDOException $e) {
    $status['database'] = 'error';
    $status['status'] = 'unhealthy';
}

http_response_code($status['status'] === 'healthy' ? 200 : 503);
echo json_encode($status);
```

---

## Support

For additional help:

- Documentation: README.md, QUICK_START.md
- Issues: GitHub repository
- Email: support@v4l.app

---

**Your V4L application is now fully configured and ready for production!**
