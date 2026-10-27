<?php

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'secure' => false,
                'samesite' => 'Lax'
            ]);

            session_start();
        }

        self::checkTimeout();
    }

    private static function checkTimeout(): void
    {
        if (
            isset($_SESSION['last_activity'])
            && time() - $_SESSION['last_activity'] > 1800
        ) {
            self::destroy();
            return;
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
}