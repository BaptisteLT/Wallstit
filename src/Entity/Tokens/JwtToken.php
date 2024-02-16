<?php
namespace App\Entity\Tokens;

class JwtToken extends Token {
    protected \DateTimeImmutable $expiresAt;

    /**
     * @param string $value
     * @param string $expiresAt (must be a timestamp)
     */
    public function __construct(
        string $value,
    )
    {
        $this->value = $value;

        try{
            $this->expiresAt = new \DateTimeImmutable('@' . $this->decode()['jwtPayload']->exp);
        }
        catch(\Exception $e)
        {
            throw new \Exception('Expiration date not found in jwtPayload');
        }

    }

    public function decode(): array
    {
        $tokenParts = explode(".", $this->value);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return ['jwtHeader' => $jwtHeader, 'jwtPayload' => $jwtPayload];
    }
}