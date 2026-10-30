<?php

namespace WenexPlatform\PhpSdk\Services\Special;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class StatsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::SPECIAL_STATS, $httpClient, $pathPrefix);
    }

    /**
     * Collect stat records; returns the collected items.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<int, mixed>
     */
    public function collect(array $data, array $config = []): array
    {
        $response = $this->post($this->url('collect'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['items'];
    }

    /**
     * Aggregate stats into a single result (OK/NOK).
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function stackup(array $data, array $config = []): array
    {
        return $this->post($this->url('stackup'), $data, Http::getOptions($config));
    }
}
