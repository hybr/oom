# Database Setup Guide

## ✅ Database Fixed and Initialized

The database connection error has been resolved and all tables have been created.

## 🔧 What Was Fixed

### 1. **Database Path Issue**
**Problem**: Relative path `./database/database.sqlite` wasn't resolving correctly

**Solution**:
- Updated `.env` to use absolute path: `C:/Users/fwyog/oom/database/database.sqlite`
- Updated `config/database.php` to use `__DIR__` for fallback path
- Set proper file permissions (666 for SQLite file, 755 for directory)

### 2. **Missing Tables**
**Problem**: Empty database with no tables

**Solution**: Created initialization script that creates all required tables

## 📊 Tables Created

### Geography Domain
- ✅ `continent` - Continents with countries relationship
- ✅ `country` - Countries with continent FK
- ✅ `language` - Languages with country FK
- ✅ `postal_address` - Addresses with geo-coordinates

### Person Domain
- ✅ `person` - Person profiles
- ✅ `credential` - User authentication with security features

### Education & Skill Domain
- ✅ `popular_education_subject` - Reference subjects
- ✅ `popular_skill` - Reference skills
- ✅ `person_education` - Education history
- ✅ `person_education_subject` - Subject grades
- ✅ `person_skill` - Skill proficiency

### System Tables
- ✅ `audit_log` - Complete audit trail
- ✅ `sqlite_sequence` - Auto-increment tracking (SQLite internal)

## 🚀 Quick Start

### 1. Initialize Database (If Needed)
```bash
php database/init-db.php
```

This will create all tables. Safe to run multiple times (uses `CREATE TABLE IF NOT EXISTS`).

### 2. Start the Application
```bash
php -S localhost:8000 -t public
```

### 3. Access the Application
Open your browser:
- **Dashboard**: http://localhost:8000/
- **Continents**: http://localhost:8000/continents
- **Countries**: http://localhost:8000/countries
- **Persons**: http://localhost:8000/persons
- **Login**: http://localhost:8000/login
- **Sign Up**: http://localhost:8000/signup

## 📋 Database Schema Details

### Common Fields (All Tables)
Every table includes:
- `id` - Primary key (auto-increment)
- `created_at` - Timestamp (auto-set)
- `created_by` - User ID who created
- `updated_at` - Timestamp (auto-updated)
- `updated_by` - User ID who updated
- `deleted_at` - Soft delete timestamp
- `version` - Optimistic locking version

### Example: Continent Table
```sql
CREATE TABLE continent (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_by INTEGER,
    deleted_at DATETIME,
    version INTEGER DEFAULT 1
);
```

### Foreign Key Relationships

```
continent (1) ──→ (N) country
country (1) ──→ (N) language
country (1) ──→ (N) postal_address
person (1) ──→ (1) credential
person (1) ──→ (N) person_education
person (1) ──→ (N) person_skill
person_education (1) ──→ (N) person_education_subject
person_education_subject (N) ──→ (1) popular_education_subject
person_skill (N) ──→ (1) popular_skill
```

## 🔒 Security Features

### Password Hashing
- Algorithm: Argon2ID (most secure)
- Stored in `credential.password_hash`
- Never exposed in API responses

### Account Lockout
- 5 failed attempts triggers lockout
- Locked for 30 minutes
- Tracked in `credential.failed_login_attempts` and `credential.locked_until`

### CSRF Protection
- Token stored in session
- Validated on all POST requests
- Auto-generated per session

### SQL Injection Prevention
- PDO prepared statements used exclusively
- Parameters always bound, never concatenated

## 📝 Database Operations

### Using Entity Classes

```php
use Entities\Continent;

// Create
$continent = new Continent();
$continent->name = 'Asia';
$continent->save();

// Read
$continent = Continent::find(1);
$continents = Continent::all(25, 0); // 25 per page, offset 0
$count = Continent::count();

// Update
$continent = Continent::find(1);
$continent->name = 'Updated Name';
$continent->save();

// Delete (soft)
$continent->delete();

// Restore
$continent->restore();

// Hard delete
$continent->forceDelete();

// Search
$results = Continent::searchByName('Asia');

// Custom queries
$results = Continent::where('name LIKE :name', ['name' => '%sia%']);
```

### Direct Database Access

```php
use App\Database;

// Query
$sql = "SELECT * FROM continent WHERE deleted_at IS NULL";
$results = Database::fetchAll($sql);

// Insert
Database::insert('continent', ['name' => 'Europe']);

// Update
Database::update('continent', ['name' => 'Updated'], 'id = :id', ['id' => 1]);

// Delete
Database::delete('continent', 'id = :id', ['id' => 1]);
```

## 🔄 Migrations

### Current Setup
Tables are created via `database/init-db.php`. This is a simple initialization script.

### For Production
Consider implementing a proper migration system:
1. Create `database/migrations/` with timestamped migration files
2. Track applied migrations in a `migrations` table
3. Support up/down migrations
4. Version control migration files

## 📊 Checking Database

### View All Tables
```bash
sqlite3 database/database.sqlite ".tables"
```

### View Table Schema
```bash
sqlite3 database/database.sqlite ".schema continent"
```

### Query Data
```bash
sqlite3 database/database.sqlite "SELECT * FROM continent"
```

### Count Records
```bash
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM continent"
```

## 🛠️ Troubleshooting

### Database Locked Error
**Cause**: SQLite doesn't handle concurrent writes well

**Solution**:
- Use transactions for bulk operations
- Consider PostgreSQL/MySQL for production
- Ensure proper connection closing

### Permission Denied
**Cause**: Insufficient file permissions

**Solution**:
```bash
chmod 755 database
chmod 666 database/database.sqlite
```

### Foreign Key Constraint Failed
**Cause**: Trying to insert/update with invalid FK

**Solution**:
- Ensure referenced record exists
- Check `deleted_at` on referenced record
- Verify FK field matches PK type

### Table Not Found
**Cause**: Database not initialized

**Solution**:
```bash
php database/init-db.php
```

## 🎯 Next Steps

### 1. Seed Sample Data (Optional)
Create a seeder script:
```bash
php database/seeders/SeedSampleData.php
```

### 2. Backup Database
```bash
cp database/database.sqlite database/backups/database_$(date +%Y%m%d).sqlite
```

### 3. Monitor Database Size
```bash
ls -lh database/database.sqlite
```

### 4. Optimize (If Needed)
```bash
sqlite3 database/database.sqlite "VACUUM"
```

## 📈 Database Statistics

After initialization:
- **Tables**: 13 (including sqlite_sequence)
- **Size**: ~16 KB (empty)
- **Foreign Keys**: Enabled
- **Indexes**: Auto-created on PKs and FKs

## ⚠️ Important Notes

1. **Soft Deletes**: All entities use soft deletes by default
   - Records marked with `deleted_at` timestamp
   - Still present in database
   - Excluded from normal queries
   - Can be restored

2. **Optimistic Locking**: Version field prevents concurrent update conflicts
   - Increments on each update
   - Checked before saving
   - Throws error if versions don't match

3. **Audit Trail**: All operations logged to `audit_log`
   - Entity type and ID
   - Action performed
   - User who performed it
   - Timestamp and IP address

4. **SQLite Limitations**:
   - Single writer at a time
   - No stored procedures
   - Limited ALTER TABLE support
   - Consider PostgreSQL/MySQL for production

---

**Database is ready for use! 🎉**
