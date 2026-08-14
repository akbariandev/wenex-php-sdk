<?php

namespace WenexPlatform\PhpSdk\Services\Context;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class ConfigsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::CONTEXT_CONFIGS, $httpClient, $pathPrefix);
    }
}
