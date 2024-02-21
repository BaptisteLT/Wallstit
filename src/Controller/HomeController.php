<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    //Ici on redirige toutes les requêtes vers index.html.twig avec le /{any}, pour que derrière React prenne en charge le routing
    //Et requirements: ["any" => "^(?!api).+"] permet de faire une exception pour les routes commençant par /api et /auth, afin que ce soit Symfony qui prenne en charge et non React
    #[Route('/{any?}', name: 'app_home', requirements: ["any" => "^(?!api|auth).+"])]
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
