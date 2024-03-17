<?php
namespace App\Tests\Unit\Service\Validator;

use App\Service\Validator\ValidatorService;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ValidatorServiceTest extends KernelTestCase
{
    private ValidatorInterface $validatorMock;
    private ValidatorService $validatorService;

    protected function setUp(): void
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->validatorService = new ValidatorService($this->validatorMock);
    }


    /**
     * Testing validateEntityOrThrowException() method
     *
     * @return void
     */
    public function testInvalidEntityProperty(): void
    {
        //Création d'une violation
        $contraintViolationMock = $this->createMock(ConstraintViolation::class);
        $msg = 'This is a validation error message';
        $path = 'property_path';
        $value = 'provided_value';
        $contraintViolationMock->method('getMessage')->willReturn($msg);
        $contraintViolationMock->method('getPropertyPath')->willReturn($path);
        $contraintViolationMock->method('getParameters')->willReturn(['{{ value }}' => $value]);

        //Ajout de la violation à la liste de violation. La liste de violations sera retournée dès que validate sera appelée dans le service.
        $constraintViolationListInterface = new ConstraintViolationList([$contraintViolationMock]);

        $entity = new \stdClass(); // Replace \stdClass() with your actual entity class
        $this->validatorMock->expects($this->once())
                            ->method('validate')
                            ->with($entity)
                            ->willReturn($constraintViolationListInterface);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage($msg . ' for the property ' . $path . '. Your provided value is ' . $value);
        
        $this->validatorService->validateEntityOrThrowException($entity);
    }

    /**
     * Testing validateEntityOrThrowException() method
     *
     * @return void
     */
    public function testValidEntityProperties(): void
    {
        //Ajout de la violation à la liste de violation. La liste de violations sera retournée dès que validate sera appelée dans le service.
        $constraintViolationListInterface = new ConstraintViolationList([]);

        $entity = new \stdClass(); // Replace \stdClass() with your actual entity class
        $this->validatorMock->expects($this->once())
                            ->method('validate')
                            ->with($entity)
                            ->willReturn($constraintViolationListInterface);

        $this->validatorService->validateEntityOrThrowException($entity);

        // If no exception is thrown, the test will pass
        $this->assertTrue(true); // Dummy assertion to ensure the test passes
    }
}
