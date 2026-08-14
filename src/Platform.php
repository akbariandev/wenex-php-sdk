<?php

namespace WenexPlatform\PhpSdk;

use GuzzleHttp\Client;
use WenexPlatform\PhpSdk\Services\Auth\Client as AuthClient;
use WenexPlatform\PhpSdk\Services\Career\Client as CareerClient;
use WenexPlatform\PhpSdk\Services\Concerns\MapsPropertyAccess;
use WenexPlatform\PhpSdk\Services\Conjoint\Client as ConjointClient;
use WenexPlatform\PhpSdk\Services\Content\Client as ContentClient;
use WenexPlatform\PhpSdk\Services\Context\Client as ContextClient;
use WenexPlatform\PhpSdk\Services\Domain\Client as DomainClient;
use WenexPlatform\PhpSdk\Services\Essential\Client as EssentialClient;
use WenexPlatform\PhpSdk\Services\Financial\Client as FinancialClient;
use WenexPlatform\PhpSdk\Services\General\Client as GeneralClient;
use WenexPlatform\PhpSdk\Services\Identity\Client as IdentityClient;
use WenexPlatform\PhpSdk\Services\Logistic\Client as LogisticClient;
use WenexPlatform\PhpSdk\Services\Special\Client as SpecialClient;
use WenexPlatform\PhpSdk\Services\Thing\Client as ThingClient;
use WenexPlatform\PhpSdk\Services\Touch\Client as TouchClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\GraphqlService;

/**
 * Entry point of the Wenex platform SDK.
 *
 * Wraps a single pre-configured Guzzle {@see Client} (base URI, Authorization
 * header, timeouts, ...) and exposes each platform domain as a lazily-built
 * client aggregator. Mirrors the TypeScript {@code Platform} class.
 */
class Platform
{
    use MapsPropertyAccess;
    protected ?AuthClient $auth = null;
    protected ?ContextClient $context = null;
    protected ?DomainClient $domain = null;
    protected ?EssentialClient $essential = null;
    protected ?FinancialClient $financial = null;
    protected ?GeneralClient $general = null;
    protected ?IdentityClient $identity = null;
    protected ?SpecialClient $special = null;
    protected ?TouchClient $touch = null;
    protected ?ContentClient $content = null;
    protected ?LogisticClient $logistic = null;
    protected ?ConjointClient $conjoint = null;
    protected ?CareerClient $career = null;
    protected ?ThingClient $thing = null;
    protected ?GraphqlService $graphql = null;

    public function __construct(
        protected readonly Client $httpClient,
        protected readonly string $pathPrefix = '/',
    ) {
    }

    public function getAuth(): AuthClient
    {
        return $this->auth ??= AuthClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getContext(): ContextClient
    {
        return $this->context ??= ContextClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getDomain(): DomainClient
    {
        return $this->domain ??= DomainClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getEssential(): EssentialClient
    {
        return $this->essential ??= EssentialClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getFinancial(): FinancialClient
    {
        return $this->financial ??= FinancialClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getGeneral(): GeneralClient
    {
        return $this->general ??= GeneralClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getIdentity(): IdentityClient
    {
        return $this->identity ??= IdentityClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getSpecial(): SpecialClient
    {
        return $this->special ??= SpecialClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getTouch(): TouchClient
    {
        return $this->touch ??= TouchClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getContent(): ContentClient
    {
        return $this->content ??= ContentClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getLogistic(): LogisticClient
    {
        return $this->logistic ??= LogisticClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getConjoint(): ConjointClient
    {
        return $this->conjoint ??= ConjointClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getCareer(): CareerClient
    {
        return $this->career ??= CareerClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getThing(): ThingClient
    {
        return $this->thing ??= ThingClient::build($this->httpClient, $this->pathPrefix);
    }

    public function getGraphql(): GraphqlService
    {
        return $this->graphql ??= GraphqlService::build($this->httpClient, $this->pathPrefix);
    }

    /**
     * Construct a Platform with a normalized path prefix (empty becomes "/").
     */
    public static function build(Client $httpClient, string $prefix = '/'): self
    {
        return new self($httpClient, $prefix ?: '/');
    }
}
