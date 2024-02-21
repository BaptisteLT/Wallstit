<?php
namespace App\EventListener\CSRF;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetCSRFListener
{
    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager
    )
    {}

    public function __invoke(RequestEvent $event)
    {
        $request = $event->getRequest();
        //On va valider le CSRF token
        if (str_starts_with($request->getRequestUri(), '/api/') && $request->getMethod() !== 'GET') {
            if($request->headers->has('X-CSRF-TOKEN'))
            {
                $csrfToken = $request->headers->get('X-CSRF-TOKEN');
                //Si le CSRF Token est valide, alors l'utilisateur va pouvoir accéder à la ressource
                if($this->csrfTokenManager->isTokenValid(new CsrfToken('app', $csrfToken)))
                {
                    return;
                }
            }
            //Autrement on lui envoie une erreur HTTP 403
            throw new AccessDeniedHttpException('CSRF Token is invalid.');
        }
    }
}