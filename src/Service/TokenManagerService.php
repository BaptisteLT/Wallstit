<?php
namespace App\Service;

use DateInterval;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\RefreshToken;
use App\Repository\RefreshTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Uid\Uuid;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class TokenManagerService
{
    public function __construct(
        private JWTTokenManagerInterface $JWTManager,
        private ParameterBagInterface $params, 
        private EntityManagerInterface $em,
        private RefreshTokenRepository $refreshTokenRepository,
        private UserRepository $userRepository
    ){}

    public function generateJWTToken(User $user): string
    {
        return $this->JWTManager->create($user);
    }

    /**
     * Retourne un utilisateur en prenant la requête et les cookies contenus dans celle-ci
     *
     * @param Request $request
     * @return ?User
     */
    public function findUserInRequest($request): ?User
    {
        //TODO: faire un middleware qui récupère directement le User de préférence
        if(!$request->cookies->has('jwtToken'))
        {
            throw new AccessDeniedException('jwtToken not found');
        }

        $jwtToken = $request->cookies->get('jwtToken');
        $decodedJwt = $this->decodeJwtToken($jwtToken);
        
        $user = $this->userRepository->findOneBy(['email' => $decodedJwt['jwtPayload']->username]);
        
        if(!$user)
        {
            throw new AccessDeniedException('User not found');
        }
        
        return $user;
    }

    /**
     * Encrypt or decrypt the refresh token
     *
     * @param string $token
     * @param string $method ('encrypt' or 'decrypt')
     * @return string | false
     */
    private function encryptOrDecrypt(string $token, string $method): string |false
    {
        // Encode the token using HMAC with the pepper
        $passphrase = $this->params->get('refresh.token.encoding.passphrase');
        //$passphrase devrait être généré précédemment d'une manière cryptographique, tel que openssl_random_pseudo_bytes

        $cipher = "aes-256-gcm";

        if (in_array($cipher, openssl_get_cipher_methods()))
        {
            if($method === 'encrypt')
            {
                $ivlen = openssl_cipher_iv_length($cipher);
                // Generate a random IV
                $iv = openssl_random_pseudo_bytes($ivlen);
   
                // Encrypt the token
                $encryptedToken = openssl_encrypt($token, $cipher, $passphrase, 0, $iv, $tag);

                $token = base64_encode($encryptedToken . '::' . $iv . '::' . $tag);
            }
            else
            {
                [$token, $iv, $tag] = explode('::', base64_decode($token), 3);
                //Le token décrypté
                $token = openssl_decrypt($token, $cipher, $passphrase, $options=0, $iv, $tag);
            }
        }
        else
        {
            throw new Exception('openssl is not installed.');
        }


        return $token;
    }

    public function refreshTokens($refreshToken)
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['value' => $refreshToken]);
        if(!$refreshToken){
            throw new AccessDeniedException('Refresh token not found or has expired.');
        }
        //Je fais ça pour récupérer le User car sinon User est un proxy et la génération du JWT ne fonctionne pas, je pourrais passer l'entité en Eager mais ça ne serait pas opti pour le reste de l'appli qui récupère User sans avoir besoin de ses propriétés
        $user = $refreshToken->getUser();

        $expiresAtDateTime = $refreshToken->getExpiresAt();
        $currentDateTime = new DateTimeImmutable('now');

        if($expiresAtDateTime < $currentDateTime)
        {
            throw new AccessDeniedException('Token has expired.');
        }

        $decryptedToken = $this->encryptOrDecrypt($refreshToken->getValue(), 'decrypt');

        //Si le token a bien été généré et n'est pas égal à false
        if(!$decryptedToken)
        {
            throw new AccessDeniedException('Failed to decrypt refresh token.');
        }

       
        return [
            'jwtToken' => $this->generateJWTToken($user), 
            'refreshToken' => $this->generateRefreshToken($user)
        ];
    }

    public function generateRefreshToken(User $user): array
    {
        // Generate a random token
        $token = (Uuid::v1())->__toString();
        $encryptedToken = $this->encryptOrDecrypt($token, 'encrypt');

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

        return [
            'refreshToken' => $encryptedToken, 
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
