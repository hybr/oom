<?php

return [
    'host' => getenv('WEBSOCKET_HOST') ?: 'localhost',
    'port' => (int) getenv('WEBSOCKET_PORT') ?: 8080,
    'enabled' => true,

    'events' => [
        'entity_created',
        'entity_updated',
        'entity_deleted',
        'notification',
        'user_presence',
        'system_alert',
    ],
];
