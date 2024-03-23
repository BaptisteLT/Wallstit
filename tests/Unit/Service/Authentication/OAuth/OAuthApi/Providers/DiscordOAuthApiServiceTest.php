<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthApi\Providers;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Service\Authentication\OAuth\OAuthApi\Providers\DiscordOAuthApiService;


final class DiscordOAuthApiServiceTest extends KernelTestCase
{
    private ParameterBagInterface $paramsMock;
    private RequestStack $requestStackMock;
    private HttpClientInterface $httpClientMock;

    private DiscordOAuthApiService $discordOAuthApiService;
    
    public function setUp(): void
    {
        $this->paramsMock = $this->createMock(ParameterBagInterface::class); 
        $this->requestStackMock = $this->createMock(RequestStack::class); 
        $this->httpClientMock = $this->createMock(HttpClientInterface::class); 
        $this->discordOAuthApiService = new DiscordOAuthApiService($this->paramsMock, $this->requestStackMock, $this->httpClientMock);
    }

    
    /**
     * test getBearerToken() method with valid data
     */
    public function testGetBearerToken(): void
    {
        $code = 'codeValue';
        $clientIdValue = 'client_id_value';   
        $secretValue = 'secret_value';              
        $redirectUri = 'some_redirect_url';

        $session = $this->createMock(SessionInterface::class);
        $session->expects($this->exactly(1))
        ->method('get')
        ->willReturnCallback(
            fn ($key) => match ($key) {
                'state' => 'stateValue',
                default => throw new \InvalidArgumentException("Unexpected key: $key")
            }
        );

        $this->requestStackMock->expects($this->once())
                               ->method('getSession')
                               ->willReturn($session);

        $this->paramsMock->expects($this->exactly(3))
        ->method('get')
        ->willReturnCallback(
            // On définit que le paramètre doit correspondre à une valeur spécifique
            fn ($key) => match ($key) {
                'discord.oauth2.client_id' => $clientIdValue,
                'discord.oauth2.secret' => $secretValue,
                'discord.oauth2.redirect_uri' => $redirectUri,
                //Si $key n'est pas trouvé on retournera une erreur
                default => throw new \InvalidArgumentException("Unexpected key: $key")
            }
        );

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
        //On définit ce qui sera retourné par Google
        ->method('getContent')
        ->willReturn('{
            "access_token": "accessTokenValue"
        }');

        // Define the request parameters
        $url = "https://discord.com/api/oauth2/token";
        $data = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ];

        // Create the Authorization header with Basic Authentication
        $authHeader = base64_encode($clientIdValue . ':' . $secretValue);

        $this->httpClientMock->expects($this->once())
        ->method('request')
        ->with('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $authHeader
            ],
            'body' => $data,
        ])
        ->willReturn($responseMock);

        $accessToken = $this->discordOAuthApiService->getBearerToken('codeValue', 'stateValue');
        
        $this->assertEquals('accessTokenValue', $accessToken, 'Le token d\'accès ne correspond pas à celui attendu.');
    }

    /**
     * test getBearerToken() method with invalid state
     */
    public function testGetBearerTokenWithInvalidState(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $this->requestStackMock->expects($this->once())
                               ->method('getSession')
                               ->willReturn($session);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('State in session doesn\'t match with what discord sent back.');

        $accessToken = $this->discordOAuthApiService->getBearerToken('codeValue', 'invalidStateValue');
        
        $this->assertEquals('accessTokenValue', $accessToken, 'Le token d\'accès ne correspond pas à celui attendu.');
    }

    /**
     * Test testRetrieveUserData()
     */
    public function testRetrieveUserData(): void
    {
        $bearerToken = 'someBearerTokenValue';
        
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
                 ->method('getContent')
                 ->willReturn('{
                    "user":
                    {
                        "id": "id_value",
                        "global_name": "global_name_value",
                        "avatar": "avatar_value"
                    }
                }');

        $url = "https://discord.com/api/oauth2/@me";
        $this->httpClientMock->expects($this->once())
        ->method('request')
        ->with('GET', $url, [
            'headers' => [
                'Authorization' => "Bearer $bearerToken"
            ]
        ])
        ->willReturn($responseMock);

        $userData = $this->discordOAuthApiService->retrieveUserData($bearerToken);
        
        $this->assertSame('id_value', $userData['id']);
        $this->assertSame('global_name_value', $userData['name']);
        $this->assertSame('https://cdn.discordapp.com/avatars/'.'id_value'.'/'.'avatar_value', $userData['picture']);
    }
}
