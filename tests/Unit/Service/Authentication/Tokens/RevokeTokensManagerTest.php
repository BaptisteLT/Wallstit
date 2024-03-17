<?php
namespace App\Service\Authentication\Tokens;

use Symfony\Component\HttpFoundation\Response;

class RevokeTokensManager
{
    function revokeAuthTokens(Response $response)
    {
        $response->headers->clearCookie('jwtToken');
        $response->headers->clearCookie('refreshToken');
        $response->headers->clearCookie('PHPSESSID');

        return $response;
    }
}
