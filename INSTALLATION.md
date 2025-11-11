# V4L Installation Guide

This guide will help you install and set up V4L (Vocal 4 Local) on your system.

---

## Quick Start (Windows)

### Prerequisites

1. **PHP 8.2 or higher**
   - Download from: https://windows.php.net/download/
   - Ensure these extensions are enabled in `php.ini`:
     - `extension=pdo_sqlite`
     - `extension=sqlite3`
     - `extension=mbstring`
     - `extension=openssl`

2. **SQLite3**
   - Download from: https://www.sqlite.org/download.html
   - Add to your PATH environment variable

### Automated Setup

1. Open Command Prompt in the project directory
2. Run the setup script:
   ```cmd
   setup.bat
   ```

3. Edit `config\.env` with your settings (if needed)

4. Start the development server:
   ```cmd
   cd public
   php -S localhost:8000
   ```

5. Open your browser to `http://localhost:8000`

---

## Manual Setup (Windows)

### Step 1: Create Directories

```cmd
mkdir data
mkdir data\uploads
mkdir logs
```

### Step 2: Configure Environment

```cmd
copy config\.env.example config\.env
```

Edit `config\.env` and set your configuration values.

### Step 3: Initialize Database

```cmd
REM Create database and import initial schema
sqlite3 data\v4l.db < metadata\initial.sql

REM Import all entity definitions
for %f in (metadata\entities\*.sql) do sqlite3 data\v4l.db < %f

REM Import all process definitions
for %f in (metadata\processes\*.sql) do sqlite3 data\v4l.db < %f
```

### Step 4: Set Permissions

Ensure the following directories are writable:
- `data/`
- `data/uploads/`
- `logs/`

### Step 5: Start Development Server

```cmd
cd public
php -S localhost:8000
```

### Step 6: Access Application

Open your browser to `http://localhost:8000` and create your first account!

---

## Production Setup (Linux/Unix)

### Prerequisites

- PHP 8.2+ with extensions: pdo_sqlite, sqlite3, mbstring, openssl
- Apache or Nginx web server
- PostgreSQL 12+ (recommended for production)
- SSL certificate (for HTTPS)

### Step 1: Install Dependencies

```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install php8.2 php8.2-fpm php8.2-sqlite3 php8.2-pgsql php8.2-mbstring php8.2-xml

# For PostgreSQL
sudo apt-get install postgresql postgresql-contrib
```

### Step 2: Clone Repository

```bash
git clone <repository-url>
cd oom
```

### Step 3: Configure Environment

```bash
cp config/.env.example config/.env
nano config/.env
```

Set production values:
```env
APP_ENV=production
DB_TYPE=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_NAME=v4l
DB_USER=v4l_user
DB_PASS=secure_password
SECRET_KEY=generate_a_random_32_character_string
```

### Step 4: Create PostgreSQL Database

```bash
# Create database and user
sudo -u postgres psql

CREATE DATABASE v4l;
CREATE USER v4l_user WITH PASSWORD 'secure_password';
GRANT ALL PRIVILEGES ON DATABASE v4l TO v4l_user;
\q

# Import schema
psql -U v4l_user -d v4l < metadata/initial.sql

# Import entities
for file in metadata/entities/*.sql; do
    psql -U v4l_user -d v4l < "$file"
done

# Import processes
for file in metadata/processes/*.sql; do
    psql -U v4l_user -d v4l < "$file"
done
```

### Step 5: Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /path/to/oom

# Set permissions
find /path/to/oom -type d -exec chmod 755 {} \;
find /path/to/oom -type f -exec chmod 644 {} \;

# Make data and logs writable
chmod -R 775 data/
chmod -R 775 logs/
```

### Step 6: Configure Web Server

#### Apache

Create virtual host: `/etc/apache2/sites-available/v4l.conf`

```apache
<VirtualHost *:80>
    ServerName v4l.yourdomain.com
    DocumentRoot /path/to/oom/public

    <Directory /path/to/oom/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/v4l_error.log
    CustomLog ${APACHE_LOG_DIR}/v4l_access.log combined
</VirtualHost>
```

Enable site and modules:
```bash
sudo a2ensite v4l.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx

