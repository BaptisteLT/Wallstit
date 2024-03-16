<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
                
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setName('Test user');
        $user->setPicture('https://website/picture_url.fr');
        $user->setLocale('fr');
        $user->setOAuth2Provider('google');
        $user->setOAuth2ProviderId('89748948918919');
        $manager->persist($user);
        $manager->flush();

        $this->addReference('User_1', $user);
    }
}
