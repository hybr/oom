<?php

return [
    'name' => getenv('APP_NAME') ?: 'V4L - Vocal 4 Local',
    'tagline' => 'Your Community, Your Marketplace.',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN),
    'url' => getenv('APP_URL') ?: 'http://localhost',
    'timezone' => 'UTC',

    'session' => [
        'lifetime' => (int) getenv('SESSION_LIFETIME') ?: 120,
        'secure' => filter_var(getenv('SESSION_SECURE'), FILTER_VALIDATE_BOOLEAN),
        'http_only' => filter_var(getenv('SESSION_HTTP_ONLY'), FILTER_VALIDATE_BOOLEAN),
        'name' => 'V4L_SESSION',
    ],

    'pagination' => [
        'default_per_page' => 25,
        'options' => [10, 25, 50, 100],
    ],
];
