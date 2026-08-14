<?php

namespace WenexPlatform\PhpSdk\Services\Auth;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class GrantsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::AUTH_GRANTS, $httpClient, $pathPrefix);
    }
}
