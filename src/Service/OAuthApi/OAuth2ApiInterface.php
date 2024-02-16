<?php
namespace App\Service\OAuthApi;

interface OAuth2ApiInterface{
    public function retrieveUserData($bearerToken): \stdClass;
    public function getBearerToken(string $code, string $state): string;
}