<?php
namespace App\Service\Authentication\OAuth\OAuthApi\Providers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class GoogleOAuthApiService implements OAuthApiInterface
{
    public function __construct(
        private ParameterBagInterface $params, 
        private RequestStack $requestStack, 
        private HttpClientInterface $httpClient
    ){}

    /**
     * Récupére les informations de l'utilisateur Google depuis l'api OAuth2
     *
     * @param string $bearerToken
     * @return \stdClass
     */
    public function retrieveUserData($bearerToken): \stdClass
    {
        // Define the request parameters
        $url = "https://www.googleapis.com/oauth2/v2/userinfo";
        // Send a POST request
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'Authorization' => "Bearer $bearerToken"
            ]
        ]);

        $userData = json_decode($response->getContent());

        return $userData;
    }

    /**
     * Return bearer token from Google Request
     *
     * @param Request $request
     * @return string Bearer token
     */
    public function getBearerToken(string $code, string $state): string
    {
        $session = $this->requestStack->getSession();

        //Destructuring the array into variables
        //['state' => $state, 'code' => $code, 'scope' => $scope, 'authuser' => $authuser, 'prompt' => $prompt] = $request->query->all();

        //On vérifie que le state est le même qu'en session. Si ce n'est pas le cas, alors la requête n'est pas authentique ne vient pas de Google
        if(!($state === $session->get('state')))
        {
            throw new AccessDeniedHttpException('State in session doesn\'t match with what google sent back.');
        }


        // Define the request parameters
        $url = "https://oauth2.googleapis.com/token";
        $data = [
            'client_id' => $this->params->get('google.oauth2.client_id'),
            'client_secret' => $this->params->get('google.oauth2.secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->params->get('google.oauth2.redirect_uri'),
            'code_verifier' => $session->get('original_PCKE')
        ];

        // Send a POST request
        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => $data,
        ]);

        // Get the response content
        $content = json_decode($response->getContent());
        // Retrieve user data from Google OAuth service
        return $content->access_token;
    }
}
