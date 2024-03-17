<?php
namespace App\Service\Authentication\OAuth\OAuthSession;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OAuthSessionHandlerService
{
    private SessionInterface $session;

    public function __construct(
        RequestStack $requestStack
    )
    {
        $this->session = $requestStack->getSession();
    }

    public function setState($state)
    {
        $this->session->set('state', $state);
    }

    public function setOriginalPCKE($originalPCKE)
    {
        $this->session->set('original_PCKE', $originalPCKE);
    }
}