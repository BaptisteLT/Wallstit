<?php

namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthResponse\Generator;

use App\Service\Authentication\OAuth\OAuthSession\OAuthSessionHandlerService;
use App\Service\Authentication\OAuth\OAuthResponse\Generator\AuthenticationCodesGeneratorService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthenticationCodesGeneratorServiceTest extends KernelTestCase
{
    private AuthenticationCodesGeneratorService $authenticationCodesGeneratorService;

    private OAuthSessionHandlerService $sessionHandlerMock;

    public function setUp(): void
    {
        $this->sessionHandlerMock = $this->createMock(OAuthSessionHandlerService::class);
        $this->authenticationCodesGeneratorService = new AuthenticationCodesGeneratorService($this->sessionHandlerMock);
    }

    public function testGenerateState()
    {
        $this->sessionHandlerMock->expects($this->once())
                                  ->method('setState')
                                  ->willReturn(null);

        $state = $this->authenticationCodesGeneratorService->generateState();

        $this->assertIsString($state);
    }

    public function testGenerateCodeChallenge()
    {
        $this->sessionHandlerMock->expects($this->once())
        ->method('setOriginalPCKE')
        ->willReturn(null);

        $codeChallenge = $this->authenticationCodesGeneratorService->generateCodeChallenge();

        $this->assertIsString($codeChallenge);
    }
}