# V4L Quick Start Guide

Get V4L up and running in 5 minutes!

---

## Windows Users

### First Time Setup

### Step 1: Verify Requirements
```cmd
check_setup.bat
```

This checks if you have:
- PHP 8.2+
- SQLite3
- Required PHP extensions

If anything is missing, install it before proceeding.

### Step 2: Run Setup (First Time)
```cmd
setup.bat
```

This will:
- ✅ Create directories (data, logs)
- ✅ Copy configuration file
- ✅ Create and populate database
- ⏱️ Takes 1-3 minutes

**Note**: Database import warnings are normal! The script handles them automatically.

### If Database Already Exists

If you need to start fresh (database corrupted or want to reset):

```cmd
setup_clean.bat
setup.bat
```

**WARNING**: `setup_clean.bat` deletes all your data! Only use if you want to start over.

### Step 3: Verify Database
```cmd
sqlite3 data\v4l.db "SELECT COUNT(*) FROM entity_definition;"
```

Should return a number greater than 0 (typically 80+).

### Step 4: Start Server
```cmd
start_dev_server.bat
```

### Step 5: Open Browser
Navigate to: **http://localhost:8000**

### Step 6: Create Account
1. Click "Sign Up"
2. Fill in your details (username and password are required)
3. Click "Create Account"
4. Login with your credentials

**Done!** 🎉

---

## Linux/Mac Users

### One-Line Setup
```bash
mkdir -p data/uploads logs && \
cp config/.env.example config/.env && \
sqlite3 data/v4l.db < metadata/initial.sql && \
(echo "PRAGMA foreign_keys = OFF; BEGIN TRANSACTION;" && cat metadata/entities/*.sql metadata/processes/*.sql && echo "COMMIT; PRAGMA foreign_keys = ON;") | sqlite3 data/v4l.db && \
cd public && php -S localhost:8000
```

Then open **http://localhost:8000** in your browser.

### Step-by-Step Setup

```bash
# 1. Create directories
mkdir -p data/uploads logs

# 2. Copy config
cp config/.env.example config/.env

# 3. Create database
sqlite3 data/v4l.db < metadata/initial.sql

# 4. Import entities and processes
(
    echo "PRAGMA foreign_keys = OFF;"
    echo "BEGIN TRANSACTION;"
    cat metadata/entities/*.sql
    cat metadata/processes/*.sql
    echo "COMMIT;"
    echo "PRAGMA foreign_keys = ON;"
) | sqlite3 data/v4l.db

# 5. Verify
sqlite3 data/v4l.db "SELECT COUNT(*) FROM entity_definition;"

# 6. Start server
cd public
php -S localhost:8000
```

---

## Common Issues

### "PHP command not found"
**Windows**: Add PHP to your PATH environment variable
**Linux/Mac**: Install PHP: `sudo apt install php8.2` or `brew install php`

### "sqlite3 command not found"
**Windows**: Download from https://www.sqlite.org/download.html and add to PATH
**Linux/Mac**: Install: `sudo apt install sqlite3` or `brew install sqlite3`

### "Error: near line X: no such table"
**Don't worry!** The new setup script handles this automatically by:
- Disabling foreign keys during import
- Running everything in a transaction
- Re-enabling foreign keys after

Check if database works: `sqlite3 data\v4l.db "SELECT COUNT(*) FROM entity_definition;"`

### Setup hangs
- Be patient, it takes 1-3 minutes
- If truly hung, press Ctrl+C and run `setup_clean.bat` (Windows) or delete `data/v4l.db` (Linux/Mac) and try again

### Can't write to data/ or logs/
**Windows**: Run Command Prompt as Administrator or check folder permissions
**Linux/Mac**: `chmod -R 755 data/ logs/`

---

## Helper Scripts (Windows)

| Script | Purpose |
|--------|---------|
| `check_setup.bat` | Verify PHP, SQLite, and requirements |
| `setup.bat` | Initial setup (run once) |
| `setup_clean.bat` | Delete database and start fresh |
| `start_dev_server.bat` | Start PHP server on port 8000 |
| `migrate.bat` | Apply new migrations to existing DB |

---

## What to Do Next

### 1. Create Your First Organization
- Login to dashboard
- Navigate to "organization" entity
- Click "Add New"
- Fill in organization details

### 2. Add Products to Marketplace
- Navigate to "item" entity
- Click "Add New"
- Fill in product details
- Items will appear on marketplace

### 3. Post a Job Vacancy
- Navigate to "organization_vacancy" entity
- Click "Add New"
- Fill in job details
- Vacancy will appear on jobs page

### 4. Explore the API
Try these endpoints:
```bash
# Get all entities
curl http://localhost:8000/api/entities

# Register user (via API)
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser","password":"password123"}'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser","password":"password123"}'

# List items
curl http://localhost:8000/api/entities/item
```

---

## Need Help?

- 📖 Read the full [README.md](README.md)
- 🔧 Check [Troubleshooting](README.md#troubleshooting)
- 📚 Review [INSTALLATION.md](INSTALLATION.md) for detailed setup
- 🏗️ See [architecture/](architecture/) for system design docs

---

## Production Deployment

For production, see:
- [INSTALLATION.md](INSTALLATION.md) - Full production setup guide
- Use PostgreSQL instead of SQLite
- Enable HTTPS
- Set `APP_ENV=production` in `.env`
- Configure proper backups

---

**Enjoy building your community marketplace!** 🚀
