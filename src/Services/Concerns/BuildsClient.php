<?php

namespace WenexPlatform\PhpSdk\Services\Concerns;

use GuzzleHttp\Client;

/**
 * Provides the canonical {@see build()} factory for domain client aggregators.
 *
 * Every domain Client accepts a Guzzle {@see Client} and a path prefix in its
 * constructor, so the factory is identical across all of them.
 */
trait BuildsClient
{
    use MapsPropertyAccess;

    /**
     * Construct a client with a normalized path prefix (empty becomes "/").
     */
    public static function build(Client $httpClient, string $prefix = '/'): static
    {
        return new static($httpClient, $prefix ?: '/');
    }
}
