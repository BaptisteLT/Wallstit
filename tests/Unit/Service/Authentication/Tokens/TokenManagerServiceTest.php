<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Tokens\JwtToken;
use App\Entity\Tokens\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\Tokens\RefreshTokenEncryptionService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenManagerServiceTest extends KernelTestCase
{
    private TokenManagerService $tokenManagerService;

    private RefreshTokenRepository $refreshTokenRepositoryMock;

    private EntityManagerInterface $entityManagerInterface;

    private RefreshTokenEncryptionService $refreshTokenEncryptionServiceMock;

    public function setUp(): void
    {
        $jwtTokenManagerInterface = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $parameterBagInterface = static::getContainer()->get('parameter_bag');
        $this->entityManagerInterface = static::getContainer()->get('doctrine.orm.default_entity_manager');
        $this->refreshTokenEncryptionServiceMock = $this->createMock(RefreshTokenEncryptionService::class);

        $this->refreshTokenRepositoryMock = $this->createMock(RefreshTokenRepository::class);

        $this->tokenManagerService = $this->getMockBuilder(TokenManagerService::class)
        ->setConstructorArgs([
            $jwtTokenManagerInterface, 
            $parameterBagInterface,
            $this->entityManagerInterface,
            $this->refreshTokenRepositoryMock,
            $this->refreshTokenEncryptionServiceMock
        ])
        ->onlyMethods(['generateJWTToken', 'generateRefreshToken']) // Methods to mock
        ->getMock();
    }


    public function testRefreshTokens()
    {
        $refreshTokenValue = 'someRefreshTokenValue';
        $user = $this->createMock(User::class);

        $refreshToken = $this->createMock(RefreshToken::class);
        $refreshToken->expects($this->once())
        ->method('getUser')
        ->willReturn($user);

        $refreshToken->expects($this->once())
        ->method('getValue')
        ->willReturn($refreshTokenValue);

        $refreshToken->expects($this->once())
        ->method('getExpiresAt')
        ->willReturn((new DateTimeImmutable())->modify('+1 day'));

        $this->refreshTokenRepositoryMock->expects($this->once())
                                         ->method('findOneBy')
                                         ->with(['value' => $refreshTokenValue])
                                         ->willReturn($refreshToken);
        
        $this->refreshTokenEncryptionServiceMock->expects($this->once())
                                         ->method('encryptOrDecrypt')
                                         ->with($refreshTokenValue, 'decrypt')
                                         ->willReturn('aDecryptedTokenString');


        $this->tokenManagerService->expects($this->once())
                                  ->method('generateJWTToken')
                                  ->with($user)
                                  ->willReturn($this->createMock(JwtToken::class));

        $this->tokenManagerService->expects($this->once())
                                  ->method('generateRefreshToken')
                                  ->with($user)
                                  ->willReturn($this->createMock(RefreshToken::class));

        $newTokens = $this->tokenManagerService->refreshTokens($refreshTokenValue);

        $this->assertInstanceOf(JwtToken::class, $newTokens['jwtToken']);
        $this->assertInstanceOf(RefreshToken::class, $newTokens['refreshToken']);
    }

    
    /**
     * test refreshTokens() avec le refresh token pas trouvé
     *
     * @return void
     */
    public function testRefreshTokensNotFound()
    {
        $refreshTokenValue = 'someRefreshTokenValue';
        $refreshToken = $this->createMock(RefreshToken::class);

        $this->refreshTokenRepositoryMock->expects($this->once())
                                         ->method('findOneBy')
                                         ->with(['value' => $refreshTokenValue])
                                         ->willReturn(null);

        $this->expectException(UnauthorizedHttpException::class, 'On attend une erreur Unauthorized.');
        
        $this->tokenManagerService->refreshTokens($refreshTokenValue);
    }


    /**
     * test refreshTokens() avec un refresh token expiré
     *
     * @return void
     */
    public function testRefreshTokensExpired()
    {
        $refreshTokenValue = 'someRefreshTokenValue';
        $user = $this->createMock(User::class);

        $refreshToken = $this->createMock(RefreshToken::class);
        $refreshToken->expects($this->once())
        ->method('getUser')
        ->willReturn($user);

        $refreshToken->expects($this->once())
        ->method('getExpiresAt')
        //Expiré d'un jour
        ->willReturn((new DateTimeImmutable())->modify('-1 day'));

        $this->refreshTokenRepositoryMock->expects($this->once())
                                         ->method('findOneBy')
                                         ->with(['value' => $refreshTokenValue])
                                         ->willReturn($refreshToken);
        
        $this->expectException(UnauthorizedHttpException::class, 'On attend une erreur Unauthorized.');

        $this->tokenManagerService->refreshTokens($refreshTokenValue);
    }


    /**
     * test refreshTokens() fail to decrypt refresh token
     *
     * @return void
     */
    public function testRefreshTokensFailedToDecrypt()
    {
        $refreshTokenValue = 'someRefreshTokenValue';
        $user = $this->createMock(User::class);

        $refreshToken = $this->createMock(RefreshToken::class);
        $refreshToken->expects($this->once())
        ->method('getUser')
        ->willReturn($user);

        $refreshToken->expects($this->once())
        ->method('getValue')
        ->willReturn($refreshTokenValue);

        $refreshToken->expects($this->once())
        ->method('getExpiresAt')
        ->willReturn((new DateTimeImmutable())->modify('+1 day'));

        $this->refreshTokenRepositoryMock->expects($this->once())
                                         ->method('findOneBy')
                                         ->with(['value' => $refreshTokenValue])
                                         ->willReturn($refreshToken);
        
        $this->refreshTokenEncryptionServiceMock->expects($this->once())
                                         ->method('encryptOrDecrypt')
                                         ->with($refreshTokenValue, 'decrypt')
                                         ->willReturn(false);

        $this->expectException(UnauthorizedHttpException::class, 'On attend une erreur Unauthorized.');

        $this->tokenManagerService->refreshTokens($refreshTokenValue);
    }
}
