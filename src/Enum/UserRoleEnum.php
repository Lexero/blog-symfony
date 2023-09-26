<?php

declare(strict_types=1);

namespace App\Enum;

enum UserRoleEnum: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_READER = 'ROLE_READER';
    case ROLE_WRITER = 'ROLE_WRITER';

    public static function isValid(UserRoleEnum $role): bool
    {
        return in_array($role, self::cases());
    }
}
