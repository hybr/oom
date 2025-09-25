<?php

echo "=== PHP Extension Checker ===\n\n";

$requiredExtensions = [
    'pdo' => 'Database operations',
    'pdo_sqlite' => 'SQLite database support',
    'json' => 'JSON encoding/decoding',
];

$optionalExtensions = [
    'sockets' => 'WebSocket server functionality',
    'curl' => 'HTTP client requests',
    'mbstring' => 'Multi-byte string functions',
    'openssl' => 'Encryption and secure connections',
];

echo "Required Extensions:\n";
echo str_repeat('-', 30) . "\n";
$allRequired = true;
foreach ($requiredExtensions as $ext => $desc) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? "✓ INSTALLED" : "✗ MISSING";
    echo sprintf("%-15s %s (%s)\n", $ext, $status, $desc);
    if (!$loaded) $allRequired = false;
}

echo "\nOptional Extensions:\n";
echo str_repeat('-', 30) . "\n";
foreach ($optionalExtensions as $ext => $desc) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? "✓ INSTALLED" : "- NOT INSTALLED";
    echo sprintf("%-15s %s (%s)\n", $ext, $status, $desc);
}

echo "\nPHP Version: " . PHP_VERSION . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";

if (!$allRequired) {
    echo "\n❌ CRITICAL: Some required extensions are missing!\n";
    echo "Please install the missing extensions to run the application.\n\n";

    echo "Windows Installation Guide:\n";
    echo "1. Download PHP extensions from https://windows.php.net/downloads/pecl/releases/\n";
    echo "2. Or use package manager like Chocolatey or XAMPP\n";
    echo "3. Edit php.ini and uncomment extension lines\n";
    echo "4. Restart your web server\n\n";

    exit(1);
} else {
    echo "\n✅ All required extensions are installed!\n";

    if (!extension_loaded('sockets')) {
        echo "\n⚠️  Note: 'sockets' extension is not installed.\n";
        echo "WebSocket server will use file-based polling instead.\n";
        echo "For better performance, consider installing the sockets extension.\n";
    }
}

echo "\nSystem is ready to run!\n";