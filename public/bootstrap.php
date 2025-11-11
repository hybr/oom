<?php
/**
 * Application Bootstrap
 *
 * Initializes the application for web pages
 */

require_once __DIR__ . '/../config/config.php';
require_once LIB_PATH . '/core/Autoloader.php';

use V4L\Core\Autoloader;
use V4L\Core\Auth;
use V4L\Core\Database;

// Initialize autoloader
Autoloader::register();

// Start session
Auth::startSession();

// Initialize database (creates connection)
try {
    Database::getConnection();
} catch (\Exception $e) {
    die('Database connection failed. Please check configuration.');
}

/**
 * Helper function to render template
 */
function render(string $template, array $data = []): void
{
    extract($data);
    $templateFile = TEMPLATE_PATH . '/' . $template . '.php';

    if (!file_exists($templateFile)) {
        die("Template not found: $template");
    }

    include $templateFile;
}

/**
 * Helper function to include partial template
 */
function partial(string $template, array $data = []): void
{
    extract($data);
    $templateFile = TEMPLATE_PATH . '/' . $template . '.php';

    if (!file_exists($templateFile)) {
        echo "<!-- Partial not found: $template -->";
        return;
    }

    include $templateFile;
}

/**
 * Helper function to escape HTML
 */
function e($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Helper function to get asset URL
 */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Helper function to generate URL
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Helper function to redirect
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Helper function to get flash message
 */
function flash(string $key, string $value = null)
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
    } else {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}

/**
 * Helper function to format date
 */
function formatDate($date, string $format = 'Y-m-d H:i:s'): string
{
    if (!$date) return '';
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

/**
 * Helper function to truncate text
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Helper function to check if current page is active
 */
function isActivePage(string $page): bool
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    return $currentPage === $page;
}
