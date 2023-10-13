<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class VerificationUserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof User && !$user->isVerified()) {
            throw new LockedException();
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if ($user instanceof User && !$user->isVerified()) {
            throw new LockedException();
        }
    }
}
