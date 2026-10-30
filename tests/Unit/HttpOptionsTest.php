<?php

namespace WenexPlatform\PhpSdk\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

/**
 * Pins the request-config semantics shared by every service: only `params`
 * contributes to the query string and only SDK-consumed keys are withheld
 * from Guzzle.
 */
final class HttpOptionsTest extends TestCase
{
    public function testParamsComeFromFilterAndExplicitParamsOnly(): void
    {
        $params = Http::getParams(
            [
                'fullResponse' => true,
                'headers' => ['X-Test' => 'yes'],
                'params' => ['zone' => 'own'],
            ],
            ['query' => ['status' => 'active']],
        );

        self::assertSame(['query' => ['status' => 'active'], 'zone' => 'own'], $params);
        self::assertArrayNotHasKey('fullResponse', $params);
        self::assertArrayNotHasKey('headers', $params);
    }

    public function testOptionsForwardGuzzleKeysAndDropClientKeys(): void
    {
        $options = Http::getOptions(
            [
                'headers' => ['Authorization' => 'Bearer token'],
                'timeout' => 5,
                'fullResponse' => true,
                'brotli' => true,
                'prefix' => '/v1/',
                'responseType' => 'blob',
                'params' => ['zone' => 'own'],
            ],
            ['query' => []],
        );

        self::assertSame(['Authorization' => 'Bearer token'], $options['headers']);
        self::assertSame(5, $options['timeout']);
        self::assertSame(['query' => [], 'zone' => 'own'], $options['query']);

        foreach (['fullResponse', 'brotli', 'prefix', 'responseType'] as $clientKey) {
            self::assertArrayNotHasKey($clientKey, $options);
        }
    }

    public function testOptionsOmitQueryWhenThereAreNoParameters(): void
    {
        self::assertSame([], Http::getOptions([], []));
        self::assertSame(['timeout' => 2], Http::getOptions(['timeout' => 2]));
    }
}
