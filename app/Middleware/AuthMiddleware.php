<?php

require_once __DIR__ . '/../bootstrap.php';

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();

        if (empty($_SESSION['user_id'])) {
            header('Location: ' . url('login.php'));
            exit;
        }
    }

    public static function requirePermission(Permission $permission): void
    {
        self::handle();

        if (Auth::can($permission)) {
            return;
        }

        error_log(sprintf(
            'Access denied: user=%s role=%s permission=%s page=%s',
            $_SESSION['username'] ?? '?',
            $_SESSION['role'] ?? '?',
            $permission->name,
            $_SERVER['REQUEST_URI'] ?? '?'
        ));

        http_response_code(403);

        $pageTitle = 'Access denied';

        ob_start();
        require __DIR__ . '/../../views/errors/403.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../views/layouts/app.php';
        exit;
    }
}
