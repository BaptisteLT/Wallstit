<?php

namespace App\Controller\Api;

use App\Entity\Wall;
use App\Repository\UserRepository;
use App\Service\TokenManagerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

#[Route('/api')]
class MyWallsController extends AbstractController
{
    public function __construct(
        private TokenManagerService $tokenManager,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    ){}

    //En création
    #[Route('/my-wall', name: 'my-wall'/*, methods: ['POST']*/)]
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
}
