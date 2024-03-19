<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Tokens\JwtToken;
use App\Repository\UserRepository;
use App\Entity\Tokens\RefreshToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Service\Authentication\Tokens\TokenCookieService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenCookieServiceTest extends KernelTestCase
{
    private TokenCookieService $tokenCookieService;
    private UserRepository $userRepositoryMock;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
    
        $this->tokenCookieService = new TokenCookieService($this->userRepositoryMock);
    }

    /**
     * Testing method createAuthCookies()
     *
     * @return void
     */
    public function testCreateAuthCookies()
    {
        $dateNow = new DateTimeImmutable('now');

        //Création d'un mock de JsonResponse
        $response = $this->createMock(JsonResponse::class);
        $response->headers = new ResponseHeaderBag();

        //On crée un mock de la class JwtToken avec les valeurs retournées de getValue et getExpiresAt
        $jwtToken = $this->createMock(JwtToken::class);
        $jwtToken->method('getValue')->willReturn('jwtTokenValue');
        $jwtToken->method('getExpiresAt')->willReturn($dateNow);

        //On crée un mock de la class RefreshToken avec les valeurs retournées de getValue et getExpiresAt
        $refreshToken = $this->createMock(RefreshToken::class);
        $refreshToken->method('getValue')->willReturn('refreshTokenValue');
        $refreshToken->method('getExpiresAt')->willReturn($dateNow);

        //On appelle la méthode, et on fera des assertions sur la réponse retournée
        $response = $this->tokenCookieService->createAuthCookies($jwtToken, $refreshToken, $response);

        $jwtTokenCookie = $response->headers->getCookies()[0];
        $refreshTokenCookie = $response->headers->getCookies()[1];
        //On s'attend à ce que le cookie soit une instance de Symfony\Component\HttpFoundation\Cookie
        $this->assertInstanceOf(Cookie::class, $jwtTokenCookie, 'Expected a Symfony\Component\HttpFoundation\Cookie object');
        $this->assertInstanceOf(Cookie::class, $refreshTokenCookie, 'Expected a Symfony\Component\HttpFoundation\Cookie object');

        //On teste tous les attributs du cookie jwtToken
        $this->assertSame('jwtToken', $jwtTokenCookie->getName());
        $this->assertSame('jwtTokenValue', $jwtTokenCookie->getValue());
        $this->assertSame($dateNow->getTimestamp(), $jwtTokenCookie->getExpiresTime());
        $this->assertSame('/', $jwtTokenCookie->getPath());
        $this->assertSame(true, $jwtTokenCookie->isSecure());
        $this->assertSame(true, $jwtTokenCookie->isHttpOnly());
        $this->assertSame('strict', $jwtTokenCookie->getSameSite());
        
        //On teste tous les attributs du cookie refreshToken
        $this->assertSame('refreshToken', $refreshTokenCookie->getName());
        $this->assertSame('refreshTokenValue', $refreshTokenCookie->getValue());
        $this->assertSame($dateNow->getTimestamp(), $refreshTokenCookie->getExpiresTime());
        $this->assertSame('/', $refreshTokenCookie->getPath());
        $this->assertSame(true, $refreshTokenCookie->isSecure());
        $this->assertSame(true, $refreshTokenCookie->isHttpOnly());
        $this->assertSame('strict', $refreshTokenCookie->getSameSite());
    }



    /**
     * test de findUserInRequest() sans jwtToken en cookies
     *
     * @return void
     */
    public function testFindUserInRequestWithNoJwtToken()
    {
        $request = $this->createMock(Request::class);
        $request->cookies = new InputBag();
        
        //On attend une erreur
        $this->expectException(UnauthorizedHttpException::class);

        $this->tokenCookieService->findUserInRequest($request);
    }

    /**
     * test de findUserInRequest() avec jwtToken en cookies mais invalide
     *
     * @return void
     */
    public function testFindUserInRequestWithInvalidJwtToken()
    {
        $request = $this->createMock(Request::class);
        $request->cookies = new InputBag(['jwtToken' => 'invalidToken']);
        
        //On attend une erreur
        $this->expectException(\Exception::class);

        $this->tokenCookieService->findUserInRequest($request);
    }


    /**
     * test de findUserInRequest() avec jwtToken en cookies valide mais utilisateur introuvable
     *
     * @return void
     */
    public function testFindUserInRequestWithValidJwtTokenButUserNotFound()
    {
        $this->userRepositoryMock->expects($this->once())
                           ->method('findOneBy')
                           ->with(['OAuth2Provider' =>  'discord','OAuth2ProviderId' => '916411606588612648'])
                           ->willReturn(null);

        $request = $this->createMock(Request::class);
        $request->cookies = new InputBag(['jwtToken' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTA4NTI3MDUsImV4cCI6MTcxMDg1MzYwNSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiZGlzY29yZEBAQDkxNjQxMTYwNjU4ODYxMjY0OCIsImF2YXRhckltZyI6Imh0dHBzOi8vY2RuLmRpc2NvcmRhcHAuY29tL2F2YXRhcnMvOTE2NDExNjA2NTg4NjEyNjQ4L2NjZWQ0NDA2ZmI5OWYxZWZhODBiYWRkNzhiMGZjZTJjIn0.hfcPY7tSpKO-BIb_PijeZxRuhDD-UnaTCOOdevGUH6MnRY8UlhxQtfoXn3FQ25j_phfJZR6wGI1ZjAfPptzDlsU9KBLHiJrSemYBqjW2QlLiJ8LpFFkudxDWUj2kWrnLHTQG5xICwf1JMAWVFmal_SKfTSqH0c3bSqYLr2Ra0pOeZCCeRZgjud-6lADVWjywUL69qiTgtvbNZFeEwh9WgfV-A6XviBwdKtWEi-DuV5o2iN_Z7qZUlyAW4pWesF8bXF1mHQ0AKrSzN2Q5OuKKYgrRMmzmA5bJlTp28tjkTZqAdAhI13fyBMOQMqfEO0mC2VDAPRs1g7hbfZO9O1Jjjw']);
        
        //On attend une erreur
        $this->expectException(UnauthorizedHttpException::class);

        $this->tokenCookieService->findUserInRequest($request, $this->userRepositoryMock);
    }


    /**
     * test de findUserInRequest() avec jwtToken en cookies valide et utilisateur trouvable
     *
     * @return void
     */
    public function testFindUserInRequestWithValidJwtTokenAndUserFound()
    {
        $user = $this->createMock(User::class);

        $this->userRepositoryMock->expects($this->once())
                           ->method('findOneBy')
                           ->with(['OAuth2Provider' =>  'discord','OAuth2ProviderId' => '916411606588612648'])
                           ->willReturn($user);

        $request = $this->createMock(Request::class);
        $request->cookies = new InputBag(['jwtToken' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTA4NTI3MDUsImV4cCI6MTcxMDg1MzYwNSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiZGlzY29yZEBAQDkxNjQxMTYwNjU4ODYxMjY0OCIsImF2YXRhckltZyI6Imh0dHBzOi8vY2RuLmRpc2NvcmRhcHAuY29tL2F2YXRhcnMvOTE2NDExNjA2NTg4NjEyNjQ4L2NjZWQ0NDA2ZmI5OWYxZWZhODBiYWRkNzhiMGZjZTJjIn0.hfcPY7tSpKO-BIb_PijeZxRuhDD-UnaTCOOdevGUH6MnRY8UlhxQtfoXn3FQ25j_phfJZR6wGI1ZjAfPptzDlsU9KBLHiJrSemYBqjW2QlLiJ8LpFFkudxDWUj2kWrnLHTQG5xICwf1JMAWVFmal_SKfTSqH0c3bSqYLr2Ra0pOeZCCeRZgjud-6lADVWjywUL69qiTgtvbNZFeEwh9WgfV-A6XviBwdKtWEi-DuV5o2iN_Z7qZUlyAW4pWesF8bXF1mHQ0AKrSzN2Q5OuKKYgrRMmzmA5bJlTp28tjkTZqAdAhI13fyBMOQMqfEO0mC2VDAPRs1g7hbfZO9O1Jjjw']);
        
        $user = $this->tokenCookieService->findUserInRequest($request, $this->userRepositoryMock);

        $this->assertInstanceOf(User::class, $user, 'Expected a User object');
    }
}
