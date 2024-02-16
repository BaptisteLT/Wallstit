<?php
namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RefreshTokenEncryptionService
{
    public function __construct(
        private ParameterBagInterface $params, 
    ){}

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

        $cipher = "aes-256-gcm";

        if (in_array($cipher, openssl_get_cipher_methods()))
        {
            if($method === 'encrypt')
            {
                $ivlen = openssl_cipher_iv_length($cipher);
                // Generate a random IV
                $iv = openssl_random_pseudo_bytes($ivlen);
   
                // Encrypt the token
                $encryptedToken = openssl_encrypt($token, $cipher, $passphrase, 0, $iv, $tag);

                $token = base64_encode($encryptedToken . '::' . $iv . '::' . $tag);
            }
            else
            {
                [$token, $iv, $tag] = explode('::', base64_decode($token), 3);
                //Le token décrypté
                $token = openssl_decrypt($token, $cipher, $passphrase, $options=0, $iv, $tag);
            }
        }
        else
        {
            throw new Exception('openssl is not installed.');
        }

        return $token;
    }
}
