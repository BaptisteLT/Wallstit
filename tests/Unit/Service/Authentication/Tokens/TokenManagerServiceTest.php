<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use DateInterval;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use App\Entity\Tokens\RefreshToken;
use App\Entity\Tokens\JwtToken;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenManagerService
{
    public function __construct(
        private JWTTokenManagerInterface $JWTManager,
        private ParameterBagInterface $params, 
        private EntityManagerInterface $em,
        private RefreshTokenRepository $refreshTokenRepository,
        private UserRepository $userRepository,
        private RefreshTokenEncryptionService $refreshTokenEncryptionService
    ){}

    public function generateJWTToken(User $user): JwtToken
    {
        $jwtToken = $this->JWTManager->create($user);
        return new JwtToken($jwtToken);
    }

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
    }
}
