<?php

namespace App\Service\Authentication\OAuth\OAuthResponse\Generator;

use App\Service\Authentication\OAuth\OAuthSession\OAuthSessionHandlerService;
use Symfony\Component\Uid\Uuid;

class AuthenticationCodesGeneratorService{

    public function __construct(
        private OAuthSessionHandlerService $sessionHandler
    )
    {}

    /**
     * Génération du state qui sera réutilisé au moment du callback
     *
     * @return string
     */
    public function generateState()
    {
        // Génération d'un Uuid random que le provider nous renverra et que l'on vérifiera par la suite
        $state = (Uuid::v1())->__toString();

        /* Mise en session, state sera réutilisé au moment du callback */
        $this->sessionHandler->setState($state);

        return $state;
    }

    /**
     * Used by some providers to add another layer of security (optional)
     *
     * @return string
     */
    public function generateCodeChallenge()
    {
        // Génération d'un code_verifier aléatoire
        $codeVerifier = bin2hex(random_bytes(32));

        /* Mise en session, original_PCKE sera réutilisé au moment du callback */
        $this->sessionHandler->setOriginalPCKE($codeVerifier);

        // Génération du PCKE, qui est une clé que l'on envoie dans un premier temps hashé, puis en clair lors de la deuxième étape afin que le provider s'assure de notre identité
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
     
        return $codeChallenge;
    }
}