<?php
namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Wall;
use DateTimeImmutable;
use App\Entity\Tokens\RefreshToken;

class UserTest extends EntityValidator
{
    private function getValidUser(): User
    {
        
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setName('Test user');
        $user->setPicture('https://website/picture_url.fr');
        $user->setLocale('fr');
        $user->setRefreshToken(new RefreshToken());
        $user->addWall(new Wall());
        $user->setOAuth2Provider('google');
        $user->setOAuth2ProviderId('1a2c34r5r67e8e9r7');

        return $user;
    }

    public function testValidPostIt()
    {
        $this->countErrors($this->getValidUser(), 0);
    }

    public function testEmail()
    {
        $user = $this->getValidUser();
        $this->assertSame('test@test.com', $user->getEmail());

        //Test email supérieur à 180 caractères
        $user->setEmail('test@test.commmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm');
        $this->countErrors($user, 1);

        $user->setEmail(null);
        $this->assertSame(null, $user->getEmail());
    }

    public function testRoles()
    {
        $user = $this->getValidUser();
        $this->assertSame(['ROLE_USER', 'ROLE_ADMIN'], $user->getRoles());

        $user->setRoles([]);
        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testName()
    {
        $user = $this->getValidUser();
        $this->assertSame('Test user', $user->getName());
    }

    public function testPicture()
    {
        $user = $this->getValidUser();
        $this->assertSame('https://website/picture_url.fr', $user->getPicture());
        $user->setPicture(null);
        $this->assertSame(null, $user->getPicture());
    }

    public function testLocale()
    {
        $user = $this->getValidUser();
        $this->assertSame('fr', $user->getLocale());
        $user->setLocale(null);
        $this->assertSame(null, $user->getLocale());
    }

    public function testRefreshToken()
    {
        $user = $this->getValidUser();
        $this->assertInstanceOf(RefreshToken::class, $user->getRefreshToken());
        $user->setRefreshToken(null);
        $this->assertSame(null, $user->getRefreshToken());
    }

    public function testWalls()
    {
        $user = $this->getValidUser();

        $wall = new Wall;
        $user->addWall($wall);
        $this->assertCount(2, $user->getWalls());
        $user->removeWall($wall);
        $this->assertCount(1, $user->getWalls());
    }

    public function testSideBarSize()
    {
        $user = $this->getValidUser()->setSideBarSize('xxl');
        $this->countErrors($user, 1);
        $user = $this->getValidUser()->setSideBarSize('small');
        $this->countErrors($user, 0);
        $user = $this->getValidUser()->setSideBarSize('medium');
        $this->countErrors($user, 0);
        $user = $this->getValidUser()->setSideBarSize('large');
        $this->countErrors($user, 0);
        $this->assertEquals('large', $user->getSideBarSize());
    }

    public function testOAuth2Provider()
    {
        $user = $this->getValidUser();
        $this->assertEquals('google', $user->getOAuth2Provider());
        $user->setOAuth2Provider(null);
        $this->countErrors($user, 0);   
        $this->assertEquals(null, $user->getOAuth2Provider());
    }

    public function testOAuth2ProviderId()
    {
        $user = $this->getValidUser();
        $this->assertEquals('1a2c34r5r67e8e9r7', $user->getOAuth2ProviderId());
        $this->countErrors($user, 0);
        $user->setOAuth2ProviderId(null);
        $this->assertEquals(null, $user->getOAuth2ProviderId());
    }

    public function testUsername()
    {
        $user = $this->getValidUser();
        $this->assertEquals('google@@@1a2c34r5r67e8e9r7', $user->getOAuth2Provider().'@@@'. $user->getOAuth2ProviderId());
    }


    public function testUserIdentifier()
    {
        $user = $this->getValidUser();
        $this->assertEquals('google_1a2c34r5r67e8e9r7', $user->getOAuth2Provider().'_'. $user->getOAuth2ProviderId());
    }

    public function testCreatedAt()
    {
        $user = $this->getValidUser();
        $user->setCreatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $user = $this->getValidUser();
        $user->setUpdatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getUpdatedAt());
    }
}
