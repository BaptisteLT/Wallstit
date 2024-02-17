<?php

namespace App\Controller\Api;

use App\Entity\PostIt;
use App\Repository\WallRepository;
use App\Repository\PostItRepository;
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
class PostItController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private WallRepository $wallRepository,
        private PostItRepository $postItRepository,
        private SerializerInterface $serializer,
        private ValidatorService $validatorService,
        private TokenCookieService $tokenCookieService
    ){}

    //En création
    #[Route('/post-it', name: 'create-post-it', methods: ['POST'])]
    public function createWall(Request $request): JsonResponse
    {
        //Récupération des paramètres POST
        $requestData = json_decode($request->getContent(), true);
        $wallId = $requestData['wallId'];

        //Récupération du user dans le cookie
        $user = $this->tokenCookieService->findUserInRequest($request);
        //Récupération du wall du l'utilisateur
        $wall = $this->wallRepository->findOneBy(['user' => $user, 'id' => $wallId]);
        
        $postIt = new PostIt();
        $postIt->setWall($wall);

        
        $this->validatorService->validateEntityOrThrowException($postIt);

        $this->em->persist($postIt);
        $this->em->flush();

        return new JsonResponse(['uuid' => $postIt->getUuid()], Response::HTTP_CREATED);
    }


    
    #[Route('/wall/{id}/post-its', name: 'get-wall-post-its', methods: ['GET'])]
    public function getWalls(int $id, Request $request): JsonResponse
    {
  
        $wall = $this->wallRepository->find($id);

        $user = $this->tokenCookieService->findUserInRequest($request);

        if((!$wall) || ($wall->getUser() !== $user))
        {
            throw new NotFoundHttpException('Wall not found');
        }

        $json = $this->serializer->serialize($wall, 'json', ['groups' => 'get-post-its']);

        return new JsonResponse($json, Response::HTTP_OK);
    }


    
    
    #[Route('/post-it/{uuid}', name: 'get-post-it', methods: ['PATCH'])]
    public function patchPostIt(string $uuid, Request $request): JsonResponse
    {
        $postIt = $this->postItRepository->findOneBy(['uuid' => $uuid]);

        $user = $this->tokenCookieService->findUserInRequest($request);

        //Si le post-it n'existe pas ou qu'il n'appartient à pas l'utilisateur qui en a fait la requête.
        if((!$postIt) || ($postIt->getWall()->getUser() !== $user))
        {
            //Alors on retourne un 404.
            throw new NotFoundHttpException('Post-it not found');
        }

        $requestData = json_decode($request->getContent(), true);
        
        $color = $requestData['color'] ?? null;
        $content = $requestData['content'] ?? null;
        $title = $requestData['title'] ?? null;
        $size = $requestData['size'] ?? null;
        $positionX = $requestData['positionX'] ?? null;
        $positionY = $requestData['positionY'] ?? null;
        $deadline = $requestData['deadline'] ?? null;
        
        /**
         * Première vérification des types
         */
        //Si la valeur est spécifiée et si le type n'est pas celui attendu, on envoie une erreur Bad Request 400
        if(!is_string($color) && $color !== null)
        {
            throw new HttpException(400, 'Color must be a string');
        }
        if(!is_string($content) && $content !== null)
        {
            throw new HttpException(400, 'Content must be a string');
        }
        if(!is_string($title) && $title !== null)
        {
            throw new HttpException(400, 'Content must be a string');
        }
        if(!is_string($size) && $size !== null)
        {
            throw new HttpException(400, 'Size must be a string');
        }
        if(!is_string($deadline) && $deadline !== null)
        {
            throw new HttpException(400, 'Deadline must be a string format "Y-m-d H:i:s"');
        }
        if(!is_integer($positionX) && $positionX !== null)
        {
            throw new HttpException(400, 'PositionX must be an integer');
        }
        if(!is_integer($positionY) && $positionY !== null)
        {
            throw new HttpException(400, 'PositionY must be an integer');
        }

        /**
         * Remplacement des valeurs qui ont été spécifiées
         */
        if (isset($deadline)) {
            try {
                $deadlineDateTime = new \DateTimeImmutable($deadline);
                $postIt->setDeadline($deadlineDateTime);
            } catch (\Exception $e) {
                throw new HttpException(400, 'Deadline must be a string format "Y-m-d H:i:s"');
            }
        }

        if($color !== null){
            $postIt->setColor($color); 
        }
        if($content !== null){
            $postIt->setContent($content); 
        }
        if($title !== null){
            $postIt->setTitle($title); 
        }
        if($positionX !== null){
            $postIt->setPositionX($positionX); 
        }
        if($positionY !== null){
            $postIt->setPositionY($positionY); 
        }
        if($size !== null){
            $postIt->setSize($size); 
        }

        $this->validatorService->validateEntityOrThrowException($postIt);

        $this->em->persist($postIt);
        $this->em->flush();

        return new JsonResponse('OK', Response::HTTP_OK);
    }

}
