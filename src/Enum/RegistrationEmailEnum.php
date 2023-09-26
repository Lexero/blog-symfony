<?php

declare(strict_types=1);

namespace App\Enum;

enum RegistrationEmailEnum: string
{
    case SENDER_ADDRESS = 'blog@your.com';
    case SENDER_NAME = 'Blog Admin';
    case EMAIL_TEXT = 'Hello! Please confirm your email';
}
