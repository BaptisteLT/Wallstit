<?php

namespace App\Controller;

use Symfony\Component\Uid\Uuid;
use App\Service\GoogleOAuthService;
use App\Service\TokenManagerService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;

#[Route('/auth', name: 'auth_')]
class GoogleAuthController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger, 
        private RequestStack $requestStack, 
        private GoogleOAuthService $googleOAuthService, 
        private TokenManagerService $tokenManager
    ) {}

    #[Route('/get-google-oauth2-url', name: 'get-google-oauth2-url')]
    public function generateGoogleOAuth2Url(): JsonResponse
    {
        // Génération d'un Uuid random que google nous renverra et que l'on vérifiera par la suite
        $uuid = (Uuid::v1())->__toString();

        // Génération d'un code_verifier aléatoire
        $codeVerifier = bin2hex(random_bytes(32));
        // Génération du PCKE, qui est une clé que l'on envoie dans un premier temps hashé, puis en clair lors de la deuxième étape afin que Google s'assure de notre identité
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        /* Mise en session, ces valeurs seront réutilisées au moment du callback */
        $session = $this->requestStack->getSession();
        $session->set('state', $uuid);
        $session->set('original_PCKE', $codeVerifier);

        /*Génération de l'URI que l'utilisateur (URL de login à Google)*/     
        $uri = vsprintf("https://accounts.google.com/o/oauth2/v2/auth?code_challenge_method=%s&scope=%s&access_type=%s&response_type=%s&client_id=%s&redirect_uri=%s&code_challenge=%s&state=%s", 
        [
            'code_challenge_method' => 'S256',
            'scope' => 'email%20profile',
            'access_type' => 'offline',
            'response_type' => 'code',
            'client_id' => $this->getParameter('google.oauth2.client_id'),
            'redirect_uri' => $this->getParameter('google.oauth2.redirect_uri'),
            'code_challenge' => $codeChallenge,
            'state' => $session->get('state'),
        ]);

        return new JsonResponse($uri);
    }


    //TODO: gérer les erreurs de google??: Une réponse d'erreur:https://oauth2.example.com/auth?error=access_denied


    /*Cette route est appelée après que l'utilisateur se soit login sur Google*/
    #[Route('/get-tokens', name: 'getTokens')]
    public function getTokens(Request $request): JsonResponse
    {
        try
        {

            $requestData = json_decode($request->getContent(), true);
            $code = $requestData['code'];
            $state = $requestData['state'];
    
            $tokens = $this->googleOAuthService->authenticate($code, $state);

            $response = new JsonResponse([
                'refreshTokenExpiresAt' => $tokens['refreshToken']['expiresAt'], 
                'jwtToken' => $this->tokenManager->decodeJwtToken($tokens['jwtToken'])
            ], 200);//TODO: display error to client

            $response->headers->setCookie(new Cookie('jwtToken', $tokens['jwtToken'], 0, '/', null, true, true));
            $response->headers->setCookie(new Cookie('refreshToken', $tokens['refreshToken']['refreshToken'], 0, '/', null, true, true));
        }
        catch(\Exception $e)
        {
            $this->logger->error('An error occurred during the google authentication: ' . $e->getMessage());
            $response = new JsonResponse(['error' => 'An error occurred during the Google authentication.'], 500);//TODO: send error to client
        }

        return $response;
    }
}
