# V4L (Vocal 4 Local)

**Your Community, Your Marketplace**

V4L is a metadata-driven PHP platform that connects local organizations with local customers, enabling community-driven commerce and interaction.

![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Version](https://img.shields.io/badge/version-1.0-green)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## Features

### Core Features
- **Metadata-Driven Architecture** - Entities defined in metadata enable dynamic CRUD operations
- **Auto-Generated UI** - List, detail, create, and edit pages generated automatically from entity definitions
- **Mobile-First Design** - Responsive Bootstrap-based UI optimized for mobile devices
- **RESTful API** - Complete API for all entities with automatic CRUD endpoints
- **Authentication & Authorization** - Secure login, registration, and position-based permissions
- **Multi-Organization Support** - Subdomain-based organization filtering
- **Workflow Engine** - Position-based task routing and process management

### Application Features
- **Local Marketplace** - Browse and purchase goods and services from local organizations
- **Job Vacancies** - Post and apply for jobs within your community
- **Organization Management** - Create and manage organizations with member roles
- **User Profiles** - Complete profile management with credentials and skills
- **Process Workflows** - Customizable workflows for business processes

---

## Technology Stack

- **Backend**: PHP 8.2+ (Core, no frameworks)
- **Database**: SQLite (development) / PostgreSQL (production)
- **Frontend**: Bootstrap 5, Vanilla JavaScript
- **Icons**: Bootstrap Icons
- **Architecture**: Metadata-driven, MVC pattern

---

## Project Structure

```
oom/
├── architecture/           # System architecture documentation
│   ├── application/       # Web application requirements
│   ├── entities/          # Entity creation rules
│   └── processes/         # Process flow system docs
├── config/                # Configuration files
│   ├── config.php         # Main configuration
│   └── .env.example       # Environment variables template
├── lib/                   # PHP library/classes
│   └── core/              # Core classes
│       ├── Autoloader.php
│       ├── Auth.php
│       ├── Database.php
│       ├── EntityController.php
│       ├── MetadataLoader.php
│       ├── Request.php
│       └── Response.php
├── metadata/              # Database metadata and migrations
│   ├── initial.sql        # Initial schema
│   ├── entities/          # Entity definitions (SQL)
│   └── processes/         # Process definitions (SQL)
├── public/                # Web root (document root should point here)
│   ├── api/               # API endpoints
│   │   └── index.php      # API router
│   ├── assets/            # Static assets
│   │   ├── css/           # Stylesheets
│   │   ├── js/            # JavaScript files
│   │   └── images/        # Images
│   ├── bootstrap.php      # Application bootstrap
│   ├── index.php          # Homepage
│   ├── login.php          # Login page
│   ├── register.php       # Registration page
│   ├── dashboard.php      # User dashboard
│   ├── marketplace.php    # Marketplace browsing
│   ├── vacancies.php      # Job vacancies listing
│   ├── entity-list.php    # Generic entity list page
│   └── .htaccess          # Apache configuration
├── templates/             # PHP templates
│   ├── layouts/           # Layout templates
│   │   └── main.php       # Main layout
│   ├── components/        # Reusable components
│   │   ├── navbar.php
│   │   └── footer.php
│   └── pages/             # Page templates
├── data/                  # Data directory (created automatically)
│   ├── v4l.db             # SQLite database (development)
│   └── uploads/           # Uploaded files
├── logs/                  # Application logs
└── README.md              # This file
```

---

## Installation

### Prerequisites

- PHP 8.2 or higher
- SQLite 3 (development) or PostgreSQL 12+ (production)
- Apache or Nginx web server
- Composer (optional, for future dependencies)

### Quick Start (Development - Windows)

**Automated Setup:**

1. **Clone the repository**
   ```cmd
   git clone <repository-url>
   cd oom
   ```

2. **Verify your setup** (optional but recommended)
   ```cmd
   check_setup.bat
   ```
   This will verify that PHP and SQLite are installed correctly.

3. **Run setup script**
   ```cmd
   setup.bat
   ```
   This will:
   - Create necessary directories
   - Copy configuration template
   - Create and populate the database

4. **Start development server**
   ```cmd
   start_dev_server.bat
   ```

5. **Access the application**
   - Open your browser to `http://localhost:8000`
   - Create an account via the registration page
   - Start using the application!

**Helper Scripts (Windows):**

- `setup.bat` - Initial setup (creates database and directories)
- `setup_clean.bat` - Delete database and start fresh
- `check_setup.bat` - Verify system requirements
- `start_dev_server.bat` - Start PHP development server
- `migrate.bat` - Apply new migrations to existing database

### Quick Start (Development - Linux/Mac)

**Manual Setup:**

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd oom
   ```

2. **Configure environment**
   ```bash
   cp config/.env.example config/.env
   ```
   Edit `config/.env` and set your configuration values.

3. **Create directories**
   ```bash
   mkdir -p data/uploads logs
   chmod -R 755 data/ logs/
   ```

4. **Set up the database**
   ```bash
   # Create database
   sqlite3 data/v4l.db < metadata/initial.sql

   # Import all entity and process definitions
   (
       echo "PRAGMA foreign_keys = OFF;"
       echo "BEGIN TRANSACTION;"
       cat metadata/entities/*.sql
       cat metadata/processes/*.sql
       echo "COMMIT;"
       echo "PRAGMA foreign_keys = ON;"
   ) | sqlite3 data/v4l.db
   ```

5. **Start development server**
   ```bash
   cd public
   php -S localhost:8000
   ```

6. **Access the application**
   - Open your browser to `http://localhost:8000`
   - Create an account via the registration page
   - Start using the application!

### Production Deployment

1. **Database**: Use PostgreSQL instead of SQLite
   ```bash
   # Create database
   createdb v4l

   # Import schema and data
   psql v4l < metadata/initial.sql

   # Import entities and processes
   for file in metadata/entities/*.sql; do
       psql v4l < "$file"
   done
   ```

2. **Environment**: Set `APP_ENV=production` in `.env`

3. **Security**:
   - Enable HTTPS (uncomment HTTPS redirect in `.htaccess`)
   - Set strong `SECRET_KEY` in `.env`
   - Configure SMTP for email notifications
   - Set proper file permissions (files: 644, directories: 755)

4. **Performance**:
   - Enable OPcache in PHP
   - Use a CDN for static assets
   - Enable caching in `config.php`

---

## Configuration

### Environment Variables

Key configuration options in `config/.env`:

```env
# Application Environment
APP_ENV=development              # development or production

# Database Configuration
DB_TYPE=sqlite                   # sqlite or pgsql
DB_HOST=localhost
DB_PORT=5432
DB_NAME=v4l
DB_USER=postgres
DB_PASS=password

# SMTP Configuration (optional)
SMTP_HOST=smtp.example.com
SMTP_PORT=587
SMTP_USER=user@example.com
SMTP_PASS=password
SMTP_FROM=noreply@v4l.local

# Security
SECRET_KEY=change_this_to_random_string
```

---

## Usage

### Creating Entities

All entities are defined in the database metadata. To add a new entity:

1. Create an entity definition in `metadata/entities/`
2. Import the SQL file into your database
3. The entity will automatically appear in the dashboard
4. CRUD pages are auto-generated based on metadata

### API Endpoints

The application provides automatic REST API endpoints for all entities:

```
GET    /api/entities                      # List all entities
GET    /api/entities/{entity}/metadata    # Get entity metadata
GET    /api/entities/{entity}             # List records
GET    /api/entities/{entity}/{id}        # Get single record
POST   /api/entities/{entity}             # Create record
PUT    /api/entities/{entity}/{id}        # Update record
DELETE /api/entities/{entity}/{id}        # Delete record

POST   /api/auth/register                 # Register new user
POST   /api/auth/login                    # Login
POST   /api/auth/logout                   # Logout
GET    /api/auth/me                       # Get current user
```

### Authentication

```php
// Check if user is logged in
if (Auth::isLoggedIn()) {
    $user = Auth::getCurrentUser();
}

// Require login for a page
Auth::requireLogin();

// Check permission
if (Auth::hasPermission('item', 'CREATE')) {
    // User can create items
}

// Require specific permission
Auth::requirePermission('item', 'UPDATE');
```

### Working with Entities

```php
use V4L\Core\MetadataLoader;
use V4L\Core\Database;

// Load entity metadata
$entity = MetadataLoader::getEntity('item');

// Get entity attributes
$attributes = MetadataLoader::getEntityAttributes($entity['id']);

// Query records
$items = Database::fetchAll("SELECT * FROM item WHERE is_active = 1");

// Create record
$id = Database::insert('item', [
    'id' => Database::generateUuid(),
    'name' => 'Product Name',
    'description' => 'Product description',
    'created_at' => date('Y-m-d H:i:s')
]);
```

---

## Features by Module

### Marketplace
- Browse items by category
- Search functionality
- Organization-specific filtering via subdomain
- Mobile-optimized product cards
- Pagination

### Job Vacancies
- List active job postings
- Filter by organization
- Application tracking (via process workflows)
- Interview scheduling

### Organization Management
- Create organizations
- Manage members and roles
- Position-based permissions
- Multi-organization support

### User Management
- Registration and login
- Profile management
- Skills and credentials
- Education history

### Process Workflows
- Define custom workflows
- Task routing based on positions
- Conditional branching
- Audit logging

---

## Development

### Adding Custom Pages

1. Create a new PHP file in `public/`
2. Include the bootstrap:
   ```php
   <?php require_once __DIR__ . '/bootstrap.php'; ?>
   ```
3. Use the template system:
   ```php
   ob_start();
   ?>
   <!-- Your HTML here -->
   <?php
   $content = ob_get_clean();
   render('layouts/main', compact('pageTitle', 'content'));
   ```

### Database Migrations

1. Create a new SQL file in `metadata/entities/` or `metadata/processes/`
2. Use sequential numbering (e.g., `0088_new_entity.sql`)
3. Import the file:
   ```bash
   sqlite3 data/v4l.db < metadata/entities/0088_new_entity.sql
   ```

---

## Architecture

### Metadata-Driven Design

V4L uses a metadata-driven architecture where entities, attributes, relationships, and validation rules are defined in the database. This allows:

- Dynamic CRUD generation
- Automatic API endpoints
- Flexible data model changes
- No code changes for new entities

### Permission System

Permissions are based on:
1. **Entity Permission Definitions** - Define which positions can perform which operations
2. **Employment Contracts** - Link users to positions within organizations
3. **Dynamic Checks** - Runtime permission validation

### Request Flow

1. HTTP Request → `public/*.php`
2. Bootstrap → Load config, autoloader, session
3. Authentication → Check user session
4. Authorization → Verify permissions
5. Controller → Process request
6. Template → Render response

---

## Testing

### Manual Testing

1. Create a test user account
2. Create a test organization
3. Add products/services
4. Post job vacancies
5. Test workflows

### API Testing

Use curl or Postman to test API endpoints:

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser","password":"password"}'

# List items
curl http://localhost:8000/api/entities/item

# Create item
curl -X POST http://localhost:8000/api/entities/item \
  -H "Content-Type: application/json" \
  -d '{"name":"New Item","description":"Description"}'
```

---

## Troubleshooting

### Setup Script Errors

**Problem**: "Error: near line X: no such table" during setup

**Solution**: This is expected! The improved setup script now:
- Disables foreign key constraints during import
- Imports all entities in a single transaction
- Re-enables foreign keys after import
- Logs errors to `setup_errors.log` for review

**Action**: Check if the database was created successfully:
```cmd
sqlite3 data\v4l.db "SELECT COUNT(*) FROM entity_definition;"
```
If it returns a number > 0, the database is functional.

**Problem**: Foreign key constraint failures

**Solution**: The setup script automatically handles this by disabling foreign keys during import. If you're manually importing:
```cmd
(
    echo PRAGMA foreign_keys = OFF;
    echo BEGIN TRANSACTION;
    type metadata\entities\*.sql
    echo COMMIT;
    echo PRAGMA foreign_keys = ON;
) | sqlite3 data\v4l.db
```

**Problem**: Setup script hangs or takes too long

**Solution**: The import can take 1-3 minutes depending on your system. Be patient. If it truly hangs:
1. Press Ctrl+C to stop
2. Run `setup_clean.bat` to start fresh
3. Try again with `setup.bat`

### Database Connection Errors
- Check database file permissions
- Verify DSN in `config/config.php`
- Ensure SQLite extension is enabled
- Run `check_setup.bat` to verify configuration

### Permission Denied Errors
- Check directory permissions (755 for directories, 644 for files)
- Ensure web server user can write to `data/` and `logs/`
- On Windows, ensure you have write access to the project directory

### Session Issues
- Verify session directory is writable
- Check session configuration in `php.ini`

### API Errors
- Enable error reporting in `config/config.php`
- Check `logs/` directory for error logs
- Verify `.htaccess` rewrite rules are working

### Starting Fresh

If you encounter issues and want to start over:

**Windows:**
```cmd
setup_clean.bat
setup.bat
```

**Linux/Mac:**
```bash
rm -f data/v4l.db
sqlite3 data/v4l.db < metadata/initial.sql
# Then run the import commands from Quick Start
```

---

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

## License

This project is licensed under the MIT License.

---

## Support

For questions, issues, or feature requests:
- Create an issue on GitHub
- Check the `/architecture` directory for documentation
- Review the `/guides` directory for implementation guides

---

## Credits

Built with:
- [Bootstrap 5](https://getbootstrap.com/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)
- PHP 8.2+
- SQLite / PostgreSQL

---

**V4L - Connecting communities, one transaction at a time.**
