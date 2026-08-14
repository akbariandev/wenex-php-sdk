<?php

namespace WenexPlatform\PhpSdk\Services\Essential;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?SagasService $sagas = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    /**
     * Saga stages are reachable via {@see SagasService::getStages()}.
     */
    public function getSagas(): SagasService
    {
        return $this->sagas ??= new SagasService($this->httpClient, $this->pathPrefix);
    }
}
