<?php
/**
 * Security Configuration and Headers
 * Centralized security settings for the application
 */

// Security Headers Function
function setSecurityHeaders() {
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // Prevent clickjacking attacks
    header('X-Frame-Options: DENY');

    // XSS Protection (legacy header but still useful)
    header('X-XSS-Protection: 1; mode=block');

    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Content Security Policy (CSP)
    $csp = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net",
        "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
        "img-src 'self' data: https:",
        "font-src 'self' https://cdn.jsdelivr.net",
        "connect-src 'self'",
        "frame-src 'none'",
        "object-src 'none'",
        "base-uri 'self'"
    ];
    header('Content-Security-Policy: ' . implode('; ', $csp));

    // HSTS for HTTPS (only if on HTTPS)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }

    // Remove server signature
    header_remove('X-Powered-By');
    header_remove('Server');
}

// Input Sanitization Functions
function sanitizeInput($input, $type = 'string') {
    if (is_null($input)) {
        return null;
    }

    switch ($type) {
        case 'string':
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        case 'email':
            return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        case 'float':
            return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        case 'url':
            return filter_var(trim($input), FILTER_SANITIZE_URL);
        case 'filename':
            return preg_replace('/[^a-zA-Z0-9._-]/', '', $input);
        default:
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

// CSRF Token Functions
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function getCSRFTokenInput() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

// Rate Limiting (Simple file-based implementation)
function checkRateLimit($identifier, $maxRequests = 60, $timeWindow = 3600) {
    $rateLimitFile = sys_get_temp_dir() . '/rate_limit_' . md5($identifier);

    $requests = [];
    if (file_exists($rateLimitFile)) {
        $requests = json_decode(file_get_contents($rateLimitFile), true) ?: [];
    }

    $now = time();

    // Remove old requests outside the time window
    $requests = array_filter($requests, function($timestamp) use ($now, $timeWindow) {
        return ($now - $timestamp) < $timeWindow;
    });

    // Check if limit exceeded
    if (count($requests) >= $maxRequests) {
        return false;
    }

    // Add current request
    $requests[] = $now;

    // Save updated requests
    file_put_contents($rateLimitFile, json_encode($requests));

    return true;
}

// File Upload Security
function validateFileUpload($file, $allowedTypes = [], $maxSize = 2097152) { // 2MB default
    $errors = [];

    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $errors[] = 'No file uploaded or upload error';
        return $errors;
    }

    // Check file size
    if ($file['size'] > $maxSize) {
        $errors[] = 'File size exceeds maximum allowed size';
    }

    // Check MIME type
    if (!empty($allowedTypes)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = 'File type not allowed';
        }
    }

    // Check for potential malicious content
    $content = file_get_contents($file['tmp_name']);
    $maliciousPatterns = [
        '/<\?php/',
        '/<script/',
        '/javascript:/',
        '/vbscript:/',
        '/onload\s*=/i',
        '/onclick\s*=/i',
        '/onerror\s*=/i'
    ];

    foreach ($maliciousPatterns as $pattern) {
        if (preg_match($pattern, $content)) {
            $errors[] = 'File contains potentially malicious content';
            break;
        }
    }

    return $errors;
}

// Path Traversal Prevention
function securePath($path, $baseDir = null) {
    // Remove any directory traversal attempts
    $path = str_replace(['../', '..\\', '../\\', '..\\/', '..'], '', $path);

    // Remove null bytes
    $path = str_replace("\0", '', $path);

    // If base directory is provided, ensure path is within it
    if ($baseDir) {
        $realPath = realpath($baseDir . '/' . $path);
        $realBaseDir = realpath($baseDir);

        if (!$realPath || strpos($realPath, $realBaseDir) !== 0) {
            return false;
        }

        return $realPath;
    }

    return $path;
}

// SQL Injection Prevention Helper
function preparePlaceholders($count) {
    return implode(',', array_fill(0, $count, '?'));
}

// Logging Security Events
function logSecurityEvent($event, $details = [], $severity = 'info') {
    $logFile = __DIR__ . '/../logs/security.log';
    $logDir = dirname($logFile);

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $logEntry = [
        'timestamp' => $timestamp,
        'severity' => $severity,
        'event' => $event,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'details' => $details
    ];

    $logLine = json_encode($logEntry) . "\n";
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}

// Environment-specific security settings
function setEnvironmentSecurity() {
    // Determine environment
    $isProduction = isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'production';

    if ($isProduction) {
        // Production settings
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
    } else {
        // Development settings
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    // Always secure settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
}

// Initialize security when this file is included
setSecurityHeaders();
setEnvironmentSecurity();
?>