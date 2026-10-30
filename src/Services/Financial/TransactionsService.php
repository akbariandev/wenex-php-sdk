<?php

namespace WenexPlatform\PhpSdk\Services\Financial;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class TransactionsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::FINANCIAL_TRANSACTIONS, $httpClient, $pathPrefix);
    }

    /**
     * Initialize a new transaction.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function init(array $data, array $config = []): array
    {
        $response = $this->post($this->url('init'), $data, Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Abort a pending transaction.
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
     * Verify a transaction (confirm settlement).
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function verify(string $id, array $config = []): array
    {
        $response = $this->get($this->url($id . '/verify'), Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }
}
