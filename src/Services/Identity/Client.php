<?php

namespace WenexPlatform\PhpSdk\Services\Identity;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?UsersService $users = null;
    protected ?ProfilesService $profiles = null;
    protected ?SessionsService $sessions = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getUsers(): UsersService
    {
        return $this->users ??= new UsersService($this->httpClient, $this->pathPrefix);
    }

    public function getProfiles(): ProfilesService
    {
        return $this->profiles ??= new ProfilesService($this->httpClient, $this->pathPrefix);
    }

    public function getSessions(): SessionsService
    {
        return $this->sessions ??= new SessionsService($this->httpClient, $this->pathPrefix);
    }
}
