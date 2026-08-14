<?php

namespace WenexPlatform\PhpSdk\Services\General;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class CommentsService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::GENERAL_COMMENTS, $httpClient, $pathPrefix);
    }
}
