<?php

namespace WenexPlatform\PhpSdk\Services\Touch;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class SmssService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::TOUCH_SMSS, $httpClient, $pathPrefix);
    }

    /**
     * Send an SMS.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function send(array $data, array $config = []): array
    {
        $response = $this->post($this->url('send'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Send an SMS using a pre-defined template.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function sendByTemplate(array $data, array $config = []): array
    {
        $response = $this->post($this->url('send/template'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }
}
