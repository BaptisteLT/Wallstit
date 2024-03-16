<?php

use App\Entity\PostIt;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostItRepositoryTest extends KernelTestCase
{
    private $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    public function testLoadUserByIdentifier(): void
    {
        $onePostIt = $this->em->getRepository(PostIt::class)->findOneBy(['wall' => 1]);
        $postIt = $this->em->getRepository(PostIt::class)->findOneByUserAndUuid($onePostIt->getWall()->getUser(), $onePostIt->getUuid());
        $this->assertInstanceOf(PostIt::class, $postIt);
    }
}