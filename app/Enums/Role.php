<?php

declare(strict_types=1);

enum Role: string
{
    case Admin = 'ADMIN';
    case Operator = 'OPERATOR';
    case Viewer = 'VIEWER';

    public function label(): string
    {
        return match ($this) {
            Role::Admin => 'Administrator',
            Role::Operator => 'Operator',
            Role::Viewer => 'Viewer',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            Role::Admin => Permission::cases(),
            Role::Operator => [
                Permission::ViewCards,
                Permission::ViewTransactions,
                Permission::BlockCard,
            ],
            Role::Viewer => [
                Permission::ViewCards,
                Permission::ViewTransactions,
            ],
        };
    }

    public function can(Permission $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }
}