Create server block: `/etc/nginx/sites-available/v4l`

```nginx
server {
    listen 80;
    server_name v4l.yourdomain.com;
    root /path/to/oom/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/v4l /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Step 7: Enable HTTPS

```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-apache

# Apache
sudo certbot --apache -d v4l.yourdomain.com

# Nginx
sudo certbot --nginx -d v4l.yourdomain.com
```

### Step 8: Configure Cron Jobs (Optional)

Add to crontab for scheduled tasks:
```bash
crontab -e

# Example: Clean up old sessions daily at 2 AM
0 2 * * * find /path/to/oom/data/sessions -type f -mtime +1 -delete

# Example: Backup database daily at 3 AM
0 3 * * * pg_dump -U v4l_user v4l > /path/to/backups/v4l_$(date +\%Y\%m\%d).sql
```

---

## Post-Installation

### Create First Admin User

1. Navigate to `http://yourdomain.com/register.php`
2. Create your account
3. The first user can be promoted to super admin via database:

```sql
-- SQLite
UPDATE person SET is_super_admin = 1 WHERE id = 'user_id';

-- PostgreSQL
UPDATE person SET is_super_admin = true WHERE id = 'user_id';
```

### Create First Organization

1. Log in to your account
2. Navigate to Dashboard
3. Go to entity list for "organization"
4. Click "Add New"
5. Fill in organization details

---

## Verification

### Check PHP Configuration

```bash
php -v  # Should show PHP 8.2 or higher
php -m  # Should list pdo_sqlite, sqlite3, mbstring, openssl
```

### Check Database Connection

```bash
sqlite3 data/v4l.db "SELECT COUNT(*) FROM entity_definition;"
# Should return a number > 0
```

### Test Application

1. Access homepage: Should load without errors
2. Register account: Should create user successfully
3. Login: Should authenticate and redirect to dashboard
4. Browse marketplace: Should display items (if any)

---

## Troubleshooting

### Database Connection Error

**Problem**: "Database connection failed"

**Solutions**:
- Check database file exists: `ls -l data/v4l.db`
- Check file permissions: Should be readable by web server user
- Verify DSN in `config/config.php`

### Permission Denied Errors

**Problem**: Cannot write to logs or uploads

**Solutions**:
```bash
sudo chown -R www-data:www-data data/ logs/
sudo chmod -R 775 data/ logs/
```

### 500 Internal Server Error

**Problem**: White page or 500 error

**Solutions**:
- Check error logs: `tail -f logs/*.log`
- Enable error display in development:
  - Set `APP_ENV=development` in `.env`
- Check Apache/Nginx error logs:
  ```bash
  sudo tail -f /var/log/apache2/error.log
  sudo tail -f /var/log/nginx/error.log
  ```

### API Not Working

**Problem**: API endpoints return 404

**Solutions**:
- Check mod_rewrite is enabled (Apache):
  ```bash
  sudo a2enmod rewrite
  sudo systemctl restart apache2
  ```
- Verify `.htaccess` exists in `public/`
- Check Nginx rewrite rules are correct

### Session Issues

**Problem**: Login doesn't persist

**Solutions**:
- Check session directory is writable
- Verify session configuration in `php.ini`
- Clear browser cookies and cache

---

## Upgrading

### From Development to Production

1. Export SQLite data:
   ```bash
   sqlite3 data/v4l.db .dump > v4l_export.sql
   ```

2. Create PostgreSQL database (see Step 4 above)

3. Import data:
   ```bash
   psql -U v4l_user -d v4l < v4l_export.sql
   ```

4. Update `.env` to use PostgreSQL

5. Test thoroughly before going live

---

## Next Steps

- Read the [README.md](README.md) for usage instructions
- Check [architecture/](architecture/) for system architecture
- Review [guides/](guides/) for implementation guides
- Customize templates in [templates/](templates/)

---

## Support

For issues during installation:
1. Check this guide thoroughly
2. Review error logs in `logs/` directory
3. Consult the troubleshooting section
4. Create an issue on GitHub with:
   - Your OS and PHP version
   - Complete error messages
   - Steps to reproduce

---

**Congratulations!** You've successfully installed V4L. Enjoy building your community marketplace!
