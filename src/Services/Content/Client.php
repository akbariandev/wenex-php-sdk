<?php

namespace WenexPlatform\PhpSdk\Services\Content;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?NotesService $notes = null;
    protected ?PostsService $posts = null;
    protected ?TicketsService $tickets = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getNotes(): NotesService
    {
        return $this->notes ??= new NotesService($this->httpClient, $this->pathPrefix);
    }

    public function getPosts(): PostsService
    {
        return $this->posts ??= new PostsService($this->httpClient, $this->pathPrefix);
    }

    public function getTickets(): TicketsService
    {
        return $this->tickets ??= new TicketsService($this->httpClient, $this->pathPrefix);
    }
}
