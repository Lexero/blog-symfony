<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Entity\BlogPost;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly Generator        $faker,
        private readonly SlugifyInterface $slug
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadPost($manager);
    }

    public function loadPost(ObjectManager $manager): void
    {
        $user = $this->getReference("admin");

        for ($i = 1; $i < 20; $i++) {
            $post = new BlogPost($user);
            $post->setTitle($this->faker->text(100));
            $post->setSlug($this->slug->slugify($post->getTitle()));
            $post->setBody($this->faker->text(1000));

            $manager->persist($post);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdminFixtures::class,
        ];
    }
}
