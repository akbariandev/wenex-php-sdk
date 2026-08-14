<?php

namespace WenexPlatform\PhpSdk\Services\Domain;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class ClientsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::DOMAIN_CLIENTS, $httpClient, $pathPrefix);
    }
}
