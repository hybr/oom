#!/usr/bin/env php
<?php
/**
 * V4L WebSocket Server
 * Real-time communication server using Ratchet
 *
 * Usage:
 *   php bin/websocket-server.php
 *
 * The server listens on port 8080 and handles:
 * - Entity change notifications
 * - User presence tracking
 * - Real-time alerts and notifications
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use V4L\WebSocket\ConnectionManager;

// Load environment configuration
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Configuration
$host = $_ENV['WEBSOCKET_HOST'] ?? '0.0.0.0';
$port = $_ENV['WEBSOCKET_PORT'] ?? 8080;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          V4L WebSocket Server Starting                     ║\n";
echo "╠════════════════════════════════════════════════════════════╣\n";
echo "║  Host: " . str_pad($host, 50) . " ║\n";
echo "║  Port: " . str_pad($port, 50) . " ║\n";
echo "║  Time: " . str_pad(date('Y-m-d H:i:s'), 50) . " ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
echo "Server is ready to accept connections...\n";
echo "Press Ctrl+C to stop the server\n\n";

// Create and start the WebSocket server
try {
    $connectionManager = new ConnectionManager();

    $server = IoServer::factory(
        new HttpServer(
            new WsServer($connectionManager)
        ),
        $port,
        $host
    );

    // Run the server
    $server->run();

} catch (Exception $e) {
    echo "\n╔════════════════════════════════════════════════════════════╗\n";
    echo "║  ERROR: Server failed to start                            ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
