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
     * @param \stdClass $userData
     * @return User
     */
    public function getOrCreateUser($userData): User
    {
        $email = $userData->email;
        $name = $userData->name;
        $picture = $userData->picture;
        $locale = $userData->locale;

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if(!$user)
        {
            //We create a new user because it is a new account
            $user = new User();
            $user->setEmail($email)
                 ->setName($name)
                 ->setPicture($picture)
                 ->setlocale($locale);
        }
        else
        {
            //We update the user data in case they have changed
            if($name !== $user->getName()){
                $user->setName($name);
            }
            if($picture !== $user->getPicture()){
                $user->setPicture($picture);
            }
            if($locale !== $user->getLocale()){
                $user->setLocale($locale);
            }
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
