<?php

class Session
{
    public static function start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.use_strict_mode', '1');   // reject session IDs the server didn't create
        ini_set('session.use_only_cookies', '1');  // never accept a session ID from the URL
        ini_set('session.cache_limiter', '');

        session_set_cookie_params([
            'httponly' => true,
            'secure'   => self::isHttps(),
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    self::checkTimeout();
}

private static function checkTimeout(): void
{
    $timeout = (int) ($_ENV['SESSION_TIMEOUT'] ?? 1800);

    if (
        isset($_SESSION['last_activity'])
        && time() - $_SESSION['last_activity'] > $timeout
    ) {
        $_SESSION = [];                 // log the user out
        session_regenerate_id(true);    // but keep a working session
    }

    $_SESSION['last_activity'] = time();
}

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

public static function isHttps(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    public static function pullFlash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);

        return $message;
    }
}