<?php
namespace App\Tests\Functional\Service\Authentication\Tokens;

use App\Entity\Tokens\RefreshToken;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\Tokens\TokenManagerService;
use App\Service\Authentication\Tokens\RefreshTokenEncryptionService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TokenManagerServiceTest extends KernelTestCase
{
    private TokenManagerService $tokenManagerService;

    private RefreshTokenRepository $refreshTokenRepository;

    private EntityManagerInterface $entityManagerInterface;

    private RefreshTokenEncryptionService $refreshTokenEncryptionServiceMock;

    private ParameterBagInterface $parameterBagInterface;

    public function setUp(): void
    {
        $jwtTokenManagerInterface = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $this->parameterBagInterface = $this->createMock(ParameterBagInterface::class);
        $this->entityManagerInterface = static::getContainer()->get('doctrine.orm.default_entity_manager');
        $this->refreshTokenEncryptionServiceMock = $this->createMock(RefreshTokenEncryptionService::class);

        $this->refreshTokenRepository = $this->createMock(RefreshTokenRepository::class);

        $this->tokenManagerService = new TokenManagerService($jwtTokenManagerInterface, $this->parameterBagInterface, $this->entityManagerInterface, $this->refreshTokenRepository, $this->refreshTokenEncryptionServiceMock);
    }

    /**
     * Test generateJWTToken() avec le JwtCreatedListener
     *
     * @return void
     */
    public function testGenerateJWTToken(): void
    {   
        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['email'=>'test@test.com']);

        //A ce moment là, le JwtCreatedListener est triggered et il va rajouter l'image d'avatar
        $jwtToken = $this->tokenManagerService->generateJWTToken($user);
        $decodedJwtToken = $jwtToken->decode();

        //Test JWT Header
        $this->assertSame('JWT', $decodedJwtToken['jwtHeader']->typ);
        $this->assertSame('RS256', $decodedJwtToken['jwtHeader']->alg);

        //Test JWT Payload
        $this->assertIsInt($decodedJwtToken['jwtPayload']->iat);
        $this->assertIsInt($decodedJwtToken['jwtPayload']->exp);
        $this->assertSame('ROLE_USER', $decodedJwtToken['jwtPayload']->roles[0]);
        $this->assertSame('google@@@89748948918919', $decodedJwtToken['jwtPayload']->username);
        $this->assertSame('https://website/picture_url.fr', $decodedJwtToken['jwtPayload']->avatarImg);
    } 






    /**
     * test de generateRefreshToken() quand aucun refresh token n'est établi sur User
     *
     * @return void
     */
    public function testGenerateRefreshTokenWhenNullOnUser(): void
    {
        $encryptedToken = 'encryptedToken';

        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setName('Test user');
        $user->setPicture('https://website/picture_url.fr');
        $user->setLocale('fr');
        $user->setOAuth2Provider('google');
        $user->setOAuth2ProviderId('89748948918919');

        //generateRefreshToken(User $user)
        $this->refreshTokenEncryptionServiceMock->expects($this->once())
                                                ->method('encryptOrDecrypt')
                                                ->with($this->isType('string'), 'encrypt')
                                                ->willReturn($encryptedToken);

        $this->parameterBagInterface->expects($this->once())
                                    ->method('get')
                                    ->with('refresh.token.expiration.seconds')
                                    //7 days
                                    ->willReturn(604800);

        $refreshToken = $this->tokenManagerService->generateRefreshToken($user);

        //Test si le token a bien été inséré en BDD
        $refreshTokenFromDB = $this->entityManagerInterface->getRepository(RefreshToken::class)->findOneBy(['value' => $encryptedToken]);
        $this->assertInstanceOf(RefreshToken::class, $refreshTokenFromDB);

        $this->assertSame($refreshToken, $refreshTokenFromDB, 'Le token retourné et celui inséré en base de données ne sont pas équivalents.');
    }

    
    /**
     * test de generateRefreshToken() avec update de l'entité RefreshToken associée au User
     *
     * @return void
     */
    public function testGenerateRefreshTokenWhenNotNullOnUser(): void
    {
        $encryptedToken = 'encryptedToken';

        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setName('Test user');
        $user->setPicture('https://website/picture_url.fr');
        $user->setLocale('fr');
        $user->setOAuth2Provider('google');
        $user->setOAuth2ProviderId('89748948918919');
        $user->setRefreshToken(new RefreshToken);

        //generateRefreshToken(User $user)
        $this->refreshTokenEncryptionServiceMock->expects($this->once())
                                                ->method('encryptOrDecrypt')
                                                ->with($this->isType('string'), 'encrypt')
                                                ->willReturn($encryptedToken);

        $this->parameterBagInterface->expects($this->once())
                                    ->method('get')
                                    ->with('refresh.token.expiration.seconds')
                                    //7 days
                                    ->willReturn(604800);

        $refreshToken = $this->tokenManagerService->generateRefreshToken($user);

        //Test si le token a bien été inséré en BDD
        $refreshTokenFromDB = $this->entityManagerInterface->getRepository(RefreshToken::class)->findOneBy(['value' => $encryptedToken]);
        $this->assertInstanceOf(RefreshToken::class, $refreshTokenFromDB);

        $this->assertSame($refreshToken, $refreshTokenFromDB, 'Le token retourné et celui inséré en base de données ne sont pas équivalents.');
    }
}
