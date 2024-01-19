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
    public function refreshTokens(Request $request, RefreshTokenRepository $refreshTokenRepository, TokenManagerService $tokenManager): JsonResponse
    {
        try
        {
            $refreshToken = $request->cookies->get('refreshToken');
            $tokens = $tokenManager->refreshTokens($refreshToken);
            
            $response = new JsonResponse(['jwtToken' =>$tokens['jwtToken'] ,'data' => [
                'refreshTokenExpiresAt' => $tokens['refreshToken']['expiresAt'], 
                'jwtToken' => $tokenManager->decodeJwtToken($tokens['jwtToken'])
            ]], Response::HTTP_OK);//TODO: display error to client
            $response->headers->setCookie(new Cookie('jwtToken', $tokens['jwtToken'], 0, '/', null, true, true));
            $response->headers->setCookie(new Cookie('refreshToken', $tokens['refreshToken']['refreshToken'], 0, '/', null, true, true));
        }
        catch(AccessDeniedException $e)
        {
            $response = new JsonResponse(['error' => 'Refresh token doesn\'t exist or is expired. Please log-in again.'], Response::HTTP_UNAUTHORIZED);
        } 
        catch(\Exception $e)
        {
            $response = new JsonResponse(['error' => 'Server error, please log-in again.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 

        return $response;
    }
}
