<?php
namespace App\Service\OAuthApi;

interface OAuth2ApiInterface{

    /**
     * Obtains a bearer token from the OAuth 2.0 API using the authorization code and state.
     *
     * @param string $code  The authorization code obtained from the authentication process.
     * @param string $state The state parameter to verify the authenticity of the request.
     *
     * @return string The bearer token.
     */
    public function getBearerToken(string $code, string $state): string;

    /**
     * Retrieves user data from the OAuth 2.0 API using the provided bearer token.
     *
     * @param string $bearerToken The bearer token obtained from the authentication process.
     *
     * @return \stdClass An object containing user data with the following properties:
     *                  - email: The user's email address.
     *                  - name: The user's name.
     *                  - picture: URL to the user's profile picture.
     *                  - locale: The user's locale or language preference.
     */
    public function retrieveUserData($bearerToken): \stdClass;
}