<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthResponse;

use App\Entity\Tokens\JwtToken;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\Tokens\TokenCookieService;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\UserRegistration\UserManagerService;
use App\Service\Authentication\OAuth\OAuthResponse\ResponseManagerService;
use App\Service\Authentication\OAuth\OAuthResponse\Generator\UrlGeneratorService;
use DateTimeImmutable;

class ResponseManagerServiceTest extends KernelTestCase
{
    private ResponseManagerService $responseManagerService;
    private UrlGeneratorService $urlGeneratorMock;
    private TokenCookieService $tokenCookieServiceMock;

    public function setUp(): void
    {
        $this->tokenCookieServiceMock = $this->createMock(TokenCookieService::class);
        $this->urlGeneratorMock = $this->createMock(UrlGeneratorService::class);
        $this->responseManagerService = new ResponseManagerService($this->tokenCookieServiceMock, $this->urlGeneratorMock);
    }

    /**
     * test generateOAuthLoginUrlResponse()
     */
    public function testGenerateOAuthLoginUrlResponse()
    {
        $providerData = ['base_login_url' => 'https://someOAuthLoginUrl.com?some_more_data=', 'some_more_data' => 'xxxx'];

        $this->urlGeneratorMock->expects($this->once())
                                ->method('generateProviderLoginUrl')
                                ->with($providerData)
                                ->willReturn('https://someOAuthLoginUrl.com?some_more_data=xxxx');

        $response = $this->responseManagerService->generateOAuthLoginUrlResponse($providerData);

        $this->assertInstanceOf(JsonResponse::class, $response, 'expected a Json Response');
        $this->assertSame('https://someOAuthLoginUrl.com?some_more_data=xxxx', json_decode($response->getContent()));
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * test authenticationResponse()
     */
    public function testAuthenticationResponse()
    {
        $refreshToken = $this->createMock(RefreshToken::class);
        $refreshToken->expects($this->once())
                     ->method('getExpiresAt')
                     ->willReturn(new DateTimeImmutable());

        $jwtToken = $this->createMock(JwtToken::class);
        $jwtToken->expects($this->once())
                 ->method('decode')
                 ->willReturn(['someData'=>'someDataValue']);

        $this->tokenCookieServiceMock->expects($this->once())
                                     ->method('createAuthCookies')
                                     ->with($jwtToken, $refreshToken, $this->isInstanceOf(JsonResponse::class))
                                     ->willReturn(new JsonResponse());

        $response = $this->responseManagerService->authenticationResponse($jwtToken, $refreshToken);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}