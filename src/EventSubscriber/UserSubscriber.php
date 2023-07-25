<?php

namespace App\EventSubscriber;

use App\Event\RegisteredUserEvent;
use App\Service\UserVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{

    private UserVerifier $userVerifier;

    public function __construct(UserVerifier $userVerifier)
    {
        $this->userVerifier = $userVerifier;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RegisteredUserEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(RegisteredUserEvent $registeredUserEvent): void
    {
        $this->userVerifier->sendVerificationEmailToUser($registeredUserEvent->getRegisteredUser());
    }
}
