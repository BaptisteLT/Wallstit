<?php

namespace App\EventListener;

//use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /*public function __construct(
        private LoggerInterface $logger
    )
    {
        
    }*/

    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();
 
        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $response->setData(['error' => $exception->getMessage()]);
            // sends the modified response object to the event
            $event->setResponse($response);
        } 
        /*elseif ($this->isWarning($exception)) 
        {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $response->setData(['error' => 'Internal server error due to a PHP warning.']);
            $this->logger->warning($exception->getMessage());
        } 
        else 
        {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $response->setData(['error' => 'Internal server error.']);
            $this->logger->error($exception->getMessage());
            // sends the modified response object to the event
            $event->setResponse($response);
        }*/
    }

    /**
     * Check if the exception is a PHP warning.
     *
     * @param \Throwable $exception
     * @return bool
     */
    private function isWarning(\Throwable $exception): bool
    {
        $warningCodes = [
            E_WARNING,
            E_CORE_WARNING,
            E_COMPILE_WARNING,
            E_USER_WARNING,
            E_RECOVERABLE_ERROR
        ];

        return in_array($exception->getCode(), $warningCodes, true);
    }
}
