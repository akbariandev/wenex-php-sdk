<?php

namespace WenexPlatform\PhpSdk\Services\Logistic;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class TravelsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::LOGISTIC_TRAVELS, $httpClient, $pathPrefix);
    }

    /**
     * Compute a routing between geographic points.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function routing(array $data, array $config = []): array
    {
        $response = $this->post($this->url('routing'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }
}
