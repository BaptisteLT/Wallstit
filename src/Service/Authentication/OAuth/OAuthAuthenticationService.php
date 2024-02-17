<?php
namespace App\Service\Authentication\OAuth;

use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use App\Service\Authentication\UserRegistration\UserManagerService;

final class OAuthAuthenticationService{

    public function __construct(
        private UserManagerService $userManager,
        private TokenManagerService $tokenManagerService
    ){}

    /**
    * Return the JWT and Refresh Token for user authentication.
    * 
    * @param OAuthApiInterface $OAuthApiInterface An implementation of OAuthApiInterface responsible for handling OAuth interactions.
    * @param string $code   The authentication code received from the authentication callback.
    * @param string $state  The state parameter used for preventing CSRF attacks.
    *
    * @return array<string, Token> An associative array containing the JWT and Refresh Token.
    *                               - 'jwtToken': Instance of JwtToken class.
    *                               - 'refreshToken': Instance of RefreshToken class.
    */
   public function getAuthenticationTokens(OAuthApiInterface $OAuthApiInterface, string $code, string $state): array
   {
       // Exchange the code present in the Request for a Bearer token
       $bearerToken = $OAuthApiInterface->getBearerToken($code, $state);
       // Retrieve user data from Google OAuth service using the Bearer token
       $userData = $OAuthApiInterface->retrieveUserData($bearerToken);
       // Create or update user based on retrieved data
       $user = $this->userManager->getOrCreateUser($userData);
       // Generate JWT token based on user
       $jwtToken = $this->tokenManagerService->generateJWTToken($user);
       // Generate the refresh token
       $refreshToken = $this->tokenManagerService->generateRefreshToken($user);

       return ['jwtToken' => $jwtToken, 'refreshToken' => $refreshToken];
   }
}