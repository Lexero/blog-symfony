<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\UserRoleTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreatorService
{
    private UserPasswordHasherInterface $userPasswordHasher;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }

    public function registerUser($user, $form): void
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );

        $user->setRoles([UserRoleTypeEnum::ROLE_READER]);
        $user->setConfirmationCode(Uuid::uuid6()->toString());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}