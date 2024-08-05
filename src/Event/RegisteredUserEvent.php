<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;

class RegisteredUserEvent
{
    public const NAME = 'user.register';

    public function __construct(private readonly User $user)
    {
    }

    public function getRegisteredUser(): User
    {
        return $this->user;
    }
}
