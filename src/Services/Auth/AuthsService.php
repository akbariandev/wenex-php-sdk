<?php

namespace WenexPlatform\PhpSdk\Services\Auth;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Providers\RequestService;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

/**
 * Authentication & authorization endpoints (POST /auth/token, GET /auth/verify, ...).
 *
 * Extends {@see RequestService} directly since these are not standard CRUD resources.
 */
class AuthsService extends RequestService
{
    protected string $pathPrefix;

    public function __construct(
        HttpClient $httpClient,
        string $pathPrefix = '/',
    ) {
        parent::__construct($httpClient);
        $this->pathPrefix = $pathPrefix;
    }

    /**
     * Build an auth-scoped URL: {prefix}auth/{path}.
     */
    protected function url(string $path, ?string $prefix = null): string
    {
        $prefix = $prefix ?? $this->pathPrefix;

        return ($prefix ?: '/') . 'auth/' . $path;
    }

    /**
     * Verify a one-time auth challenge (e.g. TOTP).
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function check(array $data, array $config = []): array
    {
        return $this->post($this->url('check'), $data, ['query' => Http::get_params($config)]);
    }

    /**
     * Exchange credentials for a token (OAuth-style grant).
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function token(array $data, array $config = []): array
    {
        return $this->post($this->url('token'), $data, ['query' => Http::get_params($config)]);
    }

    /**
     * Verify the current bearer token and return the decoded JWT payload.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function verify(array $config = []): array
    {
        return $this->get($this->url('verify'), ['query' => Http::get_params($config)]);
    }

    /**
     * Log out / invalidate the current session.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function logout(array $config = []): array
    {
        return $this->get($this->url('logout'), ['query' => Http::get_params($config)]);
    }

    /**
     * Check whether the caller is granted an action on a resource.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function can(array $data, array $config = []): array
    {
        return $this->post($this->url('can'), $data, ['query' => Http::get_params($config)]);
    }
}
