<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class VerificationUserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if ($user instanceof User && !$user->isVerified()) {
            throw new LockedException();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if ($user instanceof User && !$user->getEnabled()) {
            throw new AccountExpiredException("Account was disabled");
        }
    }
}