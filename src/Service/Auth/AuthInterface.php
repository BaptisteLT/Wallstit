<?php
namespace App\Service\Auth;

interface AuthInterface{

    /**
     * Return the JWT and Refresh Token for user authentication.
     *
     * @param string $code   The authentication code.
     * @param string $state  The state parameter for authentication.
     *
     * @return array<string, Token> An associative array containing the JWT and Refresh Token.
     *                               - 'jwtToken': Instance of JwtToken class.
     *                               - 'refreshToken': Instance of RefreshToken class.
     */
    public function getAuthenticationTokens(string $code, string $state): array;
}