<?php
namespace App\Service\Authentication\OAuth\OAuthApi\Providers;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


final class DiscordOAuthApiService implements OAuthApiInterface
{
    public function __construct(
        private ParameterBagInterface $params, 
        private RequestStack $requestStack, 
        private HttpClientInterface $httpClient
    ){}


    
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
        $url = "https://discord.com/api/oauth2/token";
        $data = [
            'code' => $code,//OK
            'grant_type' => 'authorization_code',//OK
            'redirect_uri' => $this->params->get('discord.oauth2.redirect_uri'),//OK
        ];

        // Create the Authorization header with Basic Authentication
        $authHeader = base64_encode($this->params->get('discord.oauth2.client_id') . ':' . $this->params->get('discord.oauth2.secret'));

        // Send a POST request
        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $authHeader
            ],
            'body' => $data,
        ]);

        

        // Get the response content
        $content = json_decode($response->getContent());

        // Retrieve user data from Google OAuth service
        return $content->access_token;
    }

    /**
     * Récupére les informations de l'utilisateur Google depuis l'api OAuth2
     *
     * @param string $bearerToken
     * @return array
     */
    public function retrieveUserData($bearerToken): array
    {
        // Define the request parameters
        $url = "https://discord.com/api/oauth2/@me";
        // Send a POST request
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'Authorization' => "Bearer $bearerToken"
            ]
        ]);

        $userData = json_decode($response->getContent());

        return [
            'id' => $userData->user->id,
            'name' => $userData->user->global_name,
            'picture' =>  'https://cdn.discordapp.com/avatars/'.$userData->user->id.'/'.$userData->user->avatar,
        ];
    }

}
