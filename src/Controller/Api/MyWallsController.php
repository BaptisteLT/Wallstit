<?php

namespace App\Controller\Api;

use App\Entity\Wall;
use App\Repository\UserRepository;
use App\Repository\WallRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\ValidatorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\Authentication\Tokens\TokenCookieService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api')]
class MyWallsController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private WallRepository $wallRepository,
        private ValidatorService $validatorService,
        private TokenCookieService $tokenCookieService
    ){}

    //En création
    #[Route('/my-wall', name: 'create-my-wall', methods: ['POST'])]
    public function createWall(Request $request): JsonResponse
    {
        $user = $this->tokenCookieService->findUserInRequest($request);

        $wall = new Wall();
        $wall->setName('My Wall');
        $wall->setUser($user);

        $this->em->persist($wall);
        $this->em->flush();

        $wallId = $wall->getId();

        return new JsonResponse(['id' => $wallId], Response::HTTP_OK);
    }


    #[Route('/my-walls', name: 'get-my-walls', methods: ['GET'])]
    public function getWalls(Request $request): JsonResponse
    {
        $user = $this->tokenCookieService->findUserInRequest($request);

        $walls = $user->getWalls();

        $json = $this->serializer->serialize($walls, 'json', ['groups' => 'get-walls']);

        return new JsonResponse(['walls' => $json], Response::HTTP_OK);
    }

    
    //suppression
    #[Route('/my-wall/delete/{id}', name: 'delete-my-wall', methods: ['DELETE'])]
    public function deleteWall(int $id, Request $request): JsonResponse
    {
        $user = $this->tokenCookieService->findUserInRequest($request);

        $wall = $this->wallRepository->findOneBy(['user'=>$user->getId(), 'id' => $id]);

        if(!$wall)
        {
            throw new NotFoundHttpException('Wall not found.');
        }
        $this->em->remove($wall);
        $this->em->flush();

        return new JsonResponse('ok', Response::HTTP_OK);
    }


    #[Route('/wall/{id}', name: 'patch-wall', methods: ['PATCH'])]
    public function patchWall(int $id, Request $request): JsonResponse
    {
        $user = $this->tokenCookieService->findUserInRequest($request);

        $wall = $this->wallRepository->findOneBy(['id' => $id, 'user' => $user->getId()]);

        
        //Si le post-it n'existe pas ou qu'il n'appartient à pas l'utilisateur qui en a fait la requête.
        if((!$wall))
        {
            //Alors on retourne un 404.
            throw new NotFoundHttpException('Wall not found');
        }

        $requestData = json_decode($request->getContent(), true);
        
        $wallBackground = $requestData['wallBackground'] ?? null;
        $wallName = $requestData['wallName'] ?? null;
        $wallDescription = $requestData['wallDescription'] ?? null;

        /**
         * Première vérification des types
         */
        //Si la valeur est spécifiée et si le type n'est pas celui attendu, on envoie une erreur Bad Request 400
        if(!is_string($wallBackground) && $wallBackground !== null)
        {
            throw new HttpException(400, 'Wall background must be a string');
        }
        if(!is_string($wallName) && $wallName !== null)
        {
            throw new HttpException(400, 'Wall name must be a string');
        }
        if(!is_string($wallDescription) && $wallDescription !== null)
        {
            throw new HttpException(400, 'Wall description must be a string');
        }
        

        /**
         * Remplacement des valeurs qui ont été spécifiées
         */
        if(array_key_exists('wallBackground', $requestData)){
            $wall->setBackground($wallBackground);
        }
        if(array_key_exists('wallName', $requestData)){
            $wall->setName($wallName); 
        }
        if(array_key_exists('wallDescription', $requestData)){
            $wall->setDescription($wallDescription); 
        }

        $this->validatorService->validateEntityOrThrowException($wall);

        $this->em->persist($wall);
        $this->em->flush();

        return new JsonResponse('OK', Response::HTTP_OK);
    }








}
