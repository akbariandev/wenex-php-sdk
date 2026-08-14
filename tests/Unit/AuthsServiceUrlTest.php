<?php

namespace WenexPlatform\PhpSdk\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use WenexPlatform\PhpSdk\Services\Auth\AuthsService;

/**
 * Validates the auth-scoped URL builder via reflection (the method is protected).
 */
final class AuthsServiceUrlTest extends TestCase
{
    private AuthsService $service;

    protected function setUp(): void
    {
        $this->service = new AuthsService(new Client(), '/v1/');
    }

    private function url(string $path): string
    {
        $method = new ReflectionMethod($this->service, 'url');
        $method->setAccessible(true);

        return $method->invoke($this->service, $path);
    }

    public function testBuildsAuthPrefixedUrl(): void
    {
        self::assertSame('/v1/auth/token', $this->url('token'));
        self::assertSame('/v1/auth/verify', $this->url('verify'));
        self::assertSame('/v1/auth/logout', $this->url('logout'));
        self::assertSame('/v1/auth/check', $this->url('check'));
        self::assertSame('/v1/auth/can', $this->url('can'));
    }

    public function testDefaultPrefixIsSlash(): void
    {
        $service = new AuthsService(new Client());
        $method = new ReflectionMethod($service, 'url');
        $method->setAccessible(true);

        self::assertSame('/auth/token', $method->invoke($service, 'token'));
    }
}
