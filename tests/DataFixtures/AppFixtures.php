<?php

namespace App\Tests\DataFixtures;

use App\Entity\BlogPost;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private \Faker\Generator $faker;

    private SlugifyInterface $slug;

    public function __construct(SlugifyInterface $slugify)
    {
        $this->faker = Factory::create();
        $this->slug = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadPost($manager);
    }

    public function loadPost(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $post = new BlogPost();
            $post->setTitle($this->faker->text(100));
            $post->setSlug($this->slug->slugify($post->getTitle()));
            $post->setBody($this->faker->text(1000));
            $post->setCreatedAt($this->faker->dateTime);
            $post->setAuthor('Alex');

            $manager->persist($post);
        }
        $manager->flush();
    }
}
