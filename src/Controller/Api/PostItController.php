<?php

namespace App\Controller\Api;

use App\Entity\Wall;
use App\Entity\PostIt;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\WallRepository;
use App\Repository\PostItRepository;
use App\Service\TokenManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        private PostItRepository $postItRepository,
        private SerializerInterface $serializer,
    ){}

    //TODO: implémenter une gestion des erreurs avec un listener: https://openclassrooms.com/fr/courses/7709361-construisez-une-api-rest-avec-symfony/7795134-gerez-les-erreurs-et-ajoutez-la-validation

    //En création
    #[Route('/post-it', name: 'create-post-it', methods: ['POST'])]
    public function createWall(Request $request): JsonResponse
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

        return new JsonResponse(['uuid' => $postIt->getUuid()], Response::HTTP_CREATED);
    }


    
    #[Route('/wall/{id}/post-its', name: 'get-wall-post-its', methods: ['GET'])]
    public function getWalls(int $id, Request $request): JsonResponse
    {
  
        $wall = $this->wallRepository->find($id);

        $user = $this->tokenManager->findUserInRequest($request);

        if((!$wall) || ($wall->getUser() !== $user))
        {
            throw new NotFoundHttpException('Wall not found');
        }

        $json = $this->serializer->serialize($wall, 'json', ['groups' => 'get-post-its']);

        return new JsonResponse($json, Response::HTTP_OK);
    }


    
    
    #[Route('/post-it/{uuid}', name: 'get-post-it', methods: ['GET'])] //TODO: changer en PATCH
    public function patchPostIt(int $uuid, Request $request): JsonResponse
    {
        $postIt = $this->postItRepository->findOneBy(['uuid' => $uuid]);

        $user = $this->tokenManager->findUserInRequest($request);

        //Si le post-it n'existe pas ou qu'il n'appartient à pas l'utilisateur qui en a fait la requête.
        if((!$postIt) || ($postIt->getWall()->getUser() !== $user))
        {
            //Alors on retourne un 404.
            throw new NotFoundHttpException('Post-it not found');
        }

        //TODO: PATCH
        
        return new JsonResponse('OK', Response::HTTP_OK);
    }

}
