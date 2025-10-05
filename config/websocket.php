<?php

return [
    'host' => $_ENV['WEBSOCKET_HOST'] ?? 'localhost',
    'port' => (int)($_ENV['WEBSOCKET_PORT'] ?? 8080),
    'enabled' => filter_var($_ENV['WEBSOCKET_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
];
