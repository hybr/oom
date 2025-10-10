<?php
/**
 * V4L Application Entry Point
 * Routes all requests through this file
 */

require_once __DIR__ . '/../bootstrap.php';

// Create router instance
$router = new Router();

// ============================================
// Public Routes
// ============================================
$router->get('/', function() {
    require __DIR__ . '/pages/home.php';
});

// ============================================
// Auth Routes
// ============================================
$router->get('/auth/login', function() {
    require __DIR__ . '/pages/auth/login.php';
});

$router->post('/auth/login-process', function() {
    require __DIR__ . '/pages/auth/login-process.php';
});

$router->get('/auth/signup', function() {
    require __DIR__ . '/pages/auth/signup.php';
});

$router->post('/auth/signup-process', function() {
    require __DIR__ . '/pages/auth/signup-process.php';
});

$router->get('/auth/logout', function() {
    require __DIR__ . '/pages/auth/logout.php';
});

// ============================================
// Dashboard
// ============================================
$router->get('/dashboard', function() {
    require __DIR__ . '/pages/dashboard.php';
});

// ============================================
// Market Routes
// ============================================
$router->get('/market/catalog', function() {
    require __DIR__ . '/pages/market/catalog.php';
});

$router->get('/market/jobs', function() {
    require __DIR__ . '/pages/market/jobs.php';
});

// ============================================
// Entity Routes (Auto-CRUD)
// ============================================

// List view
$router->get('/entities/:entity/list', function($entity) {
    require __DIR__ . '/pages/entities/list.php';
});

// Detail view
$router->get('/entities/:entity/detail/:id', function($entity, $id) {
    require __DIR__ . '/pages/entities/detail.php';
});

// Create form
$router->get('/entities/:entity/create', function($entity) {
    require __DIR__ . '/pages/entities/create.php';
});

// Store (create handler)
$router->post('/entities/:entity/store', function($entity) {
    require __DIR__ . '/pages/entities/store.php';
});

// Edit form
$router->get('/entities/:entity/edit/:id', function($entity, $id) {
    require __DIR__ . '/pages/entities/edit.php';
});

// Update handler
$router->post('/entities/:entity/update', function($entity) {
    require __DIR__ . '/pages/entities/update.php';
});

// Delete handler
$router->post('/entities/:entity/delete', function($entity) {
    require __DIR__ . '/pages/entities/delete.php';
});

// ============================================
// Dispatch the request
// ============================================
$router->dispatch();
