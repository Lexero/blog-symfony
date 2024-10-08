<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\RegisteredUserEvent;
use App\Service\UserVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserVerifier $userVerifier)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RegisteredUserEvent::NAME => 'onUserRegister'
        ];
    }

    /** @throws TransportExceptionInterface */
    public function onUserRegister(RegisteredUserEvent $registeredUserEvent): void
    {
        $this->userVerifier->sendVerificationEmailToUser($registeredUserEvent->getRegisteredUser());
    }
}
