<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;

class RegisteredUserEvent
{
    public const NAME = 'user.register';

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getRegisteredUser(): User
    {
        return $this->user;
    }
}
