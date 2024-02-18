<?php
namespace App\Service\Authentication\OAuth\OAuthApi\Factory;

use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;

/**
 * Permet de register automatiquement les impléments de OAuthApiInterface
 */
final class OAuthApiFactory
{
  private iterable $providers;

  public function __construct(
    iterable $providers
  )
  {
    $this->providers = $providers;
  }

  public function create(string $providerName): OAuthApiInterface
  {
    
    foreach($this->providers as $provider) {
      //Is gonna find the class that matches the $providerName
      if (strpos(strtolower(get_class($provider)), strtolower($providerName)) !== false) {
        return $provider;
      }
    }

    throw new \InvalidArgumentException('Unknown provider given.');
  }
}
