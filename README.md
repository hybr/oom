# V4L (Vocal 4 Local) - Geo-Intelligent Marketplace Platform

## Overview
V4L is a modern, enterprise-grade PHP application built without frameworks, featuring microservices architecture, real-time capabilities, and comprehensive workflow management.

## System Architecture

### Technology Stack
- **Backend**: PHP 8.1+ (Pure PHP, no framework)
- **Database**: SQLite (with migration path to PostgreSQL/MySQL)
- **Frontend**: HTML5, Bootstrap 5.3+, Vanilla JavaScript (ES6+)
- **Real-time**: WebSocket
- **Architecture**: Microservices, MVC, SOLID principles

### Folder Structure
```
project-root/
├── config/                 # Configuration files
│   ├── app.php            # Application config
│   ├── database.php       # Database config
│   └── websocket.php      # WebSocket config
├── database/
│   ├── migrations/        # Schema migrations
│   ├── seeders/          # Data seeders
│   └── database.sqlite   # SQLite database file
├── entities/             # Entity classes (Domain Models)
│   ├── BaseEntity.php    # Abstract base entity
│   ├── Geography Domain
│   │   ├── Continent.php
│   │   ├── Country.php
│   │   ├── Language.php
│   │   └── PostalAddress.php
│   ├── Person Domain
│   │   ├── Person.php
│   │   └── Credential.php
│   ├── Education & Skill Domain
│   │   ├── PopularEducationSubject.php
│   │   ├── PopularSkill.php
│   │   ├── PersonEducation.php
│   │   ├── PersonEducationSubject.php
│   │   └── PersonSkill.php
│   └── [More entities to be created]
├── processes/            # Workflow/Process classes
│   └── BaseProcess.php
├── services/             # Microservice modules
│   ├── entity/          # Entity CRUD operations
│   ├── workflow/        # Process engine
│   ├── auth/            # Authentication & authorization
│   ├── notification/    # Notifications
│   ├── reporting/       # Reports
│   ├── search/          # Full-text search
│   ├── integration/     # External APIs
│   ├── audit/           # Audit trails
│   └── storage/         # File storage
├── includes/             # Shared includes
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
├── public/               # Web root
│   ├── index.php        # Entry point
│   ├── api/             # API endpoints
│   ├── assets/          # Static assets
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── entities/        # Entity web pages
├── lib/                  # Core libraries
│   ├── Database.php     # Database layer
│   ├── Router.php       # Request routing
│   ├── Validator.php    # Validation
│   └── WebSocket.php    # WebSocket server
├── tests/                # Test suite
│   ├── Unit/
│   └── Integration/
├── logs/                 # Application logs
├── uploads/              # User uploads
└── composer.json        # Dependencies
```

## Core Features

### 1. BaseEntity Class
All entities inherit from `BaseEntity` which provides:

#### Attributes (All Entities)
- `id` - Primary key
- `created_at` - Creation timestamp
- `created_by` - Creator user ID
- `updated_at` - Last update timestamp
- `updated_by` - Last updater user ID
- `deleted_at` - Soft delete timestamp
- `version` - Optimistic locking version

#### CRUD Operations
- `save()` - Insert or update
- `delete()` - Soft delete
- `forceDelete()` - Hard delete
- `restore()` - Restore soft-deleted record
- `find($id)` - Find by ID
- `all($limit, $offset)` - Get all records
- `where($condition, $params)` - Custom queries
- `count($condition)` - Count records

#### Validation & Security
- Automatic validation based on rules
- Field-level validation errors
- SQL injection prevention (PDO prepared statements)
- Optimistic locking for concurrent updates

#### Audit Trail
- Automatic audit logging
- Change history tracking
- User action tracking
- IP and user agent capture

### 2. Database Layer
The `Database` class (`lib/Database.php`) provides:
- PDO-based connection management
- Support for SQLite, MySQL, PostgreSQL
- Query builder methods
- Transaction support
- Connection pooling (singleton pattern)

### 3. Entity Domains

#### Geography Domain
- **Continent**: Global continents
- **Country**: Countries with continent links
- **Language**: Languages by country
- **PostalAddress**: Full addresses with geo-coordinates
  - Distance calculations
  - Proximity search
  - Multi-line formatting

#### Person Domain
- **Person**: Individual profiles
  - Full name formatting
  - Age calculation
  - Initials generation
- **Credential**: Authentication
  - Password hashing (Argon2)
  - Login/logout
  - Password reset
  - Remember me tokens
  - Account locking after failed attempts

#### Education & Skill Domain
- **PopularEducationSubject**: Reference subjects
- **PopularSkill**: Reference skills
- **PersonEducation**: Education history
  - Institution tracking
  - Education levels (ENUM)
  - Duration calculations
- **PersonEducationSubject**: Grades/marks per subject
- **PersonSkill**: Skill proficiency
  - Skill levels (beginner, intermediate, expert)
  - Certification tracking

### 4. Security Features

#### Authentication
- Secure password hashing (Argon2ID)
- Session management
- Remember me functionality
- Account lockout protection
- Password reset with time-limited tokens

#### Data Protection
- SQL injection prevention (PDO)
- XSS prevention (output escaping)
- CSRF protection (token validation)
- Sensitive data exclusion from API responses

#### Security Headers (via .htaccess)
- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection
- Referrer-Policy

### 5. API Design

#### RESTful Endpoints Pattern
```
GET    /api/entities          # List
GET    /api/entities/{id}     # Get single
POST   /api/entities          # Create
PUT    /api/entities/{id}     # Update
DELETE /api/entities/{id}     # Delete
POST   /api/entities/{id}/action  # Execute action
GET    /api/entities/{id}/history # Get history
```

