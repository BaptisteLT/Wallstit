<?php

namespace App\Tests\Functional\Entity;

use App\Entity\Wall;
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
        $this->em->persist($wall);
        //$this->em->flush();
        //dump($wall->getCreatedAt());
        //dump($wall->getUpdatedAt());//TODO:
    }
}
