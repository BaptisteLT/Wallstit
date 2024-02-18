<?php
namespace App\Service\Authentication\OAuth\OAuthResponse;

use App\Entity\Tokens\JwtToken;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Authentication\Tokens\TokenCookieService;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\UserRegistration\UserManagerService;
use App\Service\Authentication\OAuth\OAuthResponse\Generator\UrlGeneratorService;

class ResponseManagerService{

    public function __construct(
        private UserManagerService $userManager,
        private TokenManagerService $tokenManagerService,
        private TokenCookieService $tokenCookieService,
        private UrlGeneratorService $urlGenerator
    ){}

    /**
     * Generates the provider login URL
     *
     * @param array $providerData
     * @return JsonResponse
     */
    public function generateOAuthLoginUrlResponse($providerData)
    {
        $uri = $this->urlGenerator->generateProviderLoginUrl($providerData);

        return new JsonResponse($uri, 200);
    }

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