#### Response Format
```json
{
  "success": true,
  "data": {},
  "message": "Operation successful",
  "errors": [],
  "meta": {
    "timestamp": "2025-10-05T10:30:00Z",
    "version": "1.0",
    "pagination": {}
  }
}
```

## Entity Relationships

### Geography Relationships
```
Continent (1) ──→ (N) Country
Country (1) ──→ (N) Language
Country (1) ──→ (N) PostalAddress
```

### Person Relationships
```
Person (1) ──→ (1) Credential
Person (1) ──→ (N) PersonEducation
PersonEducation (1) ──→ (N) PersonEducationSubject
PersonEducationSubject (N) ──→ (1) PopularEducationSubject
Person (1) ──→ (N) PersonSkill
PersonSkill (N) ──→ (1) PopularSkill
```

## Key Entity Methods

### Person Entity
- `getFullName()`: Returns formatted full name
- `getInitials()`: Returns initials (e.g., "JD")
- `getAge()`: Calculates age from date of birth
- `getCredential()`: Gets authentication credential
- `getEducation()`: Gets all education records
- `getSkills()`: Gets all skills
- `searchByName($query)`: Search persons by name

### Credential Entity
- `setPassword($password)`: Hash and set password
- `verifyPassword($password)`: Verify password
- `login($username, $password)`: Authenticate user
- `signUp($personId, $username, $password)`: Create account
- `forgotPassword()`: Generate reset token
- `resetPassword($token, $newPassword)`: Reset password
- `changePassword($current, $new)`: Change password
- `generateRememberToken()`: Create remember token
- `isLocked()`: Check if account is locked

### PostalAddress Entity
- `getFormattedAddress()`: Get one-line address
- `getMultiLine()`: Get multi-line address array
- `hasCoordinates()`: Check if coordinates exist
- `distanceTo($otherAddress)`: Calculate distance in km
- `findNear($lat, $lng, $radius)`: Find nearby addresses
- `searchByLocation($query)`: Search addresses

## Configuration

### Environment Variables (.env)
```
APP_NAME="V4L - Vocal 4 Local"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=./database/database.sqlite

SESSION_LIFETIME=120
WEBSOCKET_HOST=localhost
WEBSOCKET_PORT=8080
```

## Installation

1. **Clone the repository**
   ```bash
   git clone <repo-url>
   cd oom
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your settings
   ```

4. **Set up database**
   ```bash
   php database/migrations/run.php
   ```

5. **Start web server**
   ```bash
   php -S localhost:8000 -t public
   ```

6. **Access application**
   ```
   http://localhost:8000
   ```

## Testing

### Run PHPUnit Tests
```bash
composer test
# or
vendor/bin/phpunit
```

### Test Coverage
```bash
vendor/bin/phpunit --coverage-html tests/coverage
```

## Development Principles

### SOLID Principles
- **Single Responsibility**: Each class has one purpose
- **Open/Closed**: Open for extension, closed for modification
- **Liskov Substitution**: Derived classes are substitutable
- **Interface Segregation**: Many specific interfaces
- **Dependency Inversion**: Depend on abstractions

### Design Patterns Used
- Repository Pattern (BaseEntity)
- Singleton (Database connection)
- Factory Pattern (Entity creation)
- Observer (Event handling - planned)
- Strategy (Workflow actions - planned)

### Code Standards
- PSR-12 coding style
- PHPDoc blocks for all classes and methods
- Type hints and return types
- Descriptive naming conventions

## Upcoming Features

### To Be Implemented
1. Organization Domain Entities
2. Hiring Domain Entities
3. Process Authorization Domain
4. Workflow/Process Engine
5. RESTful API Endpoints
6. WebSocket Real-time Updates
7. Responsive UI Components
8. Dark/Light Theme Support
9. Comprehensive Testing Suite

## Menu Structure

Based on `@menu.txt`, the application features:

### Main Modules
1. **Dashboard** - Overview and stats
2. **My** - Personal management (profile, education, skills, addresses)
3. **Organization** - Business management (branches, teams, positions, HR)
4. **Market** - Marketplace (goods, services, map view, requests)
5. **Common** - Reference data (geography, languages, industries)
6. **Administration** - System management (users, content moderation, reports)
7. **Account** - Authentication (login, signup, password management)

## Entity Method Summary

### BaseEntity (Abstract)
- CRUD: save(), delete(), forceDelete(), restore()
- Query: find(), findWithTrashed(), all(), where(), count()
- Validation: validate(), getErrors()
- Conversion: toArray(), toJson()
- Audit: getHistory(), logAudit()
- Search: search()

### Domain-Specific Methods
Each entity extends BaseEntity with 10-15 action methods specific to its domain, including:
- Relationship getters
- Business logic calculations
- State transitions
- Search/filter capabilities
- Formatted output methods

## Security Best Practices

1. **Never store plain passwords** - Always use password_hash()
2. **Use prepared statements** - Prevent SQL injection
3. **Validate all inputs** - Server-side validation
4. **Escape all outputs** - Prevent XSS
5. **Implement CSRF tokens** - Protect state-changing operations
6. **Use HTTPS in production** - Encrypt data in transit
7. **Regular security audits** - Review code and dependencies

## Support & Documentation

- Entity documentation: See individual entity files
- API documentation: `/docs/api.md` (to be created)
- Workflow documentation: `/docs/workflows.md` (to be created)

## License
[Your License Here]

## Contributors
[Your Name/Team]

---

**Note**: This is a work in progress. Additional entities, services, and UI components are being developed according to the architecture specification.
