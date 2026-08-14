<?php

namespace WenexPlatform\PhpSdk\Services\Touch;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?EmailsService $emails = null;
    protected ?SmssService $smss = null;
    protected ?PushesService $pushes = null;
    protected ?NoticesService $notices = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getEmails(): EmailsService
    {
        return $this->emails ??= new EmailsService($this->httpClient, $this->pathPrefix);
    }

    public function getSmss(): SmssService
    {
        return $this->smss ??= new SmssService($this->httpClient, $this->pathPrefix);
    }

    public function getPushes(): PushesService
    {
        return $this->pushes ??= new PushesService($this->httpClient, $this->pathPrefix);
    }

    public function getNotices(): NoticesService
    {
        return $this->notices ??= new NoticesService($this->httpClient, $this->pathPrefix);
    }
}
