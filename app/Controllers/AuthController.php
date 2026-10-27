
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
        string $password
    ): bool {
        return $this->authService->login(
            $username,
            $password
        );
    }

    public function logout(): void
    {
        $this->authService->logout();
    }
}
