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

    //TODO: 
    public function testAuthenticationResponse()
    {
        /*$response = new JsonResponse([
            'refreshTokenExpiresAt' => $refreshToken->getExpiresAt()->getTimestamp(), 
            'jwtToken' => $jwtToken->decode()
        ], 200);

        $response = $this->tokenCookieService->createAuthCookies($jwtToken, $refreshToken, $response);
        
        return $response;*/
    }
}