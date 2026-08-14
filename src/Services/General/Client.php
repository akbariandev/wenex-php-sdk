<?php

namespace WenexPlatform\PhpSdk\Services\General;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?EventsService $events = null;
    protected ?CommentsService $comments = null;
    protected ?ArtifactsService $artifacts = null;
    protected ?WorkflowsService $workflows = null;
    protected ?ActivitiesService $activities = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getEvents(): EventsService
    {
        return $this->events ??= new EventsService($this->httpClient, $this->pathPrefix);
    }

    public function getComments(): CommentsService
    {
        return $this->comments ??= new CommentsService($this->httpClient, $this->pathPrefix);
    }

    public function getArtifacts(): ArtifactsService
    {
        return $this->artifacts ??= new ArtifactsService($this->httpClient, $this->pathPrefix);
    }

    public function getWorkflows(): WorkflowsService
    {
        return $this->workflows ??= new WorkflowsService($this->httpClient, $this->pathPrefix);
    }

    public function getActivities(): ActivitiesService
    {
        return $this->activities ??= new ActivitiesService($this->httpClient, $this->pathPrefix);
    }
}
