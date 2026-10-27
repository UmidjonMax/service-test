
<?php

require_once __DIR__ . '/../Models/User.php';

class AuthService
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login(
        string $username,
        string $password
    ): bool {

        $user = $this->userModel->findByUsername(
            trim($username)
        );

        if ($user === null) {
            return false;
        }

        if (
            !isset(
                $user['is_active'],
                $user['password_hash'],
                $user['id'],
                $user['username'],
                $user['role']
            )
            || $user['is_active'] !== true
        ) {
            return false;
        }

        if (!password_verify(
            $password,
            $user['password_hash']
        )) {
            return false;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        return true;
    }

    public function logout(): void
    {
        Session::destroy();
    }
}
