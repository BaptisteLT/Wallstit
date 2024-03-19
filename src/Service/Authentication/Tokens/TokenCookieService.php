<?php
namespace App\Service\Authentication\Tokens;

use App\Entity\User;
use App\Entity\Tokens\JwtToken;
use App\Repository\UserRepository;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenCookieService
{
    public function __construct(
        private UserRepository $userRepository
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
        $response->headers->setCookie(new Cookie('jwtToken', $jwtToken->getValue(), $jwtToken->getExpiresAt()->getTimestamp(), '/', null, true, true, false, Cookie::SAMESITE_STRICT));
        $response->headers->setCookie(new Cookie('refreshToken', $refreshToken->getValue(), $refreshToken->getExpiresAt()->getTimestamp(), '/', null, true, true, false, Cookie::SAMESITE_STRICT));

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
            throw new UnauthorizedHttpException('jwtToken not found');
        }

        $jwtToken = $request->cookies->get('jwtToken');
        $decodedJwt = (new JwtToken($jwtToken))->decode();

        [0 => $providerName, 1 => $providerAccountId] = explode("@@@", $decodedJwt['jwtPayload']->username);

        $user = $this->userRepository->findOneBy([
            'OAuth2Provider' =>  $providerName,
            'OAuth2ProviderId' => $providerAccountId
        ]);
        
        if(!$user)
        {
            throw new UnauthorizedHttpException('User not found');
        }
        
        return $user;
    }
}
