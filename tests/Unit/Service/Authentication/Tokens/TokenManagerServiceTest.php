<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use DateInterval;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Tokens\JwtToken;
use Symfony\Component\Uid\Uuid;
use App\Entity\Tokens\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use App\Service\Authentication\Tokens\TokenManagerService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TokenManagerServiceTest extends KernelTestCase
{
    private TokenManagerService $tokenManagerService;

    private RefreshTokenRepository $refreshTokenRepository;

    public function setUp(): void
    {
        $jwtTokenManagerInterface = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $parameterBagInterface = static::getContainer()->get('parameter_bag');
        $entityManagerInterface = static::getContainer()->get('doctrine.orm.default_entity_manager');
        $refreshTokenEncryptionService = static::getContainer()->get('test.RefreshTokenEncryptionService');

        $this->refreshTokenRepository = $this->createMock(RefreshTokenRepository::class);

        $this->tokenManagerService = new TokenManagerService($jwtTokenManagerInterface, $parameterBagInterface, $entityManagerInterface, $this->refreshTokenRepository, $refreshTokenEncryptionService);
    }

    /*public function testGenerateJWTToken(): void
    {
        $user = $this->createMock(User::class);
        
        $user->method('getId')->willReturn(1);
        $user->method('getEmail')->willReturn('test@test.fr');
        
        $user->method('getUserIdentifier')->willReturn('%s_%s');
        $user->method('getUsername')->willReturn('ff@@@ff');
        $user->method('getName')->willReturn('test');
        //$user->method('getPicture')->willReturn('pic');

        //TODO: le pb est que le JwtCreatedListener est triggered donc je pense que le plus simple c'est de faire un test d'intégration



   

        $jwtToken = $this->tokenManagerService->generateJWTToken($user);
        
        dump($jwtToken);die;
    } */


    /*public function generateJWTToken(User $user): JwtToken
    {
        $jwtToken = $this->JWTManager->create($user);
        return new JwtToken($jwtToken);
    }*/















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
