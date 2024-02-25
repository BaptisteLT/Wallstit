<?php
namespace App\Service\Authentication\UserRegistration;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManagerService
{
    public function __construct(
        private UserRepository $userRepository, 
        private EntityManagerInterface $em, 
    ){}

    /**
     * Trouve l'utilisateur en base de données ou le crée
     *
     * @param array $userData
     * @return User
     */
    public function getOrCreateUser($userData, string $provider): User
    {
        $user = $this->userRepository->findOneBy(['OAuth2Provider' => $provider, 'OAuth2ProviderId' => $userData['id']]);

        if(!$user)
        {
            //We create a new user because it is a new account
            $user = new User();
            if(isset($userData['name']))
            {
                $user->setName($userData['name']);
            }
            if(isset($userData['picture']))
            {
                $user->setPicture($userData['picture']);
            }
            if(isset($userData['locale']))
            {
                $user->setLocale($userData['locale']);
            }
            $user->setOAuth2Provider($provider);
            $user->setOAuth2ProviderId($userData['id']);
        }
        else
        {
            //We update the user data in case they have changed
            /*if(isset($userData['name']) && $userData['name'] !== $user->getName()){
                $user->setName($userData['name']);
            }*/
            if(isset($userData['picture']) && $userData['picture'] !== $user->getPicture()){
                $user->setPicture($userData['picture']);
            }
            /*if(isset($userData['locale']) && $userData['locale'] !== $user->getLocale()){
                $user->setLocale($userData['locale']);
            }*/
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
