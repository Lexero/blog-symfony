<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\UserRoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserCreator
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface      $entityManager
    )
    {
    }

    public function registrationUser($user, $form): void
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );

        $user->setRoles([UserRoleEnum::ROLE_READER]);
        $user->setConfirmationCode(Uuid::uuid6()->toString());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
