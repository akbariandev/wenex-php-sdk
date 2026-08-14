<?php

namespace WenexPlatform\PhpSdk\Services\Touch;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class NoticesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::TOUCH_NOTICES, $httpClient, $pathPrefix);
    }
}
