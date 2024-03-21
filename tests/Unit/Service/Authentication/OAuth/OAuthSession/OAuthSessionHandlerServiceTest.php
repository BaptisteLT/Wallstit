<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthSession;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\Authentication\OAuth\OAuthSession\OAuthSessionHandlerService;

class OAuthSessionHandlerServiceTest extends KernelTestCase
{
    private OAuthSessionHandlerService $oAuthSessionHandlerService;

    private RequestStack $requestStackMock;

    private SessionInterface $sessionMock;

    public function setUp(): void
    {
        $this->sessionMock = $this->createMock(SessionInterface::class);
        $this->requestStackMock = $this->createMock(RequestStack::class);
        $this->requestStackMock
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->oAuthSessionHandlerService = new OAuthSessionHandlerService($this->requestStackMock);
    }

    public function testSetState(): void
    {
        $value = 'someState';

        $this->sessionMock
            ->expects($this->once())
            ->method('set')
            ->with('state', $value);

        $this->oAuthSessionHandlerService->setState($value);
    }


    public function testSetOriginalPCKE(): void
    {
        $value = 'somePCKE';

        $this->sessionMock
            ->expects($this->once())
            ->method('set')
            ->with('original_PCKE', $value);

        $this->oAuthSessionHandlerService->setOriginalPCKE($value);
    }
}