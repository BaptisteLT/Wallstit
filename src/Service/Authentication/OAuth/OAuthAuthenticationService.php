<?php
namespace App\Service\Authentication\OAuth;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use App\Service\Authentication\Response\ResponseManagerService;
use App\Service\Authentication\UserRegistration\UserManagerService;

final class OAuthAuthenticationService{

    public function __construct(
        private UserManagerService $userManager,
        private TokenManagerService $tokenManager,
        private ResponseManagerService $responseManager
    ){}

    /**
     * Return the JWT and Refresh Token for user authentication.
     * 
     * @param OAuthApiInterface $OAuthApiInterface An implementation of OAuthApiInterface responsible for handling OAuth interactions.
     * @param string $code   The authentication code received from the authentication callback.
     * @param string $state  The state parameter used for preventing CSRF attacks.
     *
     * @return JsonResponse containing the JwtToken and RefreshToken in a cookie and in the response body itself.
     */
    public function prepareAuthenticationResponse(OAuthApiInterface $OAuthApiInterface, string $code, string $state): JsonResponse
    {
        // Exchange the code present in the Request for a Bearer token
        $bearerToken = $OAuthApiInterface->getBearerToken($code, $state);
        // Retrieve user data from Google OAuth service using the Bearer token
        $userData = $OAuthApiInterface->retrieveUserData($bearerToken);
        // Create or update user based on retrieved data
        $user = $this->userManager->getOrCreateUser($userData);
        // Generate JWT token based on user
        $jwtToken = $this->tokenManager->generateJWTToken($user);
        // Generate the refresh token
        $refreshToken = $this->tokenManager->generateRefreshToken($user);

        $response = $this->responseManager->authenticationResponse($jwtToken, $refreshToken);

        return $response;
    }
}