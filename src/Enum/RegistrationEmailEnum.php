<?php

declare(strict_types=1);

namespace App\Enum;

class RegistrationEmailEnum
{
    public const SENDER_ADDRESS = 'mailer@your.com';
    public const SENDER_NAME    = 'Blog Admin';
    public const EMAIL_TEXT     = 'Hello! Please confirm your email';

    public static function getValues(): array
    {
        return [
            self::SENDER_ADDRESS => self::SENDER_ADDRESS,
            self::SENDER_NAME    => self::SENDER_NAME,
            self::EMAIL_TEXT     => self::EMAIL_TEXT,
        ];
    }

}