<?php

namespace WenexPlatform\PhpSdk\Services\Financial;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class InvoicesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::FINANCIAL_INVOICES, $httpClient, $pathPrefix);
    }

    /**
     * Pay an invoice, returning the resulting transaction.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function payment(string $id, array $config = []): array
    {
        $response = $this->get($this->url($id . '/payment'), Http::getOptions($config));

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }
}
