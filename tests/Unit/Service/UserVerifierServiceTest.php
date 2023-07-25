<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Service\UserVerifier;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserVerifierServiceTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testVerifyUser()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $mailer = $this->createMock(MailerInterface::class);
        $router = $this->createMock(RouterInterface::class);

        $userVerifierService = new UserVerifier($entityManager, $tokenStorage, $mailer, $router);
        $user = $this->createMock(User::class);

        $user->expects($this->once())->method('setVerified')->with(true);
        $entityManager->expects($this->once())->method('persist')->with($user);
        $entityManager->expects($this->once())->method('flush');

        $user->method('getRoles')->willReturn(['ROLE_USER']);
        $tokenStorage->expects($this->once())->method('setToken')->with($this->isInstanceOf(UsernamePasswordToken::class));

        $userVerifierService->verifyUser($user);
    }

    /**
     * @throws Exception
     */
    public function testSendVerificationEmailToUser()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $mailer = $this->createMock(MailerInterface::class);
        $router = $this->createMock(RouterInterface::class);

        $userVerifierService = new UserVerifier($entityManager, $tokenStorage, $mailer, $router);
        $user = $this->createMock(User::class);

        $user->method('getEmail')->willReturn('test@example.com');
        $user->method('getConfirmationCode')->willReturn('code');
        $user->method('getName')->willReturn('John');

        $mailer->expects($this->once())->method('send')->with($this->isInstanceOf(TemplatedEmail::class));

        $router->method('generate')->willReturn('url');

        $userVerifierService->sendVerificationEmailToUser($user);
    }
}
