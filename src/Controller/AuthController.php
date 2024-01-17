<?php

namespace App\Controller;

use App\Service\TokenManagerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    #[Route('/api/get-user-roles', name: 'api_get_user_roles')]
    public function index(TokenManagerService $tokenManagerService, Request $request): JsonResponse
    {
        $token = $request->cookies->get('jwtToken');


        //Récupérer le jwtToken
        $decodedToken = $tokenManagerService->decodeJwtToken($token);

        dump($decodedToken);die;
        
        return new JsonResponse(['roles' => [$token]]);
    }
}
