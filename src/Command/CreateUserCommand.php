<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-user',
    description: 'Creates new user and stores in the database'
)]
class CreateUserCommand extends Command
{
    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp(<<<'HELP'
            The <info>%command.name%</info> command creates new user and saves it in the database:

              <info>php %command.full_name%</info> 
              <info>Email: </info>
              <info>Password: </info>
              <info>Name: </info>
            HELP);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');

        $email = $questionHelper->ask($input, $output, new Question('<info>Email: </info>'));
        $password = $questionHelper->ask($input, $output, new Question('<info>Password: </info>'));
        $name = $questionHelper->ask($input, $output, new Question('<info>Name: </info>'));

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln(
            sprintf('<comment>User created successfully! Email - %s, Name - %s</comment>',
                $email, $name
            )
        );

        return Command::SUCCESS;
    }
}
