<?php

return [
    'app_name' => $_ENV['APP_NAME'] ?? 'Card System',
    'environment' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => filter_var(
        $_ENV['APP_DEBUG'] ?? false,
        FILTER_VALIDATE_BOOLEAN
    ),

    'session_timeout' => (int) (
        $_ENV['SESSION_TIMEOUT'] ?? 1800
    ),
];