<?php

namespace WenexPlatform\PhpSdk\Services\Thing;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?DevicesService $devices = null;
    protected ?SensorsService $sensors = null;
    protected ?MetricsService $metrics = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getDevices(): DevicesService
    {
        return $this->devices ??= new DevicesService($this->httpClient, $this->pathPrefix);
    }

    public function getSensors(): SensorsService
    {
        return $this->sensors ??= new SensorsService($this->httpClient, $this->pathPrefix);
    }

    public function getMetrics(): MetricsService
    {
        return $this->metrics ??= new MetricsService($this->httpClient, $this->pathPrefix);
    }
}
