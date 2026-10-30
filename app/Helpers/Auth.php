<?php

declare(strict_types=1);

class Auth
{
    public static function role(): ?Role
    {
        $value = $_SESSION['role'] ?? null;

        return is_string($value) ? Role::tryFrom($value) : null;
    }

    public static function can(Permission $permission): bool
    {
        return self::role()?->can($permission) ?? false;
    }
}