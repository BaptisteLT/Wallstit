<?php
namespace App\Service\Authentication\Tokens;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RefreshTokenEncryptionService
{
    private string $cipher;

    public function __construct(
        private ParameterBagInterface $params, 
    ){
        $this->cipher = "aes-256-gcm";
    }

    public function setCipher(string $cipher): void
    {
        $this->cipher = $cipher;
    }

    /**
     * Encrypt or decrypt the refresh token
     *
     * @param string $token
     * @param string $method ('encrypt' or 'decrypt')
     * @return string | false
     */
    public function encryptOrDecrypt(string $token, string $method): string |false
    {
        // Encode the token using HMAC with the pepper
        $passphrase = $this->params->get('refresh.token.encoding.passphrase');
        //$passphrase devrait être généré précédemment d'une manière cryptographique, tel que openssl_random_pseudo_bytes

        if (in_array($this->cipher, openssl_get_cipher_methods()))
        {
            if($method === 'encrypt')
            {
                $ivlen = openssl_cipher_iv_length($this->cipher);
                // Generate a random IV
                $iv = openssl_random_pseudo_bytes($ivlen);
   
                // Encrypt the token
                $encryptedToken = openssl_encrypt($token, $this->cipher, $passphrase, 0, $iv, $tag);

                $token = base64_encode($encryptedToken . '::' . $iv . '::' . $tag);
            }
            else
            {
                [$token, $iv, $tag] = explode('::', base64_decode($token), 3);
                //Le token décrypté
                $token = openssl_decrypt($token, $this->cipher, $passphrase, $options=0, $iv, $tag);
            }
        }
        else
        {
            throw new Exception('OpenSSL is not installed or cipher doesn\'t exist.');
        }

        return $token;
    }
}
