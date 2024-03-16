<?php

namespace App\Tests\Functional\Entity\Traits;

use App\Entity\User;
use App\Entity\Wall;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateUpdateTraitTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        // Boot the Symfony kernel for the test environment
        //self::bootKernel();

        // Get the entity manager from the container
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    public function testCreateAndUpdate()
    {
        /*Test createdAt*/
        $wall = $this->em->getRepository(Wall::class)->findOneBy(['name' => 'My Wall']);
        $this->assertInstanceOf(DateTimeImmutable::class, $wall->getCreatedAt());

        $postIt = $wall->getPostIts()[0];
        $this->assertInstanceOf(DateTimeImmutable::class, $postIt->getCreatedAt());

        $user = $wall->getUser();
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());
        
        $refreshToken = $user->getRefreshToken();
        $this->assertInstanceOf(DateTimeImmutable::class, $refreshToken->getCreatedAt());
        
        
        /*Test updatedAt*/

        //updating user
        $user->setName('New name');
        $this->em->persist($user);
        //updating title
        $postIt->setTitle('New title');
        $this->em->persist($postIt);
        //updating wall
        $wall->setName('New name');
        $this->em->persist($wall);
        //updating refreshToken
        $refreshToken->setValue('New value');
        $this->em->persist($refreshToken);

        $this->em->flush();

        $this->assertInstanceOf(DateTimeImmutable::class, $wall->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $postIt->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $refreshToken->getUpdatedAt());
    }
}
