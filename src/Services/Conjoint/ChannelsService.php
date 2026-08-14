<?php

namespace WenexPlatform\PhpSdk\Services\Conjoint;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class ChannelsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::CONJOINT_CHANNELS, $httpClient, $pathPrefix);
    }
}
