<?php
/**
 * Authentication Required
 * Include this file at the top of any page that requires authentication
 */

if (!isset($BOOTSTRAP_LOADED)) {
    require_once __DIR__ . '/../bootstrap.php';
}

\App\Auth::require();
