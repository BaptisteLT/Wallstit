<?php
namespace App\Tests\Functional\Service\Authentication\Tokens;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\Tokens\TokenManagerService;

class TokenManagerServiceTest extends KernelTestCase
{
    private TokenManagerService $tokenManagerService;

    private RefreshTokenRepository $refreshTokenRepository;

    private EntityManagerInterface $entityManagerInterface;

    public function setUp(): void
    {
        $jwtTokenManagerInterface = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $parameterBagInterface = static::getContainer()->get('parameter_bag');
        $this->entityManagerInterface = static::getContainer()->get('doctrine.orm.default_entity_manager');
        $refreshTokenEncryptionService = static::getContainer()->get('test.RefreshTokenEncryptionService');

        $this->refreshTokenRepository = $this->createMock(RefreshTokenRepository::class);

        $this->tokenManagerService = new TokenManagerService($jwtTokenManagerInterface, $parameterBagInterface, $this->entityManagerInterface, $this->refreshTokenRepository, $refreshTokenEncryptionService);
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















/*
    public function refreshTokens(string $refreshToken): array
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['value' => $refreshToken]);
        
        if(!$refreshToken){
            throw new UnauthorizedHttpException('Refresh token not found or has expired.');
        }
        //Je fais ça pour récupérer le User car sinon User est un proxy et la génération du JWT ne fonctionne pas, je pourrais passer l'entité en Eager mais ça ne serait pas opti pour le reste de l'appli qui récupère User sans avoir besoin de ses propriétés
        $user = $refreshToken->getUser();

        $expiresAtDateTime = $refreshToken->getExpiresAt();
        $currentDateTime = new DateTimeImmutable('now');

        if($expiresAtDateTime < $currentDateTime)
        {
            throw new UnauthorizedHttpException('Token has expired.');
        }

        $decryptedToken = $this->refreshTokenEncryptionService->encryptOrDecrypt($refreshToken->getValue(), 'decrypt');

        //Si le token a bien été généré et n'est pas égal à false
        if(!$decryptedToken)
        {
            throw new UnauthorizedHttpException('Failed to decrypt refresh token.');
        }

        return [
            'jwtToken' => $this->generateJWTToken($user), 
            'refreshToken' => $this->generateRefreshToken($user)
        ];
    }

    public function generateRefreshToken(User $user): RefreshToken
    {
        // Generate a random token
        $token = (Uuid::v1())->__toString();
        $encryptedToken = $this->refreshTokenEncryptionService->encryptOrDecrypt($token, 'encrypt');

        //Define the expiration date
        $expirationInSeconds = $this->params->get('refresh.token.expiration.seconds');
        $expiresAt = (new DateTimeImmutable('now'))->add(DateInterval::createFromDateString($expirationInSeconds.' seconds'));

        //Create the token (or update it with the new value)
        $refreshToken = $user->getRefreshToken();
        if(!$refreshToken)
        {
            $refreshToken = new RefreshToken();
            $user->setRefreshToken($refreshToken);
        }
        $refreshToken->setValue($encryptedToken)
                     ->setExpiresAt($expiresAt);
        
        $this->em->persist($refreshToken);
        $this->em->persist($user);
        $this->em->flush();

        return $refreshToken;
    }*/
}
