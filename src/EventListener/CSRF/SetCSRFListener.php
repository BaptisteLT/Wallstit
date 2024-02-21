<?php
namespace App\EventListener\CSRF;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SetCSRFListener
{
    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager
    )
    {}

    public function __invoke(ResponseEvent $event)
    {
        $response = $event->getResponse();

        //Envoie du csrf Token quand il y a un GET
        if($event->getRequest()->getMethod() === 'GET' && 
        $response instanceof JsonResponse && 
        json_validate($response->getContent()))
        {
            $csrfToken = $this->csrfTokenManager->getToken('app');

            //set new content with csrfToken in it
            $content = json_decode($response->getContent(), true);
            if(is_array($content))
            {
                $content['csrfToken'] = $csrfToken->getValue();
                $response->setContent(json_encode($content));
            }
        }
    }
}