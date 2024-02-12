<?php
namespace App\Controller\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Service\ValidatorService;
use App\Service\TokenManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class GeneralController extends AbstractController
{
    public function __construct(
        private TokenManagerService $tokenManager,
        private ValidatorService $validatorService,
        private EntityManagerInterface $em
    ){}

    //En création
    #[Route('/general/side-bar-size', name: 'update-side-bar-size', methods: ['PUT'])]
    public function updateSideBarSize(Request $request): JsonResponse
    {
        $user = $this->tokenManager->findUserInRequest($request);

        $requestData = json_decode($request->getContent(), true);
        $sideBarSize = $requestData['sideBarSize'];

        if(!is_string($sideBarSize))
        {
            throw new HttpException(400, 'Sidebar size must be a string');
        }

        $user->setSideBarSize($sideBarSize);

        $this->validatorService->validateEntityOrThrowException($user);

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse('OK', Response::HTTP_OK);
    }
}
