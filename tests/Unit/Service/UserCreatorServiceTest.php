<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\UserCreator;

class UserCreatorServiceTest extends WebTestCase
{

    /**
     * @throws Exception
     */
    public function testValidateUserFields(): void
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $form = $this->createMock(FormInterface::class);
        $user = $this->createMock(User::class);

        $password = 'password';
        $hashedPassword = 'hashedPassword';

        $form->method('get')->with('password')->willReturn($form);
        $form->method('getData')->willReturn($password);

        $passwordHasher->expects($this->once())->method('hashPassword')->with($user, $password)->willReturn($hashedPassword);
        $entityManager->expects($this->once())->method('persist')->with($user);
        $entityManager->expects($this->once())->method('flush');

        $user->expects($this->once())->method('setPassword')->with($hashedPassword);
        $user->expects($this->once())->method('setRoles')->with([UserRoleEnum::ROLE_READER]);
        $user->expects($this->once())->method('setConfirmationCode')->with($this->isType('string'));

        $userCreatorService = new UserCreator($passwordHasher, $entityManager);
        $userCreatorService->registrationUser($user, $form);
    }
}
