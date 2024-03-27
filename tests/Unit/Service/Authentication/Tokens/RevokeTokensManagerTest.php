<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Service\Authentication\Tokens\RevokeTokensManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RevokeTokensManagerTest extends KernelTestCase
{
    private RevokeTokensManager $revokeTokensManager;

    public function setUp(): void
    {
        $this->revokeTokensManager = new RevokeTokensManager();
    }

    function revokeAuthTokens(Response $response)
    {
        $response->headers->clearCookie('jwtToken');
        $response->headers->clearCookie('refreshToken');
        $response->headers->clearCookie('PHPSESSID');

        return $response;
    }

    function testRevokeAuthTokens()
    {
        $headers = $this->createMock(ResponseHeaderBag::class);
        $headers->expects($this->exactly(3))
        ->method('clearCookie')
        ->willReturnCallback(
            fn ($key) => match ($key) {
                'jwtToken' => null,
                'refreshToken' => null,
                'PHPSESSID' => null,
                default => throw new \InvalidArgumentException("Unexpected key: $key")
            }
        );

        $response = $this->createMock(Response::class);
        $response->headers = $headers;

        $this->revokeTokensManager->revokeAuthTokens($response);
    }
}
