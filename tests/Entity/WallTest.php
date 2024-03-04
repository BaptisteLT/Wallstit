<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Wall;
use App\Entity\PostIt;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WallTest extends EntityValidator
{
    private function getValidWall(): Wall
    {
        $wall = new Wall();

        $postIt = new PostIt();
        $user = new User();

        $wall->setName('My Wall');
        $wall->setUser($user);
        $wall->setDescription('My Description.');
        $wall->addPostIt($postIt);
        $wall->setBackground('bricks');

        return $wall;
    }

    //Teste si l'entité Wall est valide
    public function testValidWall(): void
    {
        $this->countErrors($this->getValidWall(), 0);
    }

    //TODO: tester name longueur de plus de 255 et 50 et null //en réalité il faudrait genre 50 caractères max
    //TODO: tester user null ou non
    //TODO: tester description longueur de plus de 100 et 50 et null
    //TODO: tester la collection de postits qui contient plusieurs éléments, ajout et suppression, tester quand collection vide
    //TODO: tester le background qui peut être null ou 'bricks', 'cork-board', 'flowers-colorful', 'flowers-dark', 'grouted-natural-stone', 'multi-coloured-tiles', 'wood', 'grid'
}
