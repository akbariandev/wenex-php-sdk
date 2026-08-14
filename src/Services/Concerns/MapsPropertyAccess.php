<?php

namespace WenexPlatform\PhpSdk\Services\Concerns;

/**
 * Allows property-style access to lazily-built services, e.g. {@code $client->users}
 * resolves to {@code getUsers()}. Mirrors the TS SDK's getter properties.
 */
trait MapsPropertyAccess
{
    /**
     * @return object
     */
    public function __get(string $name): object
    {
        $getter = 'get' . str_replace('_', '', ucwords($name, '_'));

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        throw new \Error(sprintf('Undefined property: %s::$%s', static::class, $name));
    }
}
