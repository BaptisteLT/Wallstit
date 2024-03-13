<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Entity\Wall;
use App\Entity\PostIt;
use DateTimeImmutable;

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

    public function testName()
    {
        //Test si longueur 40
        $wall = $this->getValidWall()->setName('DJpCSZx8AAj1hIyguQmctmfZgnPCyiuftkFwvHAi');
        $this->countErrors($wall, 0);

        $longName = 'Hf5PYEZptIpZUGGwC4FU6EXCGciazniZS9l0Quq8YnLkQjtuuvFxDi2kRXE4Wkb7UsJbnMFDguDtBljNYKTuZEcE18vw391u2oEHcCebmc2oEds6Wcl3kdTtkf7AdVb4MuCUFuWms3IANREhHBivvv4SfCGxUJgR2N9QMhxctRy2M7dTtIEiacaafLB525wLE9gBDc9JvqZ5YOP2c4fe36dF5Ghx5UPwTvGEMipzlMZsUb2k76DlYrOgxHA24meJzOVJtfxKsYl0LaknTB1Tz7eeyB6LMZ3ZkgohIMaMh7RV';
        //Test si longueur 300 (renvoie une erreur car longueur supérieur à 50 caractères)
        $wall->setName($longName);
        $this->countErrors($wall, 1);

        //Test de récupération de la donnée
        $this->assertEquals($longName, $wall->getName());
    }

    public function testDescription()
    {
        //Test si null
        $wall = $this->getValidWall()->setDescription(null);
        $this->countErrors($wall, 0);

        //Test si longueur 40
        $wall->setDescription('DJpCSZx8AAj1hIyguQmctmfZgnPCyiuftkFwvHAi');
        $this->countErrors($wall, 0);

        //Test si longueur 300 (renvoie une erreur car longueur supérieur à 50 caractères)
        $longDesc = 'Hf5PYEZptIpZUGGwC4FU6EXCGciazniZS9l0Quq8YnLkQjtuuvFxDi2kRXE4Wkb7UsJbnMFDguDtBljNYKTuZEcE18vw391u2oEHcCebmc2oEds6Wcl3kdTtkf7AdVb4MuCUFuWms3IANREhHBivvv4SfCGxUJgR2N9QMhxctRy2M7dTtIEiacaafLB525wLE9gBDc9JvqZ5YOP2c4fe36dF5Ghx5UPwTvGEMipzlMZsUb2k76DlYrOgxHA24meJzOVJtfxKsYl0LaknTB1Tz7eeyB6LMZ3ZkgohIMaMh7RV';
        $wall->setDescription($longDesc);
        $this->countErrors($wall, 1);

        //Test de récupération de la donnée
        $this->assertEquals($longDesc, $wall->getDescription());
    }

    public function testBackground()
    {
        //Test si null
        $wall = $this->getValidWall()->setBackground(null);
        $this->countErrors($wall, 0);

        //Test avec option valide
        $wall->setBackground('bricks');
        $this->countErrors($wall, 0);

        //Test avec option invalide
        $wall->setBackground('not_authaurized_value');
        $this->countErrors($wall, 1);

        //Test de récupération de la donnée
        $this->assertEquals('not_authaurized_value', $wall->getBackground());
    }

    function testUser()
    {
        $wall = $this->getValidWall();
        $this->assertInstanceOf(User::class, $wall->getUser());
    }

    public function testPostIt()
    {
        $wall = $this->getValidWall();

        $postIt = new PostIt();

        //Ajout de deux postIts
        $wall->addPostIt($postIt);

        //Vérification qu'on a bien les 2 PostIts
        $this->assertCount(2, $wall->getPostIts());

        //Vérification que la suppression fonctionne
        $wall->removePostIt($postIt);

        //Vérification que la suppression a bien fonctionné
        $this->assertCount(1, $wall->getPostIts());
    }

    public function testCreatedAt()
    {
        $wall = $this->getValidWall();
        $wall->setCreatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $wall->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $wall = $this->getValidWall();
        $wall->setUpdatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $wall->getUpdatedAt());
    }
}
