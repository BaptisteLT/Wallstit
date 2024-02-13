<?php

namespace App\Controller;

use PHPUnit\Util\Json;
use App\Service\TokenManagerService;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

#[Route('/auth', name: 'auth_')]
class AuthController extends AbstractController
{
    #[Route('/refresh-jwt-token', name: 'refresh-jwt-token')]
    /* Va tenter de refresh le jwt token en utilisant le refresh token en cookies */
    public function refreshTokens(Request $request, TokenManagerService $tokenManager): JsonResponse
    {
        
        $refreshToken = $request->cookies->get('refreshToken');
        $tokens = $tokenManager->refreshTokens($refreshToken);
        
        $jwtToken = $tokenManager->decodeJwtToken($tokens['jwtToken']);

        $response = new JsonResponse(['jwtToken' =>$tokens['jwtToken'] ,'data' => [
            'refreshTokenExpiresAt' => $tokens['refreshToken']['expiresAt'], 
            'jwtToken' => $jwtToken
        ]], Response::HTTP_OK);//TODO: display error to client

        $response->headers->setCookie(new Cookie('jwtToken', $tokens['jwtToken'], $jwtToken['jwtPayload']->exp, '/', null, true, true));
        $response->headers->setCookie(new Cookie('refreshToken', $tokens['refreshToken']['refreshToken'], $tokens['refreshToken']['expiresAt'], '/', null, true, true));

        return $response;
    }
}
