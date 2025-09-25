<?php

echo "=== PHP Order Management System Setup ===\n\n";

// Initialize database and create tables
echo "1. Setting up database...\n";
require_once 'migrations/migrate.php';
$migration = new DatabaseMigration();
$migration->migrate();

// Add notification table
echo "2. Setting up notifications...\n";
require_once 'services/notifications/NotificationService.php';
NotificationService::createTable();

// Seed sample data
echo "3. Adding sample data...\n";
$migration->seed();

echo "\n=== Setup Complete! ===\n";
echo "✓ Database created and migrated\n";
echo "✓ Sample orders added\n";
echo "✓ All tables initialized\n\n";

echo "Next steps:\n";
echo "1. Check extensions: php check_extensions.php\n";
echo "2. Start PHP built-in server: php -S localhost:8000 -t public/\n";
echo "3. (Optional) Start WebSocket server: php services/websocket/SimpleWebSocketServer.php\n";
echo "4. Open browser: http://localhost:8000\n";
echo "5. Run tests: php tests/RunAllTests.php\n\n";

echo "API Endpoints available at http://localhost:8000/api/\n";
echo "- GET /api/entities/Order - List all orders\n";
echo "- POST /api/entities/Order - Create new order\n";
echo "- GET /api/processes/order/{id}/state - Get order state\n";
echo "- POST /api/processes/order/{id} - Transition order state\n";
echo "- GET /api/reports/order_summary - Order summary report\n";
echo "- GET /api/notifications - Get recent notifications\n\n";