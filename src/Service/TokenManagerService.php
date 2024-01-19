<?php
namespace App\Service;

use DateInterval;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\RefreshToken;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TokenManagerService
{
    public function __construct(
        private JWTTokenManagerInterface $JWTManager,
        private ParameterBagInterface $params, 
        private EntityManagerInterface $em
    ){}

    public function generateJWTToken(User $user): string
    {
        return $this->JWTManager->create($user);
    }

    public function generateRefreshToken(User $user): array
    {
        // Generate a random token
        $token = (Uuid::v1())->__toString();

        // Encode the token using HMAC with the pepper
        $pepper = $this->params->get('refresh.token.encoding.passphrase');
        $encodedRefreshToken = hash_hmac('sha256', $token, $pepper);

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
        $refreshToken->setValue($encodedRefreshToken)
                     ->setExpiresAt($expiresAt);
        
        $this->em->persist($refreshToken);
        $this->em->persist($user);
        $this->em->flush();

        return [
            'refreshToken' => $encodedRefreshToken, 
            'expiresAt' => $refreshToken->getExpiresAt()->getTimestamp()
        ];//TODO faire le refresh du jwt avec ce refreshToken (check expiration date) + bouton logout pour kill les cookies + TTL du jwt à 15min
    }

    public function decodeJwtToken($token)
    {
        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return ['jwtHeader' => $jwtHeader, 'jwtPayload' => $jwtPayload];
    }
}
