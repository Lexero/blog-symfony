<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use MyBuilder\Bundle\CronosBundle\Annotation\Cron;
use Random\RandomException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/** @Cron(minute="/2", hour="*", noLogs=true) */
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserManager                 $userManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Create a test user.')
            ->addArgument('email', InputArgument::OPTIONAL, 'User email')
            ->addArgument('password', InputArgument::OPTIONAL, 'User password')
            ->addArgument('name', InputArgument::OPTIONAL, 'Username');
    }

    /**
     * @throws RandomException
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email') ?? 'user' . rand(1000, 9999) . '@example.com';
        $password = $input->getArgument('password') ?? bin2hex(random_bytes(8));
        $name = $input->getArgument('name') ?? 'User' . rand(1000, 9999);

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

        $this->userManager->recordEvent(
            'Admin',
            'Event success'
        );

        return Command::SUCCESS;
    }
}
