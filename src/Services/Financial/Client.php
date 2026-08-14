<?php

namespace WenexPlatform\PhpSdk\Services\Financial;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?WalletsService $wallets = null;
    protected ?AccountsService $accounts = null;
    protected ?InvoicesService $invoices = null;
    protected ?CurrenciesService $currencies = null;
    protected ?TransactionsService $transactions = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getWallets(): WalletsService
    {
        return $this->wallets ??= new WalletsService($this->httpClient, $this->pathPrefix);
    }

    public function getAccounts(): AccountsService
    {
        return $this->accounts ??= new AccountsService($this->httpClient, $this->pathPrefix);
    }

    public function getInvoices(): InvoicesService
    {
        return $this->invoices ??= new InvoicesService($this->httpClient, $this->pathPrefix);
    }

    public function getCurrencies(): CurrenciesService
    {
        return $this->currencies ??= new CurrenciesService($this->httpClient, $this->pathPrefix);
    }

    public function getTransactions(): TransactionsService
    {
        return $this->transactions ??= new TransactionsService($this->httpClient, $this->pathPrefix);
    }
}
