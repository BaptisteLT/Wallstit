<?php
namespace App\Service\Validator;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public function __construct(
        private ValidatorInterface $validator
    ){}

    /**
     *
     * Entity properties validation. (example, the user submits 'huge' for the size property, but only 'small', 'medium' and 'large' are allowed)
     *
     * @param $entity
     * @return void
     */
    public function validateEntityOrThrowException($entity): void
    {
        $errors = $this->validator->validate($entity);

        foreach($errors as $error)
        {
            $propertyName = $error->getPropertyPath();
            //La valeur renseignée par l'utilisateur
            $providedValue = $error->getParameters()['{{ value }}'] ? 'Your provided value is ' . $error->getParameters()['{{ value }}'] : null;

            //HTTP 400
            throw new BadRequestException($error->getMessage() . ' for the property '. $propertyName . '. ' . $providedValue);
        }
    }
}
