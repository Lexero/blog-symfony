<?php

declare(strict_types=1);

namespace App\Tests\System;

use App\Kernel;
use App\Repository\UserRepository;
use App\Tests\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebTestCase extends SymfonyTestCase
{
    private const ADMIN_FIREWALL = 'admin';

    protected function setUp(): void
    {
        static::bootKernel();
    }

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    //Copy-paste from previous version. Current one don't allow load kernel many times
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        try {
            $client = self::$kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException) {
            if (class_exists(KernelBrowser::class)) {
                throw new LogicException('You cannot create the client used in functional tests if the "framework.test" config is not set to true.');
            }
            throw new LogicException('You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit".');
        }

        $client->setServerParameters($server);

        return $client;
    }

    /** @throws Exception */
    protected function createAuthenticatedAdminClient(string $email): KernelBrowser
    {
        $user = $this->getRepository()->findOneBy(['email' => $email]);

        if ($user === null) {
            throw new NotFoundHttpException(sprintf('Not found user with email "%s".', $email));
        }

        return $this->createClient()->loginUser($user, self::ADMIN_FIREWALL);
    }

    /** @throws Exception */
    private function getRepository(): ServiceEntityRepository
    {
        self::bootKernel();

        return static::getContainer()->get(UserRepository::class);
    }
}
