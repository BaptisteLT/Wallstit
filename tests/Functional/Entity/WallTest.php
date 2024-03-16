<?php

namespace App\Tests\Functional\Entity;

use App\Entity\User;
use App\Entity\Wall;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WallTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        // Boot the Symfony kernel for the test environment
        //self::bootKernel();

        // Get the entity manager from the container
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    function testCreateAndUpdate()
    {
        $wall = new Wall();
        $user = new User();
        $user->setName('Test user');
        $user->setOAuth2Provider('google');
        $user->setOAuth2ProviderId('1fa2c3f4r5r6f7e8e9r79');

        $wall->setName('My Wall');
        $wall->setUser($user);
        $wall->setDescription('My Description.');
        $wall->setBackground('bricks');


        $this->em->persist($wall);
        $this->em->persist($user);
        $this->em->flush();
        $this->assertInstanceOf(DateTimeImmutable::class, $wall->getCreatedAt());
        
        $wall->setName('My Wall Edited');
        $this->em->persist($wall);
        $this->em->flush();
        $this->assertInstanceOf(DateTimeImmutable::class, $wall->getUpdatedAt());
    }
}
