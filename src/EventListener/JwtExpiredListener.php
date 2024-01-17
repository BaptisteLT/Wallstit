<?php
namespace App\EventListener;

use App\Service\TokenManagerService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class JwtExpiredListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorageInterface, 
        private JWTTokenManagerInterface $jwtManager,
        private RequestStack $requestStack
    ){}

    public function onJwtExpired(JWTExpiredEvent $event)
    {
        
        // Retrieve the request from the event
        /*$request = $event->getRequest();

        // Extract the token from the Authorization header
        $authorizationHeader = $request->headers->get('Authorization');
        //TODO: il peut être null
        $jwtPayload = $this->getJwtPayload($authorizationHeader);
        if(true)//expired
        {
            $event->setResponse(new RedirectResponse('/'));
        }

        
 
        // Retry the original request with the new token
        //$event->getRequest()->headers->set('Authorization', 'Bearer ' . $this->tokenManager->generateJWTToken($event->getUser()));



        /*if ($refreshToken) {
            // Attempt to refresh the token
            $newToken = $this->jwtManager->create($event->getUser());
            $event->setResponse($newToken);

            // Retry the original request with the new token
            $event->getRequest()->headers->set('Authorization', 'Bearer ' . $newToken);

            // Optionally, you can redirect to the original requested URL
            // $event->setResponse(new RedirectResponse($event->getRequest()->getRequestUri()));
        } else {
            // If no refresh token is available, you may redirect to the login page or handle it accordingly
            $event->setResponse(new RedirectResponse('/login'));
        }
    }*/

    /*private function getJwtPayload($authorizationHeader)
    {
        $token = trim(str_replace('Bearer', '', $authorizationHeader));
        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return $jwtPayload;*/
    }
}