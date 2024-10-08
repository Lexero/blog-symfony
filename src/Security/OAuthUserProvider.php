<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function loadUserByOAuthUserResponse($response)
    {
        $email = $response->getEmail();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null) {
            $user = new User();
            $user->setEmail($email);
            $user->setName($response->getNickname() ?? $email);
            $user->setVerified(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } elseif (!$user->isVerified()) {
            $user->setVerified(true);
        }

        return $user;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $identifier]);

        if ($user === null) {
            throw new UserNotFoundException(sprintf('User with email "%s" not found.', $identifier));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === OAuthUser::class || $class === 'HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser';
    }
}
