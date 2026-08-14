<?php

namespace WenexPlatform\PhpSdk\Services\Career;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;

class BranchesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::CAREER_BRANCHES, $httpClient, $pathPrefix);
    }
}
