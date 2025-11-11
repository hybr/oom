<?php
/**
 * API Router
 *
 * Routes API requests to appropriate controllers
 */

require_once __DIR__ . '/../../config/config.php';
require_once LIB_PATH . '/core/Autoloader.php';

use V4L\Core\Autoloader;
use V4L\Core\Auth;
use V4L\Core\Request;
use V4L\Core\Response;
use V4L\Core\EntityController;

// Initialize autoloader
Autoloader::register();

// Start session
Auth::startSession();

// Set CORS headers if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Parse the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($scriptName, '', $requestUri);
$path = trim(parse_url($path, PHP_URL_PATH), '/');

// Remove 'api/' prefix if present
$path = preg_replace('#^api/#', '', $path);

// Split path into segments
$segments = explode('/', $path);

// Create request object
$request = new Request();
$method = $request->method();

try {
    // Route: /api/auth/register
    if ($segments[0] === 'auth' && $segments[1] === 'register' && $method === 'POST') {
        $result = Auth::register($request->all());
        if ($result['success']) {
            Response::success($result, 'Registration successful', 201);
        } else {
            Response::validationError($result['errors']);
        }
    }

    // Route: /api/auth/login
    if ($segments[0] === 'auth' && $segments[1] === 'login' && $method === 'POST') {
        $username = $request->get('username');
        $password = $request->get('password');

        $result = Auth::login($username, $password);
        if ($result['success']) {
            Response::success($result['user'], 'Login successful');
        } else {
            Response::unauthorized($result['error']);
        }
    }

    // Route: /api/auth/logout
    if ($segments[0] === 'auth' && $segments[1] === 'logout' && $method === 'POST') {
        Auth::logout();
        Response::success(null, 'Logged out successfully');
    }

    // Route: /api/auth/me
    if ($segments[0] === 'auth' && $segments[1] === 'me' && $method === 'GET') {
        if (!Auth::isLoggedIn()) {
            Response::unauthorized();
        }
        $user = Auth::getCurrentUser();
        Response::success($user);
    }

    // Route: /api/entities (list all entities)
    if ($segments[0] === 'entities' && count($segments) === 1 && $method === 'GET') {
        $entities = \V4L\Core\MetadataLoader::loadEntities();
        Response::success($entities);
    }

    // Route: /api/entities/{entity_code}/metadata
    if ($segments[0] === 'entities' && count($segments) === 3 && $segments[2] === 'metadata' && $method === 'GET') {
        $entityCode = $segments[1];
        $controller = new EntityController($entityCode);
        $controller->metadata();
    }

    // Route: /api/entities/{entity_code} (list records)
    if ($segments[0] === 'entities' && count($segments) === 2 && $method === 'GET') {
        $entityCode = $segments[1];

        // Check read permission
        if (!Auth::hasPermission($entityCode, 'READ')) {
            // Allow guest access to specific entities
            $publicEntities = ['item', 'catalog', 'category', 'organization'];
            if (!in_array($entityCode, $publicEntities)) {
                Response::forbidden('You do not have permission to view this entity');
            }
        }

        $controller = new EntityController($entityCode);
        $controller->list($request);
    }

    // Route: /api/entities/{entity_code}/{id} (get single record)
    if ($segments[0] === 'entities' && count($segments) === 3 && $method === 'GET' && $segments[2] !== 'metadata') {
        $entityCode = $segments[1];
        $id = $segments[2];

        // Check read permission
        if (!Auth::hasPermission($entityCode, 'READ')) {
            $publicEntities = ['item', 'catalog', 'category', 'organization'];
            if (!in_array($entityCode, $publicEntities)) {
                Response::forbidden('You do not have permission to view this entity');
            }
        }

        $controller = new EntityController($entityCode);
        $controller->get($id);
    }

    // Route: /api/entities/{entity_code} (create record)
    if ($segments[0] === 'entities' && count($segments) === 2 && $method === 'POST') {
        if (!Auth::isLoggedIn()) {
            Response::unauthorized('You must be logged in to create records');
        }

        $entityCode = $segments[1];

        // Check create permission
        if (!Auth::hasPermission($entityCode, 'CREATE')) {
            Response::forbidden('You do not have permission to create records for this entity');
        }

        $controller = new EntityController($entityCode);
        $controller->create($request);
    }

    // Route: /api/entities/{entity_code}/{id} (update record)
    if ($segments[0] === 'entities' && count($segments) === 3 && in_array($method, ['PUT', 'PATCH'])) {
        if (!Auth::isLoggedIn()) {
            Response::unauthorized('You must be logged in to update records');
        }

        $entityCode = $segments[1];
        $id = $segments[2];

        // Check update permission
        if (!Auth::hasPermission($entityCode, 'UPDATE')) {
            Response::forbidden('You do not have permission to update records for this entity');
        }

        $controller = new EntityController($entityCode);
        $controller->update($id, $request);
    }

    // Route: /api/entities/{entity_code}/{id} (delete record)
    if ($segments[0] === 'entities' && count($segments) === 3 && $method === 'DELETE') {
        if (!Auth::isLoggedIn()) {
            Response::unauthorized('You must be logged in to delete records');
        }

        $entityCode = $segments[1];
        $id = $segments[2];

        // Check delete permission
        if (!Auth::hasPermission($entityCode, 'DELETE')) {
            Response::forbidden('You do not have permission to delete records for this entity');
        }

        $controller = new EntityController($entityCode);
        $controller->delete($id);
    }

    // No route matched
    Response::notFound('API endpoint not found');

} catch (\Exception $e) {
    if (IS_DEVELOPMENT) {
        Response::serverError($e->getMessage() . "\n" . $e->getTraceAsString());
    } else {
        Response::serverError('An error occurred while processing your request');
    }
}
