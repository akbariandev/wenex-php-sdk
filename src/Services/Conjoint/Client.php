<?php

namespace WenexPlatform\PhpSdk\Services\Conjoint;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?MembersService $members = null;
    protected ?AccountsService $accounts = null;
    protected ?ChannelsService $channels = null;
    protected ?ContactsService $contacts = null;
    protected ?MessagesService $messages = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getMembers(): MembersService
    {
        return $this->members ??= new MembersService($this->httpClient, $this->pathPrefix);
    }

    public function getAccounts(): AccountsService
    {
        return $this->accounts ??= new AccountsService($this->httpClient, $this->pathPrefix);
    }

    public function getChannels(): ChannelsService
    {
        return $this->channels ??= new ChannelsService($this->httpClient, $this->pathPrefix);
    }

    public function getContacts(): ContactsService
    {
        return $this->contacts ??= new ContactsService($this->httpClient, $this->pathPrefix);
    }

    public function getMessages(): MessagesService
    {
        return $this->messages ??= new MessagesService($this->httpClient, $this->pathPrefix);
    }
}
