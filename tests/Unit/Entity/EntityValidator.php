<?php

namespace App\Tests\Unit\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class EntityValidator extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = static::getContainer()->get('validator');
    }
    
    //Méthode pour compter le nombre d'erreurs dans l'entité
    protected function countErrors($entity, $expectedErrors)
    {
        $errors = $this->validator->validate($entity);

        if(count($errors) <> $expectedErrors)
        {
            dump($errors);
        }

        $this->assertCount($expectedErrors, $errors);
    }
}
