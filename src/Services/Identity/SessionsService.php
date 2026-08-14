<?php

namespace WenexPlatform\PhpSdk\Services\Identity;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class SessionsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::IDENTITY_SESSIONS, $httpClient, $pathPrefix);
    }
}
