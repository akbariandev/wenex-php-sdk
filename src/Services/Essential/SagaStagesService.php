<?php

namespace WenexPlatform\PhpSdk\Services\Essential;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class SagaStagesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::ESSENTIAL_SAGA_STAGES, $httpClient, $pathPrefix);
    }
}
