<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:delete-user',
    description: 'Deletes user from the database'
)]
final class DeleteUserCommand extends Command
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
            ->setHelp(<<<'HELP'
                The <info>%command.name%</info> command deletes users from the database:

                  <info>php %command.full_name%</info> <comment>username</comment>
                HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');

        /** @var string|null $email */
        $email = $questionHelper->ask($input, $output, new Question('<info>Email: </info>'));

        /** @var User|null $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (null === $user) {
            throw new RuntimeException(sprintf('User with email "%s" not found.', $email));
        }

        $userId = $user->getId();

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $userName = $user->getName();
        $userEmail = $user->getEmail();

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
