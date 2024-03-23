<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthApi\Providers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Service\Authentication\OAuth\OAuthApi\Providers\GoogleOAuthApiService;

class GoogleOAuthApiServiceTest extends KernelTestCase
{
    private ParameterBagInterface $paramsMock;
    private RequestStack $requestStackMock;
    private HttpClientInterface $httpClientMock;

    private GoogleOAuthApiService $googleOAuthApiService;
    
    public function setUp(): void
    {
        $this->paramsMock = $this->createMock(ParameterBagInterface::class); 
        $this->requestStackMock = $this->createMock(RequestStack::class); 
        $this->httpClientMock = $this->createMock(HttpClientInterface::class); 
        $this->googleOAuthApiService = new GoogleOAuthApiService($this->paramsMock, $this->requestStackMock, $this->httpClientMock);
    }

    /**
     * test getBearerToken() method with valid data
     */
    public function testGetBearerToken(): void
    {
        $code = 'codeValue';
        $originalPCKE = 'original_PCKE_value';

        $session = $this->createMock(SessionInterface::class);
        $session->expects($this->exactly(2))
        ->method('get')
        ->willReturnCallback(
            fn ($key) => match ($key) {
                'state' => 'stateValue',
                'original_PCKE' => 'original_PCKE_value',
                default => throw new \InvalidArgumentException("Unexpected key: $key")
            }
        );
        $this->requestStackMock->expects($this->once())
                               ->method('getSession')
                               ->willReturn($session);


        $clientIdValue = 'client_id_value';   
        $secretValue = 'secret_value';              
        $redirectUri = 'some_redirect_url';
   
        $this->paramsMock->expects($this->exactly(3))
        ->method('get')
        ->willReturnCallback(
            // On définit que le paramètre doit correspondre à une valeur spécifique
            fn ($key) => match ($key) {
                'google.oauth2.client_id' => $clientIdValue,
                'google.oauth2.secret' => $secretValue,
                'google.oauth2.redirect_uri' => $redirectUri,
                //Si $key n'est pas trouvé on retournera une erreur
                default => throw new \InvalidArgumentException("Unexpected key: $key")
            }
        );

        // Define the request parameters
        $url = "https://oauth2.googleapis.com/token";
        $data = [
            'client_id' => $clientIdValue,
            'client_secret' => $secretValue,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
            'code_verifier' => $originalPCKE
        ];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
        //On définit ce qui sera retourné par Google
        ->method('getContent')
        ->willReturn('{
            "access_token": "accessTokenValue"
        }');

        $this->httpClientMock->expects($this->once())
        ->method('request')
        ->with('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => $data,
        ])
        ->willReturn($responseMock);

        $accessToken = $this->googleOAuthApiService->getBearerToken('codeValue', 'stateValue');
        
        $this->assertEquals('accessTokenValue', $accessToken, 'Le token d\'accès ne correspond pas à celui attendu.');
    }


    /**
     * test getBearerToken() method with invalid state AccessDeniedHttpException 'State in session doesn\'t match with what google sent back.'
     */
    public function testGetBearerTokenWithInvalidState(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $this->requestStackMock->expects($this->once())
                               ->method('getSession')
                               ->willReturn($session);


        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('State in session doesn\'t match with what google sent back.');

        $accessToken = $this->googleOAuthApiService->getBearerToken('codeValue', 'invalidStateValue');
        
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
                    "id": "id_value",
                    "name": "name_value",
                    "picture": "picture_value",
                    "locale": "locale_value"
                 }');

        $url = "https://www.googleapis.com/oauth2/v2/userinfo";
        $this->httpClientMock->expects($this->once())
        ->method('request')
        ->with('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'Authorization' => "Bearer $bearerToken"
            ]
        ])
        ->willReturn($responseMock);

        $userData = $this->googleOAuthApiService->retrieveUserData($bearerToken);
        
        $this->assertSame('id_value', $userData['id']);
        $this->assertSame('name_value', $userData['name']);
        $this->assertSame('picture_value', $userData['picture']);
        $this->assertSame('locale_value', $userData['locale']);
    }
}
