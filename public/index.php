<?php

/**
 * Main entry point for V4L application
 */

require_once __DIR__ . '/../bootstrap.php';

use App\Router;

// Create router instance
$router = new Router();

// Define routes

// Home / Dashboard
$router->get('/', function () {
    require __DIR__ . '/pages/dashboard.php';
});

// Authentication routes
$router->get('/login', function () {
    require __DIR__ . '/pages/auth/login.php';
});

$router->post('/login', function () {
    require __DIR__ . '/pages/auth/login-process.php';
});

$router->get('/signup', function () {
    require __DIR__ . '/pages/auth/signup.php';
});

$router->post('/signup', function () {
    require __DIR__ . '/pages/auth/signup-process.php';
});

$router->get('/logout', function () {
    require __DIR__ . '/pages/auth/logout.php';
});

// Organization Vacancies
$router->get('/organization_vacancies', function () {
    require __DIR__ . '/pages/organization/vacancies/list.php';
});

$router->get('/organization_vacancies/create', function () {
    require __DIR__ . '/pages/organization/vacancies/create.php';
});

$router->post('/organization_vacancies/store', function () {
    require __DIR__ . '/pages/organization/vacancies/store.php';
});

$router->get('/organization_vacancies/{id}', function ($params) {
    $_GET['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/detail.php';
});

$router->get('/organization_vacancies/{id}/edit', function ($params) {
    $_GET['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/edit.php';
});

$router->post('/organization_vacancies/{id}/update', function ($params) {
    $_POST['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/update.php';
});

$router->post('/organization_vacancies/{id}/delete', function ($params) {
    $_POST['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/delete.php';
});

// Generic entity routes (for all entities)
$router->get('/{entity}', function ($params) {
    $entity = $params['entity'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/list.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->get('/{entity}/create', function ($params) {
    $entity = $params['entity'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/create.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->post('/{entity}/store', function ($params) {
    $entity = $params['entity'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/store.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->get('/{entity}/{id}', function ($params) {
    $entity = $params['entity'];
    $_GET['id'] = $params['id'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/detail.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->get('/{entity}/{id}/edit', function ($params) {
    $entity = $params['entity'];
    $_GET['id'] = $params['id'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/edit.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->post('/{entity}/{id}/update', function ($params) {
    $entity = $params['entity'];
    $_POST['id'] = $params['id'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/update.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->post('/{entity}/{id}/delete', function ($params) {
    $entity = $params['entity'];
    $_POST['id'] = $params['id'];
    $file = __DIR__ . '/pages/entities/' . $entity . '/delete.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

// Dispatch the request
$router->dispatch();
