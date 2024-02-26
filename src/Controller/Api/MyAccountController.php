<?php
namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\Authentication\Tokens\TokenCookieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api')]
class MyAccountController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private TokenCookieService $tokenCookieService
    ){}

    
    #[Route('/get-user-info', name: 'get-user-info', methods: ['GET'])]
    public function getUserInfo(Request $request): JsonResponse
    {
        $user = $this->tokenCookieService->findUserInRequest($request);

        if(!$user)
        {
            throw new NotFoundHttpException('User not found.');
        }

        $json = $this->serializer->serialize($user, 'json', ['groups' => 'get-user']);

        return new JsonResponse(['user' => $json], Response::HTTP_OK);
    }

}
