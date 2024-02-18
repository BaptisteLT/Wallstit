<?php

namespace App\Controller\Authentication;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\OAuth\OAuthAuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\Authentication\OAuth\OAuthResponse\ResponseManagerService;
use App\Service\Authentication\OAuth\OAuthApi\Providers\GoogleOAuthApiService;


#[Route('/auth', name: 'auth_')]
class GoogleOAuthController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack, 
        private OAuthAuthenticationService $OAuthAuthenticationService, 
        private TokenManagerService $tokenManager
    ) {}

    #[Route('/get-{provider}-oauth2-url', name: 'get-provider-oauth2-url')]
    public function generateProviderOAuth2Url(string $provider, ResponseManagerService $responseManager): JsonResponse
    {
        $OAuthProviders = $this->getParameter('oauth2.providers');
        
        //Check if the selected provider exists
        if(!isset($OAuthProviders[$provider]))
        {
            throw new NotFoundHttpException(sprintf("Provider '%s' not found.", $provider));
        }

        $providerData = $OAuthProviders[$provider];

        $response = $responseManager->generateOAuthLoginUrlResponse($providerData);

        return $response;
    }


    //TODO: gérer les erreurs de google??: Une réponse d'erreur:https://oauth2.example.com/auth?error=access_denied


    /*Cette route est appelée après que l'utilisateur se soit login sur Google*/
    #[Route('/get-tokens', name: 'getTokens')]
    public function getTokens(Request $request, GoogleOAuthApiService $googleOAuthApiService): JsonResponse
    {
        try
        {
            ['code' => $code, 'state' => $state] = json_decode($request->getContent(), true);

            $response = $this->OAuthAuthenticationService->prepareAuthenticationResponse($googleOAuthApiService, $code, $state); 
        }
        catch(\Exception $e)
        {
            throw new HttpException(500, 'An error occurred during the Google authentication.');
        }

        return $response;
    }
}
