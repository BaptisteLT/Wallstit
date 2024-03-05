<?php
namespace App\Tests\Entity;

use App\Entity\Wall;
use App\Entity\PostIt;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class PostItTest extends EntityValidator
{
    private function getValidPostIt(): PostIt
    {
        $postIt = new PostIt();

        $postIt->setColor('yellow');
        $postIt->setTitle('Go to the gym');
        $postIt->setContent('I need to go to the gym.');
        $postIt->setPositionX(1000);
        $postIt->setPositionY(1000);
        $postIt->setWall(new Wall());
        $postIt->setSize('small');
        $postIt->setDeadline(new DateTimeImmutable());
        //TODO test get de tous les getters
      
        return $postIt;
    }

    public function testValidPostIt()
    {
        $this->countErrors($this->getValidPostIt(), 0);
    }

    public function testColor()
    {
        //test invalid color
        $postIt = $this->getValidPostIt()->setColor('purple');
        $this->countErrors($postIt, 1);
    }

    public function testTitle()
    {
        //test invalid title
        $postIt = $this->getValidPostIt()->setTitle('This is a very lonnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnng title');
        $this->countErrors($postIt, 1);

        //test null title
        $postIt = $this->getValidPostIt()->setTitle(null);
        $this->countErrors($postIt, 0);
    }

    public function testContent()
    {
        //test invalid content
        $postIt = $this->getValidPostIt()->setContent('This is a very lonnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnng content');
        $this->countErrors($postIt, 1);

        //test null content
        $postIt = $this->getValidPostIt()->setContent(null);
        $this->countErrors($postIt, 0);
    }

    public function testPositionX()
    {
        //test invalid positionX
        $postIt = $this->getValidPostIt()->setPositionX(8000);
        $this->countErrors($postIt, 1);

        //test null positionX
        $postIt = $this->getValidPostIt()->setPositionX(null);
        $this->countErrors($postIt, 0);
    }

    public function testPositionY()
    {
        //test invalid positionY
        $postIt = $this->getValidPostIt()->setPositionY(8000);
        $this->countErrors($postIt, 1);

        //test null positionY
        $postIt = $this->getValidPostIt()->setPositionY(null);
        $this->countErrors($postIt, 0);
    }

    public function testWall()
    {
        //test invalid positionX
        $postIt = $this->getValidPostIt()->setWall(null);
        $this->countErrors($postIt, 1);
    }

    public function testSize()
    {
        //test invalid size
        $postIt = $this->getValidPostIt()->setSize('xxl');
        $this->countErrors($postIt, 1);
    }
    
    public function testDeadline()
    {
        //test invalid size
        $postIt = $this->getValidPostIt()->setDeadline(null);
        $this->countErrors($postIt, 0);
    }

    public function testUuid()
    {
        $postIt = $this->getValidPostIt();
        $this->assertInstanceOf(Uuid::class, $postIt->getUuid());
    }
}
