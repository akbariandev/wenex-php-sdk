<?php

namespace WenexPlatform\PhpSdk\Services\Logistic;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdk\Services\Concerns\BuildsClient;

final class Client
{
    use BuildsClient;

    protected ?CargoesService $cargoes = null;
    protected ?DriversService $drivers = null;
    protected ?VehiclesService $vehicles = null;
    protected ?TravelsService $travels = null;
    protected ?LocationsService $locations = null;

    public function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getCargoes(): CargoesService
    {
        return $this->cargoes ??= new CargoesService($this->httpClient, $this->pathPrefix);
    }

    public function getDrivers(): DriversService
    {
        return $this->drivers ??= new DriversService($this->httpClient, $this->pathPrefix);
    }

    public function getVehicles(): VehiclesService
    {
        return $this->vehicles ??= new VehiclesService($this->httpClient, $this->pathPrefix);
    }

    public function getTravels(): TravelsService
    {
        return $this->travels ??= new TravelsService($this->httpClient, $this->pathPrefix);
    }

    public function getLocations(): LocationsService
    {
        return $this->locations ??= new LocationsService($this->httpClient, $this->pathPrefix);
    }
}
