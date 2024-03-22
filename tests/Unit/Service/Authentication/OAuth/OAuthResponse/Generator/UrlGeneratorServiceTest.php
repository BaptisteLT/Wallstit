<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthResponse\Generator;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\OAuth\OAuthResponse\Generator\UrlGeneratorService;
use App\Service\Authentication\OAuth\OAuthResponse\Generator\AuthenticationCodesGeneratorService;

class UrlGeneratorServiceTest extends KernelTestCase
{
    private UrlGeneratorService $urlGeneratorService;
    private AuthenticationCodesGeneratorService $codesGeneratorMock;

    public function setUp(): void
    {
        $this->codesGeneratorMock = $this->createMock(AuthenticationCodesGeneratorService::class);
        $this->urlGeneratorService = new UrlGeneratorService($this->codesGeneratorMock);
    }

    /**
     * Test valid testGenerateProviderLoginUrl()
     *
     * @return void
     */
    public function testValidGenerateProviderLoginUrl(): void
    {   
        $providerData = [
            'base_login_url' => 'https://discord.com/api/oauth2/authorize?client_id=&response_type=&redirect_uri=&scope=&state=',
            'client_id' => 'client_id_value',
            'redirect_uri' => 'redirect_uri_value',
            'response_type' => 'code',
            'scope' => 'identify',
            'code_challenge' => 'some_code_challenge_value'
        ];

        $this->codesGeneratorMock->expects($this->once())
                                 ->method('generateCodeChallenge')
                                 ->willReturn('someCodeChallengeValue');

        $this->codesGeneratorMock->expects($this->once())
                                 ->method('generateState')
                                 ->willReturn('someStateValue');

        $url = $this->urlGeneratorService->generateProviderLoginUrl($providerData);
        $this->assertSame('https://discord.com/api/oauth2/authorize?client_id=client_id_value&response_type=code&redirect_uri=redirect_uri_value&scope=identify&state=someStateValue', $url, 'generated url is wrong');
    }

    
    /**
     * Test missing param in providerData testGenerateProviderLoginUrl()
     *
     * @return void
     */
    public function testMissingParameterInUrl(): void
    {   
        $providerData = [
            'base_login_url' => 'https://discord.com/api/oauth2/authorize?client_id=&response_type=&redirect_uri=&scope=&state=',
            'client_id' => 'client_id_value',
            'redirect_uri' => 'redirect_uri_value',
            'response_type' => 'code',
            //'scope' => 'identify', Missing "scope"
            'code_challenge' => 'some_code_challenge_value'
        ];

        $this->codesGeneratorMock->expects($this->once())
                                 ->method('generateCodeChallenge')
                                 ->willReturn('someCodeChallengeValue');

        $this->codesGeneratorMock->expects($this->once())
                                 ->method('generateState')
                                 ->willReturn('someStateValue');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing url parameter "scope"');
        
        $this->urlGeneratorService->generateProviderLoginUrl($providerData);
    }
}