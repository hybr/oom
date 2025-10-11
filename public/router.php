<?php
/**
 * Router script for PHP built-in server
 * Routes all requests through index.php
 */

// Get the URI without query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if file exists and is not a directory
$filePath = __DIR__ . $uri;
if (is_file($filePath)) {
    return false; // Serve the actual file
}

// Otherwise, route through index.php
require_once __DIR__ . '/index.php';
