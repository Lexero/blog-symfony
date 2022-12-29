<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserVerifierService
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
        $email->from(new Address('mailer@your.com', 'Blog Admin'));
        $email->to($user->getEmail());
        $email->htmlTemplate('registration/confirmation_email.html.twig');
        $email->subject("Hello! Please verify your email");
        $email->context([
            'signedUrl' => $this->router->generate(
                "app_verify_email",
                ["confirmationCode" => $user->getConfirmationCode()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        ]);

        $this->mailer->send($email);
    }
}