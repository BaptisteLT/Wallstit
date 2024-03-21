<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthResponse\Generator;

class UrlGeneratorService{

    public function __construct(
        private AuthenticationCodesGeneratorService $codesGenerator
    )
    {}

    public function generateProviderLoginUrl(array $providerData): string
    {
        //Génère à la volée le state
        $providerData['state'] = $this->codesGenerator->generateState();
        
        //Génère à la volée le code_challenge s'il est disponible pour le provider en question
        if(isset($providerData['code_challenge']))
        {
            $providerData['code_challenge'] = $this->codesGenerator->generateCodeChallenge();
        }

        // splitting the URL
        ['scheme' => $scheme, 'host' => $host, 'path' => $path, 'query' => $query] = parse_url($providerData['base_login_url']);

        // Extract the query parameters into array $queryParams
        parse_str($query, $queryParams);

        //Populate the query params with the provider data
        $queryParams = $this->populateParams($queryParams, $providerData);

        // Rebuilding the query string with actual values
        $uri = $scheme . '://' . $host . $path . '?' .http_build_query($queryParams);

        return $uri;
    }

    private function populateParams(array $queryParams, array $providerData): array
    {
        foreach($queryParams as $key => $param)
        {
            if(!isset($providerData[$key]))
            {
                throw new \InvalidArgumentException('Missing url parameter "' .$key . '"');
            }
            $queryParams[$key] = $providerData[$key];
        }

        return $queryParams;
    }
}