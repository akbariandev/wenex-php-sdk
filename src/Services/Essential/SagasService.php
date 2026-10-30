<?php

namespace WenexPlatform\PhpSdk\Services\Essential;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class SagasService extends RestfulService
{
    protected ?SagaStagesService $stages = null;

    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::ESSENTIAL_SAGAS, $httpClient, $pathPrefix);
    }

    /**
     * Start a new saga.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function start(array $data, array $config = []): array
    {
        $response = $this->post($this->url('start'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Add a stage to the current saga.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function add(array $data, array $config = []): array
    {
        $response = $this->post($this->url('add'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Abort a saga.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function abort(string $id, array $config = []): array
    {
        $response = $this->get($this->url($id . '/abort'), Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Commit (finalize) a saga.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function commit(string $id, array $config = []): array
    {
        $response = $this->get($this->url($id . '/commit'), Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Access the saga-stages sub-service.
     */
    public function getStages(): SagaStagesService
    {
        return $this->stages ??= new SagaStagesService($this->httpClient, $this->pathPrefix);
    }
}
