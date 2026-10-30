<?php

namespace WenexPlatform\PhpSdk\Services\Conjoint;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class MessagesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::CONJOINT_MESSAGES, $httpClient, $pathPrefix);
    }

    /**
     * Run an Elasticsearch query against messages.
     *
     * @param array<string, mixed> $request  Elasticsearch request body
     * @param array<string, mixed> $filter   Mongo-style filter merged into the query string
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function search(array $request, array $filter = [], array $config = []): array
    {
        $response = $this->post($this->url('search'), $request, Http::getOptions($config, $filter));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }
}
