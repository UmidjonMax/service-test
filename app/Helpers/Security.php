<?php

class Security
{
    public static function sendHeaders(): void
    {
        header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: same-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
        header('Cross-Origin-Opener-Policy: same-origin');

        header('Cache-Control: no-store, private');
        header('Pragma: no-cache');

        header_remove('X-Powered-By');

        if (Session::isHttps()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
    
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