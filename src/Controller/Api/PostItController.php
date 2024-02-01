<?php

namespace App\Controller\Api;

use App\Entity\Wall;
use App\Entity\PostIt;
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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

#[Route('/api')]
class PostItController extends AbstractController
{
    public function __construct(
        private TokenManagerService $tokenManager,
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private WallRepository $wallRepository,
        private SerializerInterface $serializer,
    ){}

    //En création
    #[Route('/post-it', name: 'create-post-it', methods: ['POST'])]
    public function createWall(Request $request): JsonResponse
    {
        try
        {
            //Récupération des paramètres POST
            $requestData = json_decode($request->getContent(), true);
            $wallId = $requestData['wallId'];

            //Récupération du user dans le cookie
            $user = $this->tokenManager->findUserInRequest($request);
            //Récupération du wall du l'utilisateur
            $wall = $this->wallRepository->findOneBy(['user' => $user, 'id' => $wallId]);
            
            $postIt = new PostIt();
            $postIt->setWall($wall);

            $errors = $this->validator->validate($postIt);

            if (count($errors) > 0) {
                $errorArray = [];
                foreach ($errors as $error) {
                    $errorArray[$error->getPropertyPath()] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorArray], Response::HTTP_BAD_REQUEST);
            }

            $this->em->persist($postIt);
            $this->em->flush();

            $response = new JsonResponse(['uuid' => $postIt->getUuid()], Response::HTTP_OK);
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




    


    
    #[Route('/wall/{wall}/post-its', name: 'get-wall-post-its', methods: ['GET'])]
    public function getWalls(Wall $wall, Request $request): JsonResponse
    {
        try
        {
            $user = $this->tokenManager->findUserInRequest($request);

            if((!$wall) || ($wall->getUser() !== $user))
            {
                return new JsonResponse(['error' => 'Wall not found'], Response::HTTP_NOT_FOUND);
            }


            $json = $this->serializer->serialize($wall, 'json', ['groups' => 'get-post-its']);

            $response = new JsonResponse(['walls' => $json], Response::HTTP_OK);
        }
        catch(AccessDeniedException $e)
        {
            $response = new JsonResponse(['error' => 'Session has expired.'], Response::HTTP_UNAUTHORIZED);
        }
        catch(\Exception $e)
        {
            dump($e);die;
            $response = new JsonResponse(['error' => 'Server error while trying to create a new wall.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

}
