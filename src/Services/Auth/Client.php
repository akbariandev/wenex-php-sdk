<?php

namespace WenexPlatform\PhpSdk\Services\Auth;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?AuthsService $auths = null;
    protected ?AptsService $apts = null;
    protected ?GrantsService $grants = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getAuths(): AuthsService
    {
        return $this->auths ??= new AuthsService($this->httpClient, $this->pathPrefix);
    }

    public function getApts(): AptsService
    {
        return $this->apts ??= new AptsService($this->httpClient, $this->pathPrefix);
    }

    public function getGrants(): GrantsService
    {
        return $this->grants ??= new GrantsService($this->httpClient, $this->pathPrefix);
    }
}
