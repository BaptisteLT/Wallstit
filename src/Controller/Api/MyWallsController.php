<?php

namespace App\Controller\Api;

use App\Entity\Wall;
use App\Repository\UserRepository;
use App\Repository\WallRepository;
use App\Service\TokenManagerService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

#[Route('/api')]
class MyWallsController extends AbstractController
{
    public function __construct(
        private TokenManagerService $tokenManager,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private WallRepository $wallRepository,
        private ValidatorService $validatorService
    ){}

    //En création
    #[Route('/my-wall', name: 'create-my-wall', methods: ['POST'])]
    public function createWall(Request $request): JsonResponse
    {
        $user = $this->tokenManager->findUserInRequest($request);

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
        $user = $this->tokenManager->findUserInRequest($request);

        $walls = $user->getWalls();

        $json = $this->serializer->serialize($walls, 'json', ['groups' => 'get-walls']);

        return new JsonResponse(['walls' => $json], Response::HTTP_OK);
    }

    
    //suppression
    #[Route('/my-wall/delete/{id}', name: 'delete-my-wall', methods: ['DELETE'])]
    public function deleteWall(int $id, Request $request): JsonResponse
    {
        $user = $this->tokenManager->findUserInRequest($request);

        $wall = $this->wallRepository->findOneBy(['user'=>$user->getId(), 'id' => $id]);

        if(!$wall)
        {
            throw new NotFoundHttpException('Wall not found.');
        }
        $this->em->remove($wall);
        $this->em->flush();

        return new JsonResponse('ok', Response::HTTP_OK);
    }

    
    //En création
    #[Route('/wall/{id}/wall-background', name: 'update-wall-background', methods: ['PUT'])]
    public function updateWallBackground(int $id, Request $request): JsonResponse
    {
        $user = $this->tokenManager->findUserInRequest($request);

        $wall = $this->wallRepository->findOneBy(['user'=>$user->getId(), 'id' => $id]);

        if(!$wall)
        {
            throw new NotFoundHttpException('Wall not found.');
        }

        $requestData = json_decode($request->getContent(), true);
        $wallBackground = $requestData['wallBackground'];

        if(!is_string($wallBackground))
        {
            throw new HttpException(400, 'Sidebar size must be a string');
        }

        $wall->setBackground($wallBackground);

        $this->validatorService->validateEntityOrThrowException($wall);

        $this->em->persist($wall);
        $this->em->flush();

        return new JsonResponse('OK', Response::HTTP_OK);
    }
}
