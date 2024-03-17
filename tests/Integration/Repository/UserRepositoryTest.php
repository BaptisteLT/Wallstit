<?php
namespace App\Tests\Functional\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em = static::getContainer()->get('doctrine')->getManager();

        //Insert test user in database
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setName('Test user');
        $user->setPicture('https://website/picture_url.fr');
        $user->setLocale('fr');
        $user->setOAuth2Provider('google');
        $user->setOAuth2ProviderId('1a2c34r5r67e8e9r7');
        $this->em->persist($user);
        $this->em->flush();

    }

    public function testLoadUserByIdentifier(): void
    {
        $user = $this->em->getRepository(User::class)->loadUserByIdentifier('google@@@1a2c34r5r67e8e9r7');
        $this->assertInstanceOf(User::class, $user);
    }
}