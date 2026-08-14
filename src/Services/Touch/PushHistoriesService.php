<?php

namespace WenexPlatform\PhpSdk\Services\Touch;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class PushHistoriesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::TOUCH_HISTORIES, $httpClient, $pathPrefix);
    }
}
