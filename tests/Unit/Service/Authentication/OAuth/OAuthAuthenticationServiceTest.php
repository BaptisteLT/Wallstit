<?php
namespace App\Tests\Unit\Service\Authentication\OAuth;

use App\Entity\User;
use App\Entity\Tokens\JwtToken;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use App\Service\Authentication\OAuth\OAuthAuthenticationService;
use App\Service\Authentication\UserRegistration\UserManagerService;
use App\Service\Authentication\OAuth\OAuthResponse\ResponseManagerService;

class OAuthAuthenticationServiceTest extends KernelTestCase
{
    private UserManagerService $userManagerMock;
    private TokenManagerService $tokenManagerMock;
    private ResponseManagerService $responseManagerMock;
    private OAuthAuthenticationService $oAuthAuthenticationService;

    public function setUp(): void
    {
        $this->userManagerMock = $this->createMock(UserManagerService::class);
        $this->tokenManagerMock = $this->createMock(TokenManagerService::class);
        $this->responseManagerMock = $this->createMock(ResponseManagerService::class);
        $this->oAuthAuthenticationService = new OAuthAuthenticationService($this->userManagerMock, $this->tokenManagerMock, $this->responseManagerMock);
    }

    /**
     * test prepareAuthenticationResponse()
     */
    public function testPrepareAuthenticationResponse(): void
    {
        $code = 'someCodeValue';
        $state = 'someStateValue';
        $provider = 'google';
        $bearerToken = 'someBearerToken';

        $oAuthApiInterface = $this->createMock(OAuthApiInterface::class);

        //Test de la méthode getBearerToken()
        $oAuthApiInterface->expects($this->once())
        ->method('getBearerToken')
        ->with($code, $state)
        ->willReturn($bearerToken);

        //Test de la méthode retrieveUserData()
        $userData = [
            'id' => 'some_id_value',
            'email' => 'some_email_value',
            'name' => 'some_name_value',
            'picture' => 'some_picture_value',
            'locale' => 'some_locale_value'
        ];
        $oAuthApiInterface->expects($this->once())
        ->method('retrieveUserData')
        ->with($bearerToken)
        ->willReturn($userData);

        //Test de la méthode getOrCreateUser()
        $user = $this->createMock(User::class);
        $this->userManagerMock->expects($this->once())
        ->method('getOrCreateUser')
        ->with($userData, $provider)
        ->willReturn($user);

        //Test de la méthode generateJWTToken()
        $jwtToken = $this->createMock(JwtToken::class);
        $this->tokenManagerMock->expects($this->once())
        ->method('generateJWTToken')
        ->with($user)
        ->willReturn($jwtToken);
  
        //Test de la méthode generateRefreshToken()
        $refreshToken = $this->createMock(RefreshToken::class);
        $this->tokenManagerMock->expects($this->once())
        ->method('generateRefreshToken')
        ->with($user)
        ->willReturn($refreshToken);

        //Test de la méthode authenticationResponse()
        $jsonResponse = $this->createMock(JsonResponse::class);
        $this->responseManagerMock->expects($this->once())
        ->method('authenticationResponse')
        ->with($jwtToken, $refreshToken)
        ->willReturn($jsonResponse);

        $jsonResponse = $this->oAuthAuthenticationService->prepareAuthenticationResponse($oAuthApiInterface, $provider, $code, $state);
    }
}