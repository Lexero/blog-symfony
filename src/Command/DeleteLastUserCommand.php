<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MyBuilder\Bundle\CronosBundle\Annotation\Cron;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @Cron(minute="/3", hour="*", noLogs=true) */
final class DeleteLastUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository         $userRepository,
        private readonly LoggerInterface        $logger
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->setName('app:delete-last-user')
            ->setDescription('Delete last user in database.')
            ->setHelp(<<<'HELP'
                The <info>%command.name%</info> command deletes the last user from the database.
                HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var User|null $user */
        $user = $this->userRepository->findOneBy([], ['id' => 'DESC']);

        if (null === $user) {
            $output->writeln('<error>No user found.</error>');
            return Command::FAILURE;
        }

        $userId = $user->getId();
        $userName = $user->getName();
        $userEmail = $user->getEmail();

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $output->writeln(
            sprintf('User "%s" (ID: %d, email: %s) was successfully deleted.',
                $userName,
                $userId,
                $userEmail
            )
        );
        $this->logger->info(
            'User "{name}" (ID: {id}, email: {email}) was successfully deleted.',
            ['name' => $userName, 'id' => $userId, 'email' => $userEmail]
        );

        return Command::SUCCESS;
    }
}
