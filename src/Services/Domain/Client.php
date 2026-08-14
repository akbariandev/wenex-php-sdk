<?php

namespace WenexPlatform\PhpSdk\Services\Domain;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?AppsService $apps = null;
    protected ?ClientsService $clients = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getApps(): AppsService
    {
        return $this->apps ??= new AppsService($this->httpClient, $this->pathPrefix);
    }

    public function getClients(): ClientsService
    {
        return $this->clients ??= new ClientsService($this->httpClient, $this->pathPrefix);
    }
}
