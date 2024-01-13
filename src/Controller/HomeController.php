<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //Ici on redirige toutes les requêtes vers index.html.twig avec le /{any}, pour que derrière React prenne en charge le routing
    //Et requirements: ["any" => "^(?!api).+"] permet de faire une exception pour les routes commençant par /api, afin que ce soit Symfony qui prenne en charge et non React
    #[Route('/{any?}', name: 'app_home', requirements: ["any" => "^(?!api).+"])]
    public function index()
    {
        return $this->render('index.html.twig');
    }
    #[Route('/api', name: 'api_endpoint')]
    public function apiEndpoint()
    {
        // Handle your API logic here
        // For example, return a JSON response
        return new JsonResponse(['message' => 'This is an API endpoint']);
    }
}
