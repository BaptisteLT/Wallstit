<?php
namespace App\Tests\Unit\Service\Authentication\OAuth\OAuthApi\Factory;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\OAuth\OAuthApi\OAuthApiInterface;
use App\Service\Authentication\OAuth\OAuthApi\Factory\OAuthApiFactory;
use App\Service\Authentication\OAuth\OAuthApi\Providers\GoogleOAuthApiService;
use App\Service\Authentication\OAuth\OAuthApi\Providers\DiscordOAuthApiService;

/**
 * Permet de register automatiquement les impléments de OAuthApiInterface
 */
final class OAuthApiFactoryTest extends KernelTestCase
{
  private OAuthApiFactory $factory;

  public function setUp(): void
  {
    $this->factory = static::getContainer()->get('test.OAuthApiFactory');
  }

  /**
   * create() method with valid provider, and invalid
   *
   * @return void
   */
  public function testCreate(): void
  {
    $provider = $this->factory->create('GOogLe');
    $this->assertInstanceOf(GoogleOAuthApiService::class, $provider, 'Expected to get GoogleOAuthApiService when provider is "GOogLe".');

    //Valid
    $provider = $this->factory->create('google');
    $this->assertInstanceOf(GoogleOAuthApiService::class, $provider, 'Expected to get GoogleOAuthApiService when provider is "google".');

    //Valid
    $provider = $this->factory->create('discord');
    $this->assertInstanceOf(DiscordOAuthApiService::class, $provider, 'Expected to get DiscordOAuthApiService when provider is "discord".');

    //Invalid
    $this->expectException(\InvalidArgumentException::class, 'Expected to get an exception when the provider is invalid.');
    $this->expectExceptionMessage('Unknown provider given.');
    $this->factory->create('InvalidProvider');
  }
}
