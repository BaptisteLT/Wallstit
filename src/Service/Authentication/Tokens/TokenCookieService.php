<?php
namespace App\Service\Authentication\Tokens;

use App\Entity\User;
use App\Entity\Tokens\JwtToken;
use App\Repository\UserRepository;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class TokenCookieService
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenManagerService $tokenManagerService
    ){}

    /**
     * Create cookies for authentication
     *
     * @param JwtToken $jwtToken
     * @param RefreshToken $refreshToken
     * @param JsonResponse $response
     * 
     * @return JsonResponse $response
     */
    public function createAuthCookies(JwtToken $jwtToken, RefreshToken $refreshToken, JsonResponse $response)
    {
        $response->headers->setCookie(new Cookie('jwtToken', $jwtToken->getValue(), $jwtToken->getExpiresAt()->getTimestamp(), '/', null, true, true));
        $response->headers->setCookie(new Cookie('refreshToken', $refreshToken->getValue(), $refreshToken->getExpiresAt()->getTimestamp(), '/', null, true, true));

        return $response;
    }

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
