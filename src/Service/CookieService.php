<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Tokens\JwtToken;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class CookieService
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenManagerService $tokenManagerService
    ){}

    /**
     * Retourne un utilisateur en prenant la requête et les cookies contenus dans celle-ci
     *
     * @param Request $request
     * @return ?User
     */
    public function findUserInRequest(Request $request): ?User
    {
        //TODO: faire un middleware qui récupère directement le User de préférence
        if(!$request->cookies->has('jwtToken'))
        {
            throw new AccessDeniedException('jwtToken not found');
        }

        $jwtToken = $request->cookies->get('jwtToken');
        $decodedJwt = (new JwtToken($jwtToken))->decode();
        
        $user = $this->userRepository->findOneBy(['email' => $decodedJwt['jwtPayload']->username]);
        
        if(!$user)
        {
            throw new AccessDeniedException('User not found');
        }
        
        return $user;
    }
}
