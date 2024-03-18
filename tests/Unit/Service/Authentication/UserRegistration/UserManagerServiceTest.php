<?php
namespace App\Service\Authentication\UserRegistration;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserManagerServiceTest extends KernelTestCase
{
    private UserRepository $userRepositoryMock;
    private EntityManagerInterface $em;
    private UserManagerService $userManagerService;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->userRepositoryMock =  $this->createMock(UserRepository::class);
        $this->userManagerService = new UserManagerService($this->userRepositoryMock, $this->em);
    }

    /**
     * Test la méthode getOrCreateUser()
     */
    public function testGetOrCreateUserWhenUserExists(): void
    {
        $provider = 'google';
        $providerId = 'id1234';

        $user = new User();
        $user->setName('John Doe');
        $user->setPicture('https://example.com/picture.jpg');
        $user->setLocale('en_US');
        $user->setOAuth2Provider($provider);
        $user->setOAuth2ProviderId($providerId);


        $this->userRepositoryMock->expects($this->once())
                                 ->method('findOneBy')
                                 ->with(['OAuth2Provider' => $provider, 'OAuth2ProviderId' => $providerId])
                                 //Simulation que l'utilisateur existe en BDD
                                 ->willReturn($user);

        //On attend qu'un User soit persisté
        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($user);
        
        //On attend que la méthode flush soit executée
        $this->em->expects($this->once())
                 ->method('flush');


        // Appeler la méthode getOrCreateUser avec des données utilisateur fictives
        $userData = [
            'id' => $providerId,
            'name' => 'John Doe',
            'picture' => 'https://example.com/my_new_picture.jpg',
            'locale' => 'en_US'
        ];
        $user = $this->userManagerService->getOrCreateUser($userData, $provider);
        $this->assertEquals('https://example.com/my_new_picture.jpg', $user->getPicture());
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('en_US', $user->getLocale());
        $this->assertEquals($provider, $user->getOAuth2Provider());
        $this->assertEquals($providerId, $user->getOAuth2ProviderId());
    }

    /**
     * Test la méthode getOrCreateUser()
     */
    public function testGetOrCreateUserWhenUserDoesNoExist(): void
    {
        $provider = 'google';
        $providerId = 'id1234';

        $user = new User();
        $user->setName('John Doe');
        $user->setPicture('https://example.com/picture.jpg');
        $user->setLocale('en_US');
        $user->setOAuth2Provider($provider);
        $user->setOAuth2ProviderId($providerId);

       
        $this->userRepositoryMock->expects($this->once())
                                 ->method('findOneBy')
                                 ->with(['OAuth2Provider' => $provider, 'OAuth2ProviderId' => $providerId])
                                 //Simulation que l'utilisateur n'existe pas en BDD
                                 ->willReturn(null);

        //On attend qu'un User soit persisté
        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($user);
        
        //On attend que la méthode flush soit executée
        $this->em->expects($this->once())
                 ->method('flush');

        // Appeler la méthode getOrCreateUser avec des données utilisateur fictives
        $userData = [
            'id' => $providerId,
            'name' => 'John Doe',
            'picture' => 'https://example.com/picture.jpg',
            'locale' => 'en_US'
        ];
        $user = $this->userManagerService->getOrCreateUser($userData, $provider);
        $this->assertEquals('https://example.com/picture.jpg', $user->getPicture());
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('en_US', $user->getLocale());
        $this->assertEquals($provider, $user->getOAuth2Provider());
        $this->assertEquals($providerId, $user->getOAuth2ProviderId());
    }
}
