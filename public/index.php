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

// Organization Vacancies (Marketplace - public view, protected actions)
$router->get('/organization_vacancies', function () {
    require __DIR__ . '/pages/organization/vacancies/list.php';
});

$router->get('/organization_vacancies/create', function () {
    require __DIR__ . '/pages/organization/vacancies/create.php';
}, [[\App\Auth::class, 'require']]);

$router->post('/organization_vacancies/store', function () {
    require __DIR__ . '/pages/organization/vacancies/store.php';
}, [[\App\Auth::class, 'require']]);

$router->get('/organization_vacancies/{id}', function ($params) {
    $_GET['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/detail.php';
});

$router->get('/organization_vacancies/{id}/edit', function ($params) {
    $_GET['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/edit.php';
}, [[\App\Auth::class, 'require']]);

$router->post('/organization_vacancies/{id}/update', function ($params) {
    $_POST['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/update.php';
}, [[\App\Auth::class, 'require']]);

$router->post('/organization_vacancies/{id}/delete', function ($params) {
    $_POST['id'] = $params['id'];
    require __DIR__ . '/pages/organization/vacancies/delete.php';
}, [[\App\Auth::class, 'require']]);

// Define public entities (marketplace catalog)
$publicEntities = ['catalog_categories'];

// Generic entity routes (for all entities)
$router->get('/{entity}', function ($params) use ($publicEntities) {
    $entity = $params['entity'];

    // Check if authentication is required
    if (!in_array($entity, $publicEntities)) {
        \App\Auth::require();
    }

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
    \App\Auth::require(); // All create actions require auth

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
    \App\Auth::require(); // All store actions require auth

    $file = __DIR__ . '/pages/entities/' . $entity . '/store.php';

    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "Entity not found: " . htmlspecialchars($entity);
    }
});

$router->get('/{entity}/{id}', function ($params) use ($publicEntities) {
    $entity = $params['entity'];
    $_GET['id'] = $params['id'];

    // Check if authentication is required
    if (!in_array($entity, $publicEntities)) {
        \App\Auth::require();
    }

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
    \App\Auth::require(); // All edit actions require auth

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
    \App\Auth::require(); // All update actions require auth

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
    \App\Auth::require(); // All delete actions require auth

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
