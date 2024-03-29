<?php

namespace App\Controller\Authentication;

use App\Service\Authentication\OAuth\OAuthApi\Factory\OAuthApiFactory;
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

    #[Route('/get-{provider}-oauth2-url', name: 'get-provider-oauth2-url', methods: ['GET'])]
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
    #[Route('/get-tokens/{provider}', name: 'getTokens', methods: ['POST'])]
    public function getTokens(string $provider, Request $request, OAuthApiFactory $factory): JsonResponse
    {
        try
        {
            ['code' => $code, 'state' => $state] = json_decode($request->getContent(), true);

            $OAuthApiService = $factory->create($provider);

            $response = $this->OAuthAuthenticationService->prepareAuthenticationResponse($OAuthApiService, $provider, $code, $state); 
        }
        catch(\Exception $e)
        {
            //TODO: log $e
            //dump($e);die;
            throw new \Exception('An error occurred during the OAuth authentication.');
        }

        return $response;
    }
}
