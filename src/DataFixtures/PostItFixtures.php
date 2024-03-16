<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\PostIt;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostItFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        //Création de 5 Post-its
        for($i=0; $i<5; $i++)
        {
            $postIt = new PostIt();

            $postIt->setColor($this->faker->randomElement(['yellow', 'green', 'orange', 'blue', 'pink']));
            $postIt->setTitle($this->faker->text(40));
            $postIt->setContent($this->faker->text(80));
            $postIt->setPositionX($this->faker->numberBetween(0, 3500));
            $postIt->setPositionY($this->faker->numberBetween(0, 2000));
            $postIt->setWall($this->getReference('Wall_1'));
            $postIt->setSize($this->faker->randomElement(['small', 'medium', 'large']));
            $postIt->setDeadline(new DateTimeImmutable());
            $manager->persist($postIt);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            WallFixtures::class,
        ];
    }
}
