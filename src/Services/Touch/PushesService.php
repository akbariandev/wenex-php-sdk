<?php

namespace WenexPlatform\PhpSdk\Services\Touch;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class PushesService extends RestfulService
{
    protected ?PushHistoriesService $histories = null;

    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::TOUCH_PUSHES, $httpClient, $pathPrefix);
    }

    /**
     * Send a push notification.
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
     * Access the push-histories sub-service.
     */
    public function getHistories(): PushHistoriesService
    {
        return $this->histories ??= new PushHistoriesService($this->httpClient, $this->pathPrefix);
    }
}
