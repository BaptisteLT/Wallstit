<?php

namespace App\Controller;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_')]
class GoogleAuthController extends AbstractController
{
    public function __construct(private RequestStack $requestStack) {

    }

    #[Route('/get-google-oauth2-url', name: 'get-google-oauth2-url')]
    public function generateGoogleOAuth2Url(): JsonResponse
    {
        //Génération d'un Uuid random que google nous renverra et que l'on vérifiera par la suite
        $uuid = (Uuid::v1())->__toString();

        $codeVerifier = bin2hex(random_bytes(32)); // Génération d'un code_verifier aléatoire
        //Génération du PCKE, qui est une clé que l'on envoie dans un premier temps hashé, puis en clair lors de la deuxième étape afin que Google s'assure de notre identité
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        $session = $this->requestStack->getSession();
        $session->set('state', $uuid);

        $session->set('original_PCKE', $codeVerifier);

        //Génération de l'URI que l'utilisateur (URL de login à Google)
        $codeChallengeMethod = 'S256';
        $scope = 'email%20profile';
        $access_type = 'offline';
        $response_type = 'code';
        $client_id = $this->getParameter('google.oauth2.client_id');
        $redirect_uri = $this->getParameter('google.oauth2.redirect_uri');
        $code_challenge = $codeChallenge;
        $state = $session->get('state'); // random guid that will be passed back to you
        
        $uri = sprintf(
        "https://accounts.google.com/o/oauth2/v2/auth?code_challenge_method=%s&scope=%s&access_type=%s&response_type=%s&client_id=%s&redirect_uri=%s&code_challenge=%s&state=%s", 
        $codeChallengeMethod, 
        $scope, 
        $access_type, 
        $response_type, 
        $client_id,
        $redirect_uri,
        $code_challenge,
        $state);

        return new JsonResponse($uri);
    }


    //TODO: gérer les erreurs??: Une réponse d'erreur:https://oauth2.example.com/auth?error=access_denied


    /*Cette route est appelée après que l'utilisateur se soit login sur Google*/
    #[Route('/google-callback', name: 'google-callback')]
    public function googleAuthCallback(Request $request): JsonResponse
    {
        $session = $this->requestStack->getSession();

        try{
            //Destructuring the array into variables
            [
                'state' => $state,
                'code' => $code, 
                'scope' => $scope, 
                'authuser' => $authuser, 
                'prompt' => $prompt
            ] = $request->query->all();

            //On vérifie que le state est le même qu'en session. Si ce n'est pas le cas, alors la requête n'est pas authentique ne vient pas de Google
            if(!($state === $session->get('state')))
            {
                dump('TODO redirect ou qqc comme ça?');die;
            }

            // Create a new HttpClient instance
            $client = HttpClient::create();

            // Define the request parameters
            $url = "https://oauth2.googleapis.com/token";
            $data = [
                'client_id' => $this->getParameter('google.oauth2.client_id'),
                'client_secret' => $this->getParameter('google.oauth2.secret'),
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->getParameter('google.oauth2.redirect_uri'),
                'code_verifier' => $session->get('original_PCKE')
            ];

            // Send a POST request
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => $data,
            ]);

            // Get the response content
            $content = $response->getContent();
            //OK c'est good on récupère bien le token, il reste à récup les infos de la personne, et créer un compte puis un token JWT
            dump($content);die;

        }
        catch(\Exception $e)
        {
            dump($e);die;
            echo 'idk yet';
        }


        return new JsonResponse('hiii');
    }

}
