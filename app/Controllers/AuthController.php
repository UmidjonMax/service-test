<?php

require_once __DIR__ . '/../Services/AuthService.php';

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(
        string $username,
        string $password,
        string $ip
    ): bool {
        return $this->authService->login($username, $password, $ip);
    }

    public function lockedFor(string $username, string $ip): int
    {
        return $this->authService->lockedFor($username, $ip);
    }

    public function logout(): void
    {
        $this->authService->logout();
    }
}
