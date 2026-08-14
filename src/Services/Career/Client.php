<?php

namespace WenexPlatform\PhpSdk\Services\Career;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?StocksService $stocks = null;
    protected ?StoresService $stores = null;
    protected ?ProductsService $products = null;
    protected ?BranchesService $branches = null;
    protected ?ServicesService $services = null;
    protected ?CustomersService $customers = null;
    protected ?EmployeesService $employees = null;
    protected ?BusinessesService $businesses = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getStocks(): StocksService
    {
        return $this->stocks ??= new StocksService($this->httpClient, $this->pathPrefix);
    }

    public function getStores(): StoresService
    {
        return $this->stores ??= new StoresService($this->httpClient, $this->pathPrefix);
    }

    public function getProducts(): ProductsService
    {
        return $this->products ??= new ProductsService($this->httpClient, $this->pathPrefix);
    }

    public function getBranches(): BranchesService
    {
        return $this->branches ??= new BranchesService($this->httpClient, $this->pathPrefix);
    }

    public function getServices(): ServicesService
    {
        return $this->services ??= new ServicesService($this->httpClient, $this->pathPrefix);
    }

    public function getCustomers(): CustomersService
    {
        return $this->customers ??= new CustomersService($this->httpClient, $this->pathPrefix);
    }

    public function getEmployees(): EmployeesService
    {
        return $this->employees ??= new EmployeesService($this->httpClient, $this->pathPrefix);
    }

    public function getBusinesses(): BusinessesService
    {
        return $this->businesses ??= new BusinessesService($this->httpClient, $this->pathPrefix);
    }
}
