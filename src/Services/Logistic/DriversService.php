<?php

namespace WenexPlatform\PhpSdk\Services\Logistic;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class DriversService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::LOGISTIC_DRIVERS, $httpClient, $pathPrefix);
    }
}
