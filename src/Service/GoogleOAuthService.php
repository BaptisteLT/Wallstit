<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleOAuthService
{
    public function __construct(
        private ParameterBagInterface $params, 
        private RequestStack $requestStack, 
        private HttpClientInterface $httpClient, 
        private UserRepository $userRepository, 
        private EntityManagerInterface $em, 
        private TokenManagerService $tokenManagerService
    ){}

    /**
     * Récupére les informations de l'utilisateur Google depuis l'api OAuth2
     *
     * @param string $bearerToken
     * @return \stdClass
     */
    private function retrieveUserData($bearerToken): \stdClass
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
     * Trouve l'utilisateur en base de données ou le crée
     *
     * @param \stdClass $userData
     * @return User
     */
    private function getOrCreateUser($userData): User
    {
        $email = $userData->email;
        $name = $userData->name;
        $picture = $userData->picture;
        $locale = $userData->locale;

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if(!$user)
        {
            //We create a new user because it is a new account
            $user = new User();
            $user->setEmail($email)
                 ->setName($name)
                 ->setPicture($picture)
                 ->setlocale($locale);
        }
        else
        {
            //We update the user data in case they have changed
            if($name !== $user->getName()){
                $user->setName($name);
            }
            if($picture !== $user->getPicture()){
                $user->setPicture($picture);
            }
            if($locale !== $user->getLocale()){
                $user->setLocale($locale);
            }
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * Return bearer token from Google Request
     *
     * @param Request $request
     * @return string Bearer token
     */
    private function getBearerToken(string $code, string $state): string
    {
        $session = $this->requestStack->getSession();

        //Destructuring the array into variables
        //['state' => $state, 'code' => $code, 'scope' => $scope, 'authuser' => $authuser, 'prompt' => $prompt] = $request->query->all();

        //On vérifie que le state est le même qu'en session. Si ce n'est pas le cas, alors la requête n'est pas authentique ne vient pas de Google
        if(!($state === $session->get('state')))
        {
            throw new \Exception('State in session doesn\'t match with what google sent back.');
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

    /**
     * Returns the JWT (check period of validity in config file)
     *
     * @param Request $request
     * @return array
     */
    public function authenticate(string $code, string $state): array
    {
        // Exchange the code present in the Request for a Bearer token
        $bearerToken = $this->getBearerToken($code, $state);
        // Retrieve user data from Google OAuth service using the Bearer token
        $userData = $this->retrieveUserData($bearerToken);
        // Create or update user based on retrieved data
        $user = $this->getOrCreateUser($userData);
        // Generate JWT token based on user
        $jwtToken = $this->tokenManagerService->generateJWTToken($user);
        // Generate the refresh token
        $refreshToken = $this->tokenManagerService->generateRefreshToken($user);

        return ['jwtToken' => $jwtToken, 'refreshToken' => $refreshToken];
    }
}
