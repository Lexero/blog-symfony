<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\RegistrationEmailEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserVerifier
{
    private EntityManagerInterface $entityManager;

    private TokenStorageInterface $tokenStorage;

    private MailerInterface $mailer;

    private RouterInterface $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface  $tokenStorage,
        MailerInterface        $mailer,
        RouterInterface        $router
    )
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function verifyUser($user): void
    {
        $user->setVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = new UsernamePasswordToken($user, "main", $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    public function sendVerificationEmailToUser($user): void
    {
        $email = new TemplatedEmail();
        $email->from(new Address(RegistrationEmailEnum::SENDER_ADDRESS, RegistrationEmailEnum::SENDER_NAME));
        $email->to($user->getEmail());
        $email->htmlTemplate('login/confirmation_email.html.twig');
        $email->subject(RegistrationEmailEnum::EMAIL_TEXT);
        $email->context([
            'signedUrl' => $this->router->generate(
                "verify_email",
                ["confirmationCode" => $user->getConfirmationCode()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'expiration_date' => new \DateTime('+7 days'),
            'name' => $user->getName()
        ]);

        $this->mailer->send($email);
    }
}