<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Service\Authentication\Tokens\RevokeTokensManager;

class RevokeTokensManagerTest extends WebTestCase
{
    private RevokeTokensManager $revokeTokensManager;

    public function setUp(): void
    {
        $this->revokeTokensManager = new RevokeTokensManager();
    }

    /*function revokeAuthTokens(Response $response)
    {
        $response->headers->clearCookie('jwtToken');
        $response->headers->clearCookie('refreshToken');
        $response->headers->clearCookie('PHPSESSID');

        return $response;
    }*/

    /*function testRevokeAuthTokens()
    {
        // Create a client for making requests
        $client = static::createClient();

        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie('jwtToken', 'someValue'));
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie('refreshToken', 'someValue'));
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie('PHPSESSID', 'someValue'));
        
        // Make a request to a specific URL
        $crawler = $client->request('GET', '/');

        // Get the response object
        $response = $client->getResponse();



        /*$response = $this->createMock(Response::class);
        $response->headers = new ResponseHeaderBag;
        $response->headers->setCookie(new Cookie('jwtToken', 'someValue'));
        $response->headers->setCookie(new Cookie('refreshToken', 'someValue'));
        $response->headers->setCookie(new Cookie('PHPSESSID', 'someValue'));*/
        
        //$this->assertCount(3, $response->headers->getCookies());
  
        /*$newResponse = $this->revokeTokensManager->revokeAuthTokens($response);
        

        dump($client->getCookieJar()->all());die;

        dump($newResponse);die;

        
    }*/
}
