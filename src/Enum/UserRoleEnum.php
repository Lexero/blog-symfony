<?php

declare(strict_types=1);

namespace App\Enum;

class UserRoleEnum
{
    public const ROLE_USER   = 'ROLE_USER';
    public const ROLE_ADMIN  = 'ROLE_ADMIN';
    public const ROLE_READER = 'ROLE_READER';
    public const ROLE_WRITER = 'ROLE_WRITER';

    public static function getValues(): array
    {
        return [
            self::ROLE_USER   => self::ROLE_USER,
            self::ROLE_ADMIN  => self::ROLE_ADMIN,
            self::ROLE_READER => self::ROLE_READER,
            self::ROLE_WRITER => self::ROLE_WRITER,
        ];
    }

    public static function isValid(string $role): bool
    {
        return in_array($role, self::getValues());
    }
}
