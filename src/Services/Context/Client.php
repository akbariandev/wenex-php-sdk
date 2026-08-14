<?php

namespace WenexPlatform\PhpSdk\Services\Context;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?ConfigsService $configs = null;
    protected ?SettingsService $settings = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getConfigs(): ConfigsService
    {
        return $this->configs ??= new ConfigsService($this->httpClient, $this->pathPrefix);
    }

    public function getSettings(): SettingsService
    {
        return $this->settings ??= new SettingsService($this->httpClient, $this->pathPrefix);
    }
}
