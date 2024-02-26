<?php
namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\ValidatorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\Authentication\Tokens\TokenCookieService;
use Symfony\Component\HttpKernel\Exception\HttpException;
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


    
    #[Route('/user/me', name: 'patch-user-me', methods: ['PATCH'])]
    public function patchUserMe(ValidatorService $validatorService, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $user = $this->tokenCookieService->findUserInRequest($request);

        if((!$user))
        {
            //Alors on retourne un 404.
            throw new NotFoundHttpException('User not found');
        }

        $requestData = json_decode($request->getContent(), true);
        $username = $requestData['username'] ?? null;
       
        /**
         * Première vérification des types
         */
        //Si la valeur est spécifiée et si le type n'est pas celui attendu, on envoie une erreur Bad Request 400
        if(!is_string($username) && $username !== null)
        {
            throw new HttpException(400, 'Username must be a string');
        }

        /**
         * Remplacement des valeurs qui ont été spécifiées
         */
        if(array_key_exists('username', $requestData)){
            $user->setName($username);
        }
        
        $validatorService->validateEntityOrThrowException($user);

        $em->persist($user);
        $em->flush();

        return new JsonResponse('OK', Response::HTTP_OK);
    }
}
