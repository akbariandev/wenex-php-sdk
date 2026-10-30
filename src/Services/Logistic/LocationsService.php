<?php

namespace WenexPlatform\PhpSdk\Services\Logistic;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class LocationsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::LOGISTIC_LOCATIONS, $httpClient, $pathPrefix);
    }

    /**
     * Resolve a free-text address to a place (single result).
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function addressLookup(array $data, array $config = []): array
    {
        $response = $this->post($this->url('address-lookup'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Geocode coordinates/address to candidate places (multiple results).
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<int, mixed>
     */
    public function geocodeLookup(array $data, array $config = []): array
    {
        $response = $this->post($this->url('geocode-lookup'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['items'];
    }
}
