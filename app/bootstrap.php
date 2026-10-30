<?php

declare(strict_types=1);

// Load .env. Real server environment variables take priority over the file.
$envFile = __DIR__ . '/../.env';

if (is_file($envFile)) {
    $values = parse_ini_file($envFile, false, INI_SCANNER_RAW);

    if ($values !== false) {
        $_ENV = array_merge($values, $_ENV);
    }
}

require_once __DIR__ . '/Helpers/Session.php';
require_once __DIR__ . '/Helpers/Security.php';
require_once __DIR__ . '/Helpers/RateLimiter.php';
require_once __DIR__ . '/Helpers/Format.php';
require_once __DIR__ . '/Enums/Permission.php';
require_once __DIR__ . '/Enums/Role.php';
require_once __DIR__ . '/Helpers/Auth.php';

function url(string $path = ''): string
{
    return rtrim($_ENV['BASE_URL'] ?? '', '/') . '/' . ltrim($path, '/');
}

Security::sendHeaders();