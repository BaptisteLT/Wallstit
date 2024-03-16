<?php

namespace App\DataFixtures;

use App\Entity\Wall;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WallFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $wall = new Wall();
        $wall->setName('My Wall');
        $wall->setUser($this->getReference('User_1'));
        $wall->setDescription('My Description.');
        $wall->setBackground('bricks');

        $manager->persist($wall);
        $manager->flush();

        $this->addReference('Wall_1', $wall);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
