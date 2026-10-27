<?php

class Security
{
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }

        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(
        string $token
    ): bool {
        return isset($_SESSION['csrf_token'])
            && hash_equals(
                $_SESSION['csrf_token'],
                $token
            );
    }

    public static function escape(
        ?string $value
    ): string {
        return htmlspecialchars(
            $value ?? '',
            ENT_QUOTES,
            'UTF-8'
        );
    }
}