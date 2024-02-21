<?php

namespace App\Controller\Authentication;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Authentication\Tokens\TokenManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[Route('/auth', name: 'auth_')]
class AuthController extends AbstractController
{
    #[Route('/refresh-jwt-token', name: 'refresh-jwt-token', methods: ['GET'])]
    /* Va tenter de refresh le jwt token en utilisant le refresh token en cookies */
    public function refreshTokens(Request $request, TokenManagerService $tokenManager): JsonResponse
    {
        $refreshToken = $request->cookies->get('refreshToken');
        if(!$refreshToken)
        {
            throw new UnauthorizedHttpException('Refresh token has expired.');
        }
        ['jwtToken' => $jwtToken, 'refreshToken' => $refreshToken] = $tokenManager->refreshTokens($refreshToken);
        
        $response = new JsonResponse(['jwtToken' =>$jwtToken->getValue() ,'data' => [
            'refreshTokenExpiresAt' => $refreshToken->getExpiresAt()->getTimestamp(), 
            'jwtToken' => $jwtToken->decode()
        ]], Response::HTTP_OK);

        $response->headers->setCookie(new Cookie('jwtToken', $jwtToken->getValue(), $jwtToken->getExpiresAt()->getTimestamp(), '/', null, true, true));
        $response->headers->setCookie(new Cookie('refreshToken', $refreshToken->getValue(), $refreshToken->getExpiresAt()->getTimestamp(), '/', null, true, true));

        return $response;
    }
}
