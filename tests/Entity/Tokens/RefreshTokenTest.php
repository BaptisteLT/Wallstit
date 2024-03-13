<?php

namespace App\Tests\Entity\Tokens;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Tokens\RefreshToken;
use App\Tests\Entity\EntityValidator;

class RefreshTokenTest extends EntityValidator
{
    private function getValidRefreshToken(): RefreshToken
    {
        $refreshToken = new RefreshToken();
        $refreshToken->setValue('some_refresh_some');
        $refreshToken->setUser(new User);
        $refreshToken->setExpiresAt(new DateTimeImmutable());

        return $refreshToken;
    }

    public function testValidRefreshToken()
    {
        $this->countErrors($this->getValidRefreshToken(), 0);
    }

    public function testValue()
    {
        $refreshToken = $this->getValidRefreshToken();
        $this->assertEquals('some_refresh_some', $refreshToken->getValue());
    }

    public function testExpiresAt()
    {
        $refreshToken = $this->getValidRefreshToken();
        $this->assertInstanceOf(DateTimeImmutable::class, $refreshToken->getExpiresAt());
    }

    public function testUser()
    {
        $refreshToken = $this->getValidRefreshToken();
        $this->assertInstanceOf(User::class, $refreshToken->getUser());
    }

    public function testUpdatedAt()
    {
        $refreshToken = $this->getValidRefreshToken();
        $refreshToken->setUpdatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $refreshToken->getUpdatedAt());
    }

    public function testCreatedAt()
    {
        $refreshToken = $this->getValidrefReshToken();
        $refreshToken->setCreatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $refreshToken->getCreatedAt());
    }
}
