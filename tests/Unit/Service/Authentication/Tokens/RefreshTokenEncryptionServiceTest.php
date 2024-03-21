<?php
namespace App\Tests\Unit\Service\Authentication\Tokens;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Authentication\Tokens\RefreshTokenEncryptionService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RefreshTokenEncryptionServiceTest extends KernelTestCase
{
    private RefreshTokenEncryptionService $refreshTokenEncryptionService;

    public function setUp(): void
    {
        $this->refreshTokenEncryptionService = static::getContainer()->get('test.RefreshTokenEncryptionService');
    }

    /**
     * Test encryptAndDecrypt() method
     *
     * @return void
     */
    public function testEncryptAndDecrypt()
    {
        $value = 'token';
        $encrypted = $this->refreshTokenEncryptionService->encryptOrDecrypt($value, 'encrypt');
        $this->assertIsString($encrypted);

        $decrypted = $this->refreshTokenEncryptionService->encryptOrDecrypt($encrypted, 'decrypt');
        $this->assertEquals($value, $decrypted, "Decrypt failed, expected value is '$value'.");
    }

    /*public function testInvalidCypher()
    {
        $value = 'token';


        // Mock openssl_get_cipher_methods to return an invalid cipher
        $opensslMock = $this->getMockBuilder('overload')
                            ->setMethods(['openssl_get_cipher_methods'])
                            ->getMock();
        $opensslMock->expects($this->any())->method('openssl_get_cipher_methods')->willReturn(['invalid_cypher']);

        //$this->expectException(\Exception::class);

        dump($this->refreshTokenEncryptionService->encryptOrDecrypt($value, 'encrypt'));die;

    }*/
}
