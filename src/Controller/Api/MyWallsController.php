<?php

namespace App\Controller\Api;

use App\Entity\Wall;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\WallRepository;
use App\Service\TokenManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class MyWallsController extends AbstractController
{
    public function __construct(
        private TokenManagerService $tokenManager,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private WallRepository $wallRepository
    ){}

    //En création
    #[Route('/my-wall', name: 'create-my-wall', methods: ['POST'])]
    public function createWall(Request $request): JsonResponse
    {
        try
        {
            $user = $this->tokenManager->findUserInRequest($request);

            $wall = new Wall();
            $wall->setName('My Wall');
            $wall->setUser($user);
            $wall->setHeight(1080);
            $wall->setWidth(1920);

            $this->em->persist($wall);
            $this->em->flush();

            $wallId = $wall->getId();

            $response = new JsonResponse(['id' => $wallId], Response::HTTP_OK);
        }
        catch(AccessDeniedException $e)
        {
            $response = new JsonResponse(['error' => 'Session has expired.'], Response::HTTP_UNAUTHORIZED);
        }
        catch(\Exception $e)
        {
            $response = new JsonResponse(['error' => 'Server error while trying to create a new wall.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 

        return $response;
    }

    //En création
    #[Route('/my-walls', name: 'get-my-walls', methods: ['GET'])]
    public function getWalls(Request $request): JsonResponse
    {
        try
        {
            $user = $this->tokenManager->findUserInRequest($request);

            $walls = $user->getWalls();

            $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('get-walls')
            ->toArray();
            
            $json = $this->serializer->serialize($walls, 'json', $context);

            $response = new JsonResponse(['walls' => $json], Response::HTTP_OK);
        }
        catch(AccessDeniedException $e)
        {
            $response = new JsonResponse(['error' => 'Session has expired.'], Response::HTTP_UNAUTHORIZED);
        }
        catch(\Exception $e)
        {
            $response = new JsonResponse(['error' => 'Server error while trying to create a new wall.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    
    //suppression
    #[Route('/my-wall/delete/{id}', name: 'delete-my-wall', methods: ['DELETE'])]
    public function deleteWall(int $id, Request $request): JsonResponse
    {
        try
        {
            $user = $this->tokenManager->findUserInRequest($request);

            $wall = $this->wallRepository->findOneBy(['user'=>$user->getId(), 'id' => $id]);

            if(!$wall)
            {
                return new JsonResponse(['error' => 'Wall not found.'], Response::HTTP_FORBIDDEN);
            }
            $this->em->remove($wall);
            $this->em->flush();

            $response = new JsonResponse(['response' => 'ok'], Response::HTTP_OK);
        }
        catch(AccessDeniedException $e)
        {
            $response = new JsonResponse(['error' => 'Session has expired.'], Response::HTTP_UNAUTHORIZED);
        }
        catch(\Exception $e)
        {
            $response = new JsonResponse(['error' => 'Server error while trying to create a new wall.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
