# V4L (Vocal 4 Local)

**Your Community, Your Marketplace**

![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Version](https://img.shields.io/badge/version-1.0-green)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## About V4L

V4L (Vocal 4 Local) is a metadata-driven PHP platform that connects local organizations with local customers, enabling community-driven commerce and interaction.

### Key Features

- **Metadata-Driven Architecture** - Entities defined in metadata enable dynamic CRUD operations
- **Auto-Generated UI** - List, detail, create, and edit pages generated automatically from entity definitions
- **Flexible Workflow Engine** - Position-based task routing and customizable process management
- **Real-time Updates** - WebSocket support for live entity updates
- **Secure by Design** - CSRF protection, input validation, and authentication built-in
- **Zero-Code Entity Creation** - Add new entities through metadata without writing application code
- **Extensible** - Plugin architecture for custom functionality

---

## Table of Contents

- [About V4L](#about-v4l)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Documentation Organization](#documentation-organization)
- [Common Tasks & Examples](#common-tasks--examples)
- [Technology Stack](#technology-stack)
- [Contributing](#contributing)
- [Support & Community](#support--community)
- [Security](#security)
- [License](#license)

---

## Requirements

Before installing V4L, ensure your environment meets these requirements:

- **PHP:** 8.2 or higher
- **Web Server:** Apache (with mod_rewrite) or Nginx
- **Database:** SQLite (development), PostgreSQL or MySQL (production)
- **PHP Extensions:** PDO, JSON, mbstring
- **Composer:** For dependency management (optional)

---

## Installation

```bash
# Clone the repository
git clone https://github.com/your-org/v4l.git
cd v4l

# Copy environment configuration
cp .env.example .env

# Edit configuration with your settings
nano .env

# Initialize database
php database/init-meta-db.php

# Set permissions
chmod 755 database/
chmod 666 database/*.sqlite

# Create logs directory
mkdir logs
chmod 755 logs/
```

**For detailed setup instructions:** See [`guides/getting-started/V4L_INSTALL.md`](guides/getting-started/V4L_INSTALL.md)

**Web Server Configuration:** See installation guide for Apache and Nginx configurations

---

## Quick Start

| What do you want to do? | Where to look |
|-------------------------|---------------|
| **Install V4L** | [`guides/getting-started/V4L_INSTALL.md`](guides/getting-started/V4L_INSTALL.md) |
| **Quick start guide** | [`guides/getting-started/V4L_QUICK_START.md`](guides/getting-started/V4L_QUICK_START.md) |
| **Run migrations** | [`guides/database/MIGRATION_GUIDE.md`](guides/database/MIGRATION_GUIDE.md) |
| **Create entities** | [`architecture/entities/ENTITY_CREATION_RULES.md`](architecture/entities/ENTITY_CREATION_RULES.md) |
| **Design workflows** | [`architecture/processes/PROCESS_FLOW_SYSTEM.md`](architecture/processes/PROCESS_FLOW_SYSTEM.md) |
| **Build the project** | [`guides/development/BUILD_SUMMARY.md`](guides/development/BUILD_SUMMARY.md) |

---

## Documentation Organization

The V4L documentation is organized into two main directories:

```
oom/
├── architecture/  ← System design, standards, technical specifications
└── guides/        ← Tutorials, how-to guides, implementation examples
```

### 📏 [`architecture/`](architecture/) - System Architecture & Standards

**Purpose:** Standards, conventions, and architecture references

**When to Use:**
- Designing a new feature or component
- Understanding system architecture
- Following naming conventions and design patterns
- Creating entity definitions or process workflows

**Structure:**
```
architecture/
├── README.md
├── entities/                   Entity design standards
│   ├── README.md
│   ├── ENTITY_CREATION_RULES.md
│   └── relationships/          Domain-specific relationship docs
│       ├── README.md
│       ├── RELATIONSHIP_RULES.md
│       ├── PERSON_IDENTITY_DOMAIN.md
│       ├── GEOGRAPHIC_DOMAIN.md
│       ├── ORGANIZATION_DOMAIN.md
│       ├── HIRING_VACANCY_DOMAIN.md
│       ├── PROCESS_FLOW_DOMAIN.md
│       ├── PERMISSIONS_SECURITY_DOMAIN.md
│       └── POPULAR_ORGANIZATION_STRUCTURE.md
└── processes/                  Process flow architecture
    ├── README.md
    ├── PROCESS_FLOW_SYSTEM.md
    └── PROCESS_SYSTEM_QUICK_START.md
```

---

### 📚 [`guides/`](guides/) - Implementation Guides & Tutorials

**Purpose:** Step-by-step tutorials and implementation documentation

**When to Use:**
- Installing the system
- Implementing a specific feature
- Following step-by-step instructions
- Troubleshooting issues

**Structure:**
```
guides/
├── README.md
├── getting-started/            Installation & quick start
│   ├── README.md
│   ├── V4L_INSTALL.md
│   └── V4L_QUICK_START.md
├── database/                   Migration guides
│   ├── README.md
│   ├── MIGRATION_GUIDE.md
│   ├── MIGRATION_UPDATE_SUMMARY.md
│   └── MIGRATION_FIXES_SUMMARY.md
├── features/                   Feature implementations
│   ├── README.md
│   ├── VACANCY_CREATION_PROCESS.md
│   ├── POSTAL_ADDRESS_UPDATES.md
│   ├── GEOCODING_SETUP.md
│   └── ORGANIZATION_MEMBERSHIP_PERMISSIONS.md
└── development/                Build & development setup
    ├── README.md
    ├── BUILD_SUMMARY.md
    └── PROCESS_SETUP_GUIDE.md
```

---

### Quick Decision Guide

| I need to... | Look in... |
|-------------|-----------|
| **Understand system architecture** | `/architecture` |
| **Learn design standards** | `/architecture` |
| **Get quick reference** | `/architecture` |
| **Install the system** | `/guides/getting-started` |
| **Run database migrations** | `/guides/database` |
| **Implement a feature** | `/guides/features` |
| **Build or setup processes** | `/guides/development` |
| **Troubleshoot an issue** | `/guides` (check relevant subdirectory) |

---

## Common Tasks & Examples

### Creating a New Entity

1. **Read Architecture:** [`architecture/entities/ENTITY_CREATION_RULES.md`](architecture/entities/ENTITY_CREATION_RULES.md)
   - Learn naming conventions and required fields
2. **Implement:** Create your entity migration file
3. **Run Migration:** [`guides/database/MIGRATION_GUIDE.md`](guides/database/MIGRATION_GUIDE.md)

### Implementing a Workflow

1. **Read Architecture:** [`architecture/processes/PROCESS_FLOW_SYSTEM.md`](architecture/processes/PROCESS_FLOW_SYSTEM.md)
   - Understand process architecture and node types
2. **Reference Example:** [`guides/features/VACANCY_CREATION_PROCESS.md`](guides/features/VACANCY_CREATION_PROCESS.md)
   - See complete working example
3. **Implement:** Create your process migration
4. **Setup:** [`guides/development/PROCESS_SETUP_GUIDE.md`](guides/development/PROCESS_SETUP_GUIDE.md)

### Understanding Entity Relationships

- **Browse:** [`architecture/entities/relationships/`](architecture/entities/relationships/)
- **Domains Available:**
  - Person & Identity
  - Geographic
  - Organization
  - Hiring & Vacancy
  - Process Flow
  - Permissions & Security

---

## Technology Stack

- **Backend:** PHP 8.2+
- **Frontend:** Bootstrap 5.3, Vanilla JavaScript
- **Database:** SQLite (development), PostgreSQL, MySQL (production)
- **Architecture:** Metadata-driven, Entity-Attribute-Value pattern
- **Security:** Argon2ID password hashing, CSRF protection, prepared statements
- **Web Standards:** HTML5, CSS3, ES6+

---

## Contributing

We welcome contributions! Here's how you can help:

### Quick Contribution Steps

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Contribution Guidelines

- Follow existing code style and conventions
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting PR
- Reference relevant issues in your PR description

**For detailed guidelines:** See [CONTRIBUTING.md](CONTRIBUTING.md) (if available)

---

## Support & Community

### Getting Help

- **Documentation:** You're reading it! Check specific sections above
- **Installation issues:** Start with [`guides/getting-started/`](guides/getting-started/)
- **Entity questions:** Check [`architecture/entities/`](architecture/entities/)
- **Process workflows:** See [`architecture/processes/`](architecture/processes/)
- **Feature guides:** Browse [`guides/features/`](guides/features/)
- **Database migrations:** See [`guides/database/`](guides/database/)

### Contact & Resources

- **Website:** https://v4l.app
- **Issues:** [GitHub Issues](https://github.com/v4l/vocal-4-local/issues)
- **Discussions:** [GitHub Discussions](https://github.com/v4l/vocal-4-local/discussions)
- **Email:** support@v4l.app

### Documentation Cross-References

Documentation directories cross-reference each other for easier navigation:

- **Architecture files** include: `> **📚 Note:** For implementation guides, see the /guides folder.`
- **Guide files** include: `> **📏 Note:** For system architecture, see the /architecture folder.`

---

## Security

Security is a top priority for V4L. The platform includes:

- **CSRF Protection** - All forms include CSRF tokens
- **XSS Prevention** - All output is HTML-escaped
- **SQL Injection Prevention** - PDO prepared statements throughout
- **Password Security** - Argon2ID hashing
- **Session Security** - HTTP-only cookies, regeneration on login
- **Input Validation** - Server-side validation for all inputs
- **Security Headers** - CSP, X-Frame-Options, X-Content-Type-Options

### Reporting Security Vulnerabilities

If you discover a security vulnerability, please email **security@v4l.app** instead of using the issue tracker. All security vulnerabilities will be promptly addressed.

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Copyright (c) 2024 V4L (Vocal 4 Local)

---

## Project Summary

| Directory | Purpose | When to Use |
|-----------|---------|-------------|
| `/architecture` | Design & Standards | Designing features, understanding system architecture |
| `/guides` | Implementation & Tutorials | Installing, implementing features, troubleshooting |

**Each directory contains detailed READMEs** for easy navigation and comprehensive documentation.

---

**Version:** 1.0
**Built with:** PHP 8.2+, Bootstrap 5.3, and modern web standards.

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history and updates.
