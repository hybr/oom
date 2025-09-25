# PHP Order Management System

A modern, responsive web application built with PHP core (no framework), featuring a complete order management workflow with real-time updates, dark/light themes, and comprehensive reporting.

## 🏗️ Architecture

- **Backend**: Pure PHP with OOP principles
- **Database**: SQLite (lightweight, file-based)
- **Frontend**: HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Real-time**: WebSocket server for live updates
- **Architecture Pattern**: Microservices with clear separation

## 📁 Project Structure

```
├── config/           # Database configuration
├── entities/         # Entity classes (Order, OrderItem)
├── process/          # State machine classes
├── services/         # Microservices modules
│   ├── entity/       # Entity CRUD operations
│   ├── process/      # Workflow management
│   ├── notifications/# Real-time notifications
│   ├── reports/      # Reporting system
│   └── websocket/    # WebSocket server
├── public/           # Web root
│   ├── api/          # REST API endpoints
│   └── index.html    # Main application
├── css/              # Styles with theme support
├── js/               # JavaScript application
├── tests/            # Unit testing framework
├── migrations/       # Database setup scripts
└── database/         # SQLite database files
```

## ⚡ Quick Start

1. **Check PHP extensions:**
   ```bash
   php check_extensions.php
   ```

2. **Setup the application:**
   ```bash
   php setup.php
   ```

3. **Start the web server:**
   ```bash
   php -S localhost:8000 -t public/
   ```

4. **Start WebSocket server (optional):**
   ```bash
   php services/websocket/SimpleWebSocketServer.php
   ```

5. **Open your browser:**
   ```
   http://localhost:8000
   ```

### Windows Users
Double-click `start_servers.bat` to automatically start both servers.

## 🔧 Features

### ✅ Core Features
- **Order Management**: Complete CRUD operations for orders
- **State Machine**: Workflow engine with configurable states and transitions
- **Real-time Updates**: WebSocket integration for live notifications
- **Responsive Design**: Mobile-first, works on all devices
- **Dark/Light Themes**: User preference with localStorage persistence
- **RESTful API**: Complete API for all operations
- **Reports Dashboard**: Analytics and insights
- **Unit Testing**: Comprehensive test suite

### 📊 Order States
- **Draft** → **Pending** → **Paid** → **Shipped** → **Delivered** → **Closed**
- **Cancellation**: Available from Draft, Pending states
- **Refunds**: Available from Paid state
- **Returns**: Available from Shipped, Delivered states

### 🎨 UI/UX Features
- Bootstrap 5 responsive framework
- Automatic dark/light theme switching
- Real-time status updates
- Toast notifications
- Loading indicators
- Process flow visualization
- Action buttons based on current state

## 🔌 API Endpoints

### Orders
- `GET /api/entities/Order` - List all orders
- `GET /api/entities/Order/{id}` - Get specific order
- `POST /api/entities/Order` - Create new order
- `PUT /api/entities/Order/{id}` - Update order
- `DELETE /api/entities/Order/{id}` - Delete order

### Process Management
- `GET /api/processes/order/{id}/state` - Get current state
- `GET /api/processes/order/{id}/history` - Get state history
- `GET /api/processes/order/{id}/transitions?role=admin` - Get available transitions
- `POST /api/processes/order/{id}` - Transition to new state

### Reports
- `GET /api/reports/order_summary` - Order summary by status
- `GET /api/reports/order_trends?days=30` - Order trends
- `GET /api/reports/top_customers?limit=10` - Top customers
- `GET /api/reports/process_efficiency` - Workflow efficiency metrics

### Notifications
- `GET /api/notifications?limit=50` - Recent notifications
- `GET /api/notifications?entity_id=123` - Notifications for specific entity

## 🧪 Testing

Run the complete test suite:
```bash
php tests/RunAllTests.php
```

Individual test suites:
```bash
php tests/EntityTest.php
php tests/ProcessTest.php
```

## 🔒 Security Features

- Input validation and sanitization
- SQL injection protection via prepared statements
- XSS prevention
- CORS headers for API access
- Role-based access control for state transitions

## 📱 Responsive Breakpoints

- **Mobile**: < 576px
- **Tablet**: 576px - 768px
- **Desktop**: 768px - 992px
- **Large Desktop**: > 992px

## 🎯 Microservices Architecture

### Entity Service
- CRUD operations for all entities
- Schema management
- Data validation

### Process Service
- State machine management
- Transition validation
- History tracking
- Rollback capabilities

### Notification Service
- Real-time event broadcasting
- WebSocket message queuing
- Notification persistence

### Report Service
- Analytics and insights
- Configurable reports
- Chart data preparation

## 🚀 Extensibility

### Adding New Entities
1. Create entity class extending `BaseEntity`
2. Define schema and fillable attributes
3. Add to `EntityService` allowed entities
4. Create migration script

### Adding New Processes
1. Create process class extending `BaseProcess`
2. Define states and transitions
3. Add to `ProcessService` processes array
4. Implement state callbacks

### Adding New Reports
1. Add method to `ReportService`
2. Register in `getAvailableReports()`
3. Create frontend visualization

## 📊 Performance Considerations

- SQLite for lightweight operations
- Indexed database queries
- Efficient WebSocket broadcasting
- Minimal JavaScript framework overhead
- CSS/JS minification ready
- Lazy loading patterns

## 🔧 Configuration

Database and application settings in `config/database.php`:
- Database path
- Connection options
- Error handling

Theme configuration in `css/styles.css`:
- Color variables
- Responsive breakpoints
- Animation settings

## 📈 Production Deployment

1. **Database**: Consider MySQL/PostgreSQL for production
2. **Web Server**: Use Apache/Nginx instead of built-in server
3. **WebSocket**: Implement with proper WebSocket library (ReactPHP)
4. **Caching**: Add Redis/Memcached for session management
5. **Security**: Implement JWT authentication
6. **Monitoring**: Add logging and error tracking

## 🤝 Contributing

1. Follow PSR-4 autoloading standards
2. Write tests for new features
3. Maintain coding standards
4. Update documentation

## 📄 License

Open source - feel free to modify and distribute.

---

**Built with ❤️ using PHP Core, Bootstrap 5, and modern web standards**