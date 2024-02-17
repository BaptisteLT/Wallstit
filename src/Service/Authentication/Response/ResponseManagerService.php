<?php
namespace App\Service\Authentication\Response;

use App\Entity\Tokens\JwtToken;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use App\Service\Authentication\Tokens\TokenCookieService;
use App\Service\Authentication\UserRegistration\UserManagerService;

class ResponseManagerService{

    public function __construct(
        private UserManagerService $userManager,
        private TokenManagerService $tokenManagerService,
        private TokenCookieService $tokenCookieService
    ){}

    /**
     * Prepare the authentication response containing the jwtToken and refreshToken
     *
     * @param JwtToken $jwtToken
     * @param RefreshToken $refreshToken
     * 
     * @return JsonResponse
     */
    public function authenticationResponse(JwtToken $jwtToken, RefreshToken $refreshToken): JsonResponse
    {
        $response = new JsonResponse([
            'refreshTokenExpiresAt' => $refreshToken->getExpiresAt()->getTimestamp(), 
            'jwtToken' => $jwtToken->decode()
        ], 200);

        $response = $this->tokenCookieService->createAuthCookies($jwtToken, $refreshToken, $response);
        
        return $response;
    }
}