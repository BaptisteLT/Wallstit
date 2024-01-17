<?php
namespace App\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenManagerService
{
    private JWTTokenManagerInterface $JWTManager;

    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }

    public function generateJWTToken(UserInterface $user)
    {
        return $this->JWTManager->create($user);
    }

    public function generateRefreshToken(UserInterface $user)
    {
        //todo Générer un UUID encodé avec un truc dans le .env
        // Generate a random token
        $token = bin2hex(random_bytes(32));
/*
        // Set expiration time (e.g., 30 days)
        $expiresAt = new \DateTime();
        $expiresAt->modify('+30 days');

        // Save the refresh token to the database
        $refreshToken = new RefreshToken();
        $refreshToken->setToken($token);
        $refreshToken->setExpiresAt($expiresAt);
        $refreshToken->setUser($user);

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return $token;*/
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
