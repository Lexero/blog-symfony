<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command;

use App\Command\CreateUserCommand;
use App\Command\UserManager;
use App\Entity\User;
use App\Tests\System\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommandTest extends WebTestCase
{
    private const EMAIL = 'test@your.com';
    private const PASSWORD = '123123';
    private const HASHED_PASSWORD = 'hashed123123';
    private const USERNAME = 'Test User';

    private EntityManagerInterface $entityManagerMock;
    private UserPasswordHasherInterface $passwordHasherMock;
    private CommandTester $commandTester;

    /** @throws Exception */
    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $this->userManagerMock = $this->createMock(UserManager::class);

        $command = new CreateUserCommand($this->entityManagerMock, $this->passwordHasherMock, $this->userManagerMock);

        $helperSet = new HelperSet([new QuestionHelper()]);
        $command->setHelperSet($helperSet);

        $this->commandTester = new CommandTester($command);
    }

    public function testExecute(): void
    {
        $this->passwordHasherMock
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn(self::HASHED_PASSWORD);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $this->commandTester->setInputs([self::EMAIL, self::PASSWORD, self::USERNAME]);
        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('User created successfully!', $output);
        $this->assertStringContainsString(self::EMAIL, $output);
        $this->assertStringContainsString(self::USERNAME, $output);
    }
}
