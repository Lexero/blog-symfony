<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command;

use App\Command\DeleteLastUserCommand;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\System\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DeleteLastUserCommandTest extends WebTestCase
{
    private const USER_ID = 1;
    private const EMAIL = 'test@your.com';
    private const USERNAME = 'Test User';

    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private LoggerInterface $logger;

    /** @throws Exception */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /** @throws Exception */
    public function testExecuteDeletesLastUserSuccessfully(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(self::USER_ID);
        $user->method('getEmail')->willReturn(self::EMAIL);
        $user->method('getName')->willReturn(self::USERNAME);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([], ['id' => 'DESC'])
            ->willReturn($user);

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($user);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with('User "{name}" (ID: {id}, email: {email}) was successfully deleted.', [
                'name' => self::USERNAME,
                'id' => self::USER_ID,
                'email' => self::EMAIL,
            ]);

        $command = new DeleteLastUserCommand($this->entityManager, $this->userRepository, $this->logger);
        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($application->find('app:delete-last-user'));

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this::assertStringContainsString(
            sprintf('User "%s" (ID: %d, email: %s) was successfully deleted.',
                self::USERNAME,
                self::USER_ID,
                self::EMAIL),
            $output
        );
    }
}
