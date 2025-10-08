# V4L - Vocal 4 Local 🏪

**Your Community, Your Marketplace.**

V4L is a comprehensive web platform connecting local organizations with local customers, built with modern PHP and responsive design principles.

## 🌟 Features

- **Local Marketplace**: Browse and discover products and services from local businesses
- **Job Board**: Find employment opportunities in your community
- **Organization Management**: Manage your organization, branches, and operations
- **Hiring System**: Post vacancies, receive applications, conduct interviews
- **Catalog Management**: Centralized product/service catalog with seller integration
- **User Authentication**: Secure signup, login, and session management
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Dark/Light Mode**: Theme toggle with persistent user preference
- **Geography Support**: Multi-level geographic data (Continents → Countries → Languages)
- **Education & Skills Tracking**: Manage personal education and skill profiles

## 🏗️ Architecture

- **Language**: PHP 8.1+ (Pure PHP, no frameworks)
- **Architecture**: MVC pattern with microservices principles
- **Database**: SQLite (with migration path to MySQL/PostgreSQL)
- **Frontend**: Bootstrap 5.3, Vanilla JavaScript, Responsive Design
- **Patterns**: SOLID principles, Repository pattern, OOP

## 📋 Requirements

- PHP 8.1 or higher
- SQLite3 extension enabled
- Apache/Nginx web server
- Write permissions for `database/`, `logs/`, and `uploads/` directories

## 🚀 Installation

### 1. Clone or download the project

```bash
git clone <repository-url>
cd oom
```

### 2. Set up the environment file

```bash
cp .env.example .env
```

Edit `.env` to configure your settings (database, URLs, etc.)

### 3. Install dependencies (optional, for testing)

```bash
composer install
```

### 4. Initialize the database

```bash
php database/init-db.php
```

This will create all necessary tables in the SQLite database.

### 5. Configure your web server

#### Apache (.htaccess provided)

Point your document root to the `public/` directory.

#### Nginx

```nginx
server {
    listen 80;
    server_name localhost;
    root /path/to/oom/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Set permissions

```bash
chmod -R 755 database/ logs/ uploads/
```

### 7. Access the application

Navigate to `http://localhost` in your browser.

## 📱 Usage

### Creating Your First Account

1. Go to the homepage
2. Click "Sign Up" in the navigation
3. Fill in your personal details and create credentials
4. You'll be automatically logged in after signup

### Creating an Organization

1. After logging in, go to Dashboard
2. Click "Create Organization" under Quick Actions
3. Fill in organization details (name, legal category, subdomain, etc.)
4. Submit to create your organization

### Managing Data

The application provides full CRUD operations for all entities:

- **Geography**: Continents, Countries, Languages, Postal Addresses
- **People**: Persons, Credentials, Education, Skills
- **Organizations**: Organizations, Branches, Buildings, Workstations
- **Hiring**: Vacancies, Applications, Interviews, Job Offers, Contracts
- **Catalog**: Categories, Items, Features, Media, Tags, Reviews
- **Sellers**: Seller Items, Prices, Inventory, Service Schedules

### Navigation

The main menu is organized into four sections:

1. **My**: Personal profile, education, skills
2. **Organization**: Manage your organizations, vacancies, hiring
3. **Market**: Browse catalog, find sellers, search jobs
4. **Common**: Geography data, reference data (industries, skills, etc.)

## 🗂️ Project Structure

```
oom/
├── bootstrap.php              # Application bootstrap
├── composer.json              # PHP dependencies
├── config/                    # Configuration files
│   ├── app.php
│   ├── database.php
│   └── websocket.php
├── database/                  # Database files
│   ├── init-db.php           # Database initialization
│   └── database.sqlite       # SQLite database file
├── entities/                  # Entity classes
│   ├── BaseEntity.php        # Base entity with CRUD
│   ├── Continent.php
│   ├── Country.php
│   ├── Person.php
│   ├── Organization.php
│   └── ...
├── includes/                  # Shared includes
│   ├── header.php
│   └── footer.php
├── lib/                       # Core libraries
│   ├── Auth.php
│   ├── Database.php
│   ├── Router.php
│   ├── Validator.php
│   └── PageGenerator.php
├── public/                    # Web root
│   ├── index.php             # Homepage
│   ├── .htaccess             # Apache config
│   ├── assets/               # CSS, JS, images
│   └── pages/                # Application pages
│       ├── auth/             # Authentication pages
│       ├── dashboard.php     # User dashboard
│       ├── entities/         # Entity CRUD pages
│       └── market/           # Marketplace pages
├── services/                  # Service modules
├── tests/                     # Unit and integration tests
├── logs/                      # Application logs
└── uploads/                   # User uploads
```

## 🔒 Security Features

- Password hashing with bcrypt
- SQL injection prevention via PDO prepared statements
- XSS protection with output escaping
- CSRF protection for state-changing operations
- Session security (httpOnly, secure flags)
- Security headers (X-Frame-Options, Content-Security-Policy, etc.)
- Soft delete for data preservation

## 🎨 Customization

### Themes

The application supports light and dark modes. Users can toggle themes using the button in the navigation bar. The preference is saved in localStorage.

### Styling

Edit `public/assets/css/style.css` to customize colors, fonts, and layout. The application uses CSS variables for easy theming.

### Adding New Entities

1. Create entity class in `entities/` extending `BaseEntity`
2. Define table name and fillable fields
3. Add validation rules
4. Create CRUD pages in `public/pages/entities/[entity_name]/`
5. Update navigation in `includes/header.php`

## 🧪 Testing

Run PHPUnit tests:

```bash
vendor/bin/phpunit
```

## 📖 API Documentation

RESTful API endpoints will be documented separately. The application is designed to support both web and API access.

## 🤝 Contributing

This is a demonstration project following enterprise PHP best practices. For production use:

1. Implement comprehensive testing
2. Add proper error logging and monitoring
3. Configure production security settings
4. Implement caching strategies
5. Add WebSocket for real-time features
6. Migrate to MySQL/PostgreSQL for production scale

## 📝 License

This project is provided as-is for educational and demonstration purposes.

## 🔗 Related Documentation

- See `er_diagram.txt` for complete entity relationship diagram
- See `instructions.txt` for detailed architecture specification

## 💡 Support

For issues or questions:
- Check the documentation in `er_diagram.txt` and `instructions.txt`
- Review the code comments and PHPDoc blocks
- Examine example implementations in the Geography domain entities

## 🎯 Roadmap

Future enhancements:
- [ ] WebSocket implementation for real-time updates
- [ ] Email notification system
- [ ] File upload and management
- [ ] Advanced search with filters
- [ ] Reporting and analytics dashboard
- [ ] Multi-language support
- [ ] Export functionality (CSV, PDF, Excel)
- [ ] API authentication (OAuth/JWT)
- [ ] Rate limiting and API throttling
- [ ] Full-text search with SQLite FTS5

---

**V4L** - Building stronger communities through local connections. 🏪🤝👥
