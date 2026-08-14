<?php

namespace WenexPlatform\PhpSdk\Services\Special;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?FilesService $files = null;
    protected ?StatsService $stats = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getFiles(): FilesService
    {
        return $this->files ??= new FilesService($this->httpClient, $this->pathPrefix);
    }

    public function getStats(): StatsService
    {
        return $this->stats ??= new StatsService($this->httpClient, $this->pathPrefix);
    }
}
