<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Tokens\RefreshToken;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RefreshTokenFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $refreshToken = new RefreshToken();
        $refreshToken->setValue('some_refresh_some');
        $refreshToken->setUser($this->getReference('User_1'));
        $refreshToken->setExpiresAt(new DateTimeImmutable());

        $manager->persist($refreshToken);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
