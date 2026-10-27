
<?php

require_once __DIR__ . '/../Helpers/Session.php';

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();

        if (empty($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }
}
