<?php

require_once __DIR__ . '/../app/bootstrap.php';      // public/*.php
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Services/AuthService.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

Session::start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($csrfToken)
        || !Security::verifyCsrfToken($csrfToken)
    ) {
        $error = 'So‘rov muddati tugagan yoki noto‘g‘ri. Sahifani yangilang.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!is_string($password) || $username === '' || $password === '') {
            $error = 'Username va password kiriting.';
        } else {
            try {
                $authService = new AuthService(new User(), new RateLimiter());
                $authController = new AuthController($authService);

                $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
                $lockedFor = $authController->lockedFor($username, $ip);

                if ($lockedFor > 0) {
                    http_response_code(429);
                    $error = sprintf(
                        'Juda ko‘p urinish. %d daqiqadan keyin qayta urinib ko‘ring.',
                        (int) ceil($lockedFor / 60)
                    );
                } elseif ($authController->login($username, $password, $ip)) {
                    header('Location: ' . url('dashboard.php'));
                    exit;
                } else {
                    $error = 'Username yoki password noto‘g‘ri.';
                }
            } catch (Throwable $e) {
                error_log('Login error: ' . $e->getMessage());
                http_response_code(500);
                $error = 'Tizimda xatolik yuz berdi. Keyinroq qayta urinib ko‘ring.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Card System</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>

<div class="login-page">
    <div class="login-card">

        <div class="login-header">
            <h1>Card System</h1>
            <p>Sign in to your account</p>
        </div>

        <?php if ($error !== ''): ?>
            <p class="alert alert-error">
                <?= Security::escape($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= Security::csrfToken() ?>"
            >

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="login-button">
                Sign in
            </button>
        </form>

    </div>
</div>

</body>
</html>
