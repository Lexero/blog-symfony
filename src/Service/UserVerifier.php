<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\RegistrationEmailEnum;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

readonly class UserVerifier
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface  $tokenStorage,
        private MailerInterface        $mailer,
        private RouterInterface        $router
    )
    {
    }

    public function verifyUser($user): void
    {
        $user->setVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = new UsernamePasswordToken($user, "main", $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    /** @throws TransportExceptionInterface */
    public function sendVerificationEmailToUser($user): void
    {
        $email = new TemplatedEmail();
        $email->from(new Address(RegistrationEmailEnum::SENDER_ADDRESS->value, RegistrationEmailEnum::SENDER_NAME->value));
        $email->to($user->getEmail());
        $email->htmlTemplate('login/confirmation_email.html.twig');
        $email->subject(RegistrationEmailEnum::EMAIL_TEXT->value);
        $email->context([
            'signedUrl' => $this->router->generate(
                "app_verify_email",
                ["confirmationCode" => $user->getConfirmationCode()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'expiration_date' => new DateTime('+7 days'),
            'name' => $user->getName()
        ]);

        $this->mailer->send($email);
    }
}
