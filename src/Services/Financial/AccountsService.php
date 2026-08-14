<?php

namespace WenexPlatform\PhpSdk\Services\Financial;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class AccountsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::FINANCIAL_ACCOUNTS, $httpClient, $pathPrefix);
    }
}
