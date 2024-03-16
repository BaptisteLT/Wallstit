<?php

namespace App\Tests\Unit\Entity\Tokens;

use App\Entity\Tokens\JwtToken;
use App\Tests\Unit\Entity\EntityValidator;
use DateTimeImmutable;

class JwtTokenTest extends EntityValidator
{
    private function getValidJwtToken(): JwtToken
    {
        $jwtToken = new JwtToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTA2MDI2MTYsImV4cCI6MTcxMDYwMzUxNiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiZGlzY29yZEBAQDkxNjQxMTYwNjU4ODYxMjY0OCIsImF2YXRhckltZyI6Imh0dHBzOi8vY2RuLmRpc2NvcmRhcHAuY29tL2F2YXRhcnMvOTE2NDExNjA2NTg4NjEyNjQ4L2NjZWQ0NDA2ZmI5OWYxZWZhODBiYWRkNzhiMGZjZTJjIn0.n6P9BunNy0Dq4ZZByp40C1WV5V_NGODl4eZav2hX0IXmh5MXPI9R4z_uokx94cmEH3Y6iEaF1Z4LyKVnEYQIQt5yLiRcJ-om1aUBInWPvA6Q9x6hlqoCpqTy5maOwl6s5lFcm-emnyuF373Hi_kLoxgTnqfVeWn9sfootSoqU7ijRM4tq3YFvfIYHBrGno0n8y8byyFVgo6ZFj4x3yyH3-c_nFgo_y-gqYF2Omhr3XHMFGtJ4s_j3s5jlr1tUtGi2ALzxQmgoYXeMnVKoUv6NaFPNP46Z7BIPpdeyPvHuWLwXZzgy_r56_4i7zCfXE8LBjmn7ffTj0J_FdCXMncAdQ");
        $jwtToken->setExpiresAt(new DateTimeImmutable('15 minutes'));

        return $jwtToken;
    }

    public function testDecode()
    {
        $jwtToken = $this->getValidJwtToken();
        $decodedJwtToken = $jwtToken->decode();
        //Test JWT Header
        $this->assertSame('JWT', $decodedJwtToken['jwtHeader']->typ);
        $this->assertSame('RS256', $decodedJwtToken['jwtHeader']->alg);

        //Test JWT Payload
        $this->assertSame(1710602616, $decodedJwtToken['jwtPayload']->iat);
        $this->assertSame(1710603516, $decodedJwtToken['jwtPayload']->exp);
        $this->assertSame('ROLE_USER', $decodedJwtToken['jwtPayload']->roles[0]);
        $this->assertSame('discord@@@916411606588612648', $decodedJwtToken['jwtPayload']->username);
        $this->assertSame('https://cdn.discordapp.com/avatars/916411606588612648/cced4406fb99f1efa80badd78b0fce2c', $decodedJwtToken['jwtPayload']->avatarImg);
    }
}
