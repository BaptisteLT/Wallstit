<?php
namespace App\EventListener\JWT;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedListener
{
    public function __construct(
        private UserRepository $userRepository
    )
    {}

    //TODO: peut-être bug côté client, lors du refresh tu jwt token, le user est-il mis à jour dans le localStorage???

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        //Permet d'ajouter l'image de l'utilisateur au payload du token JWT
        $payload = $event->getData();
        $userIdentifier = $payload['username'];
        $user = $this->userRepository->loadUserByIdentifier($userIdentifier);
        
        $payload['avatarImg'] = $user->getPicture();

        $event->setData($payload);
    }
}
