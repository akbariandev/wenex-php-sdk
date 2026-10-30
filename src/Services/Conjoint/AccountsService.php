<?php

namespace WenexPlatform\PhpSdk\Services\Conjoint;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class AccountsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::CONJOINT_ACCOUNTS, $httpClient, $pathPrefix);
    }

    /**
     * Issue temporary account credentials.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function cred(array $config = []): array
    {
        return $this->post($this->url('cred'), [], Http::getOptions($config));
    }
}
