<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadAdmin($manager);
    }

    public function loadAdmin(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@your.com');
        $admin->setName('Alex');
        $admin->setRoles(['ROLE_WRITER']);
        $admin->setPassword('$2y$13$8KGfu/2v7n84rnCPijENKOpvpRaeKQXOnZeDYlMLXbt1rFuV1rm8S');
        $admin->setVerified(true);

        $manager->persist($admin);
        $manager->flush();
        $this->setReference('admin', $admin);
    }
}
