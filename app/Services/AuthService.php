<?php

require_once __DIR__ . '/../Enums/Permission.php';
require_once __DIR__ . '/../Enums/Role.php';
require_once __DIR__ . '/../Models/User.php';

class AuthService
{
    private User $userModel;
    private RateLimiter $limiter;

    public function __construct(User $userModel, RateLimiter $limiter)
    {
        $this->userModel = $userModel;
        $this->limiter = $limiter;
    }

        public function login(
        string $username,
        string $password,
        string $ip
    ): bool {
        if ($this->lockedFor($username, $ip) > 0) {
            return false;
        }

        $user = $this->userModel->findByUsername(trim($username));

        $hash = is_string($user['password_hash'] ?? null)
            ? $user['password_hash']
            : null;

        if ($hash === null) {
            // Spend the same time as a real check, so the response
            // time doesn't reveal whether the username exists.
            password_hash($password, PASSWORD_DEFAULT);
            $passwordOk = false;
        } else {
            $passwordOk = password_verify($password, $hash);
        }

        $accountOk = $user !== null
            && isset($user['id'], $user['username'], $user['role'])
            && ($user['is_active'] ?? false) === true
            && Role::tryFrom((string) $user['role']) !== null;

        if (!$passwordOk || !$accountOk) {
            $this->limiter->hit($this->userKey($username, $ip), $this->lockSeconds());
            $this->limiter->hit($this->ipKey($ip), $this->lockSeconds());

            error_log('Failed login from IP ' . $ip);

            return false;
        }

        $this->limiter->clear($this->userKey($username, $ip));

        session_regenerate_id(true);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        return true;
    }

    public function lockedFor(string $username, string $ip): int
    {
        $max = (int) ($_ENV['LOGIN_MAX_ATTEMPTS'] ?? 5);
        $userKey = $this->userKey($username, $ip);
        $ipKey = $this->ipKey($ip);
        $seconds = 0;

        if ($this->limiter->tooManyAttempts($userKey, $max)) {
            $seconds = $this->limiter->availableIn($userKey);
        }

        if ($this->limiter->tooManyAttempts($ipKey, $max * 4)) {
            $seconds = max($seconds, $this->limiter->availableIn($ipKey));
        }

        return $seconds;
    }

    private function userKey(string $username, string $ip): string
    {
        return 'login:' . mb_strtolower(trim($username)) . '|' . $ip;
    }

    private function ipKey(string $ip): string
    {
        return 'login-ip:' . $ip;
    }

    private function lockSeconds(): int
    {
        return (int) ($_ENV['LOGIN_LOCK_SECONDS'] ?? 900);
    }

    public function logout(): void
    {
        Session::destroy();
    }
}
