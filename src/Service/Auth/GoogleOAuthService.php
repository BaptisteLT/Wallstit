<?php
namespace App\Service\Auth;

use App\Service\UserManagerService;
use App\Service\TokenManagerService;
use Symfony\Component\HttpFoundation\Request;
use App\Service\OAuthApi\GoogleOAuth2ApiService;

final class GoogleOAuthService
{
    public function __construct(
        private GoogleOAuth2ApiService $apiService,
        private UserManagerService $userManager,
        private TokenManagerService $tokenManagerService
    ){}

    /**
     * Returns the JWT (check period of validity in config file) and Refresh Token
     *
     * @param Request $request
     * @return array
     */
    public function getAuthenticationTokens(string $code, string $state): array
    {
        // Exchange the code present in the Request for a Bearer token
        $bearerToken = $this->apiService->getBearerToken($code, $state);
        // Retrieve user data from Google OAuth service using the Bearer token
        $userData = $this->apiService->retrieveUserData($bearerToken);
        // Create or update user based on retrieved data
        $user = $this->userManager->getOrCreateUser($userData);
        // Generate JWT token based on user
        $jwtToken = $this->tokenManagerService->generateJWTToken($user);
        // Generate the refresh token
        $refreshToken = $this->tokenManagerService->generateRefreshToken($user);

        return ['jwtToken' => $jwtToken, 'refreshToken' => $refreshToken];
    }
}
