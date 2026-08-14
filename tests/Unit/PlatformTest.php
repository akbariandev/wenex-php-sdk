<?php

namespace WenexPlatform\PhpSdk\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use WenexPlatform\PhpSdk\Platform;
use WenexPlatform\PhpSdk\Services\Auth\AuthsService;
use WenexPlatform\PhpSdk\Services\Auth\Client as AuthClient;
use WenexPlatform\PhpSdk\Services\Career\Client as CareerClient;
use WenexPlatform\PhpSdk\Services\Conjoint\Client as ConjointClient;
use WenexPlatform\PhpSdk\Services\Content\Client as ContentClient;
use WenexPlatform\PhpSdk\Services\Context\Client as ContextClient;
use WenexPlatform\PhpSdk\Services\Domain\Client as DomainClient;
use WenexPlatform\PhpSdk\Services\Essential\Client as EssentialClient;
use WenexPlatform\PhpSdk\Services\Essential\SagaStagesService;
use WenexPlatform\PhpSdk\Services\Financial\Client as FinancialClient;
use WenexPlatform\PhpSdk\Services\General\Client as GeneralClient;
use WenexPlatform\PhpSdk\Services\Identity\Client as IdentityClient;
use WenexPlatform\PhpSdk\Services\Identity\UsersService;
use WenexPlatform\PhpSdk\Services\Logistic\Client as LogisticClient;
use WenexPlatform\PhpSdk\Services\Special\Client as SpecialClient;
use WenexPlatform\PhpSdk\Services\Thing\Client as ThingClient;
use WenexPlatform\PhpSdk\Services\Touch\Client as TouchClient;
use WenexPlatform\PhpSdk\Services\Touch\PushHistoriesService;
use WenexPlatform\PhpSdkCommon\Core\Classes\GraphqlService;

/**
 * Smoke tests for the Platform entry point and domain wiring.
 *
 * Mirrors the TypeScript {@code test/index.spec.ts} contract: a Platform built
 * from a Guzzle client must expose every domain client. No network calls.
 */
final class PlatformTest extends TestCase
{
    private Platform $platform;

    protected function setUp(): void
    {
        $this->platform = Platform::build(new Client(['base_uri' => 'https://api.example.com/']));
    }

    public function testPlatformInstanceIsBuilt(): void
    {
        self::assertInstanceOf(Platform::class, $this->platform);
    }

    public function testEmptyPrefixIsNormalizedToSlash(): void
    {
        $platform = Platform::build(new Client(), '');
        // Accessing a domain client should not throw; prefix normalization happens in build().
        self::assertInstanceOf(AuthClient::class, $platform->getAuth());
    }

    public function testExposesEveryDomainClient(): void
    {
        self::assertInstanceOf(AuthClient::class, $this->platform->getAuth());
        self::assertInstanceOf(ContextClient::class, $this->platform->getContext());
        self::assertInstanceOf(DomainClient::class, $this->platform->getDomain());
        self::assertInstanceOf(EssentialClient::class, $this->platform->getEssential());
        self::assertInstanceOf(FinancialClient::class, $this->platform->getFinancial());
        self::assertInstanceOf(GeneralClient::class, $this->platform->getGeneral());
        self::assertInstanceOf(IdentityClient::class, $this->platform->getIdentity());
        self::assertInstanceOf(SpecialClient::class, $this->platform->getSpecial());
        self::assertInstanceOf(TouchClient::class, $this->platform->getTouch());
        self::assertInstanceOf(ContentClient::class, $this->platform->getContent());
        self::assertInstanceOf(LogisticClient::class, $this->platform->getLogistic());
        self::assertInstanceOf(ConjointClient::class, $this->platform->getConjoint());
        self::assertInstanceOf(CareerClient::class, $this->platform->getCareer());
        self::assertInstanceOf(ThingClient::class, $this->platform->getThing());
    }

    public function testExposesGraphqlService(): void
    {
        self::assertInstanceOf(GraphqlService::class, $this->platform->getGraphql());
    }

    public function testDomainClientsAreMemoized(): void
    {
        self::assertSame($this->platform->getIdentity(), $this->platform->getIdentity());
    }

    public function testPropertyAccessShimMirrorsGetters(): void
    {
        // Exercises the MapsPropertyAccess magic; static analysis can't see through __get.
        $identity = $this->platform->identity; // @phpstan-ignore property.protected
        self::assertInstanceOf(IdentityClient::class, $identity);
        self::assertInstanceOf(UsersService::class, $identity->users); // @phpstan-ignore property.protected
        self::assertSame($this->platform->getIdentity(), $identity);
    }

    public function testPropertyAccessThrowsForUnknownDomain(): void
    {
        $this->expectException(\Error::class);
        $undefined = $this->platform->nonexistent; // @phpstan-ignore property.notFound
        unset($undefined);
    }

    public function testNestedSubServicesAreReachable(): void
    {
        self::assertInstanceOf(SagaStagesService::class, $this->platform->getEssential()->getSagas()->getStages());
        self::assertInstanceOf(PushHistoriesService::class, $this->platform->getTouch()->getPushes()->getHistories());
    }

    public function testAuthsServiceIsExposedUnderAuth(): void
    {
        self::assertInstanceOf(AuthsService::class, $this->platform->getAuth()->getAuths());
    }

    public function testStandardCrudServiceIsExposed(): void
    {
        self::assertInstanceOf(UsersService::class, $this->platform->getIdentity()->getUsers());
    }
}
