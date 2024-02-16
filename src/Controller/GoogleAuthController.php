<?php

namespace App\Controller;

use Symfony\Component\Uid\Uuid;
use App\Service\TokenManagerService;
use App\Service\Auth\GoogleOAuthService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/auth', name: 'auth_')]
class GoogleAuthController extends AbstractController
{
    public function __construct(
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

        return new JsonResponse($uri, 200);
    }


    //TODO: gérer les erreurs de google??: Une réponse d'erreur:https://oauth2.example.com/auth?error=access_denied


    /*Cette route est appelée après que l'utilisateur se soit login sur Google*/
    #[Route('/get-tokens', name: 'getTokens')]
    public function getTokens(Request $request): JsonResponse
    {
        try
        {
            ['code' => $code, 'state' => $state] = json_decode($request->getContent(), true);
            ['jwtToken' => $jwtToken, 'refreshToken' => $refreshToken] = $this->googleOAuthService->getAuthenticationTokens($code, $state);

            $response = new JsonResponse([
                'refreshTokenExpiresAt' => $refreshToken->getExpiresAt()->getTimestamp(), 
                'jwtToken' => $jwtToken->decode()
            ], 200);

            $response->headers->setCookie(new Cookie('jwtToken', $jwtToken->getValue(), $jwtToken->getExpiresAt()->getTimestamp(), '/', null, true, true));
            $response->headers->setCookie(new Cookie('refreshToken', $refreshToken->getValue(), $refreshToken->getExpiresAt()->getTimestamp(), '/', null, true, true));
        }
        catch(\Exception $e)
        {
            throw new HttpException(500, 'An error occurred during the Google authentication.');
        }

        return $response;
    }
}
