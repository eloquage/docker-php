<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\System;

use Eloquage\DockerPhp\DataTransferObjects\SystemVersion;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Eloquage\DockerPhp\Support\NullCacheDriver;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class VersionRequest extends DockerRequest implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/version';
    }

    public function resolveCacheDriver(): Driver
    {
        if (! config('docker-php.cache.enabled', true)) {
            return new NullCacheDriver;
        }

        $store = config('docker-php.cache.store') ?? config('cache.default', 'array');

        return new LaravelCacheDriver(Cache::store($store));
    }

    public function cacheExpiryInSeconds(): int
    {
        return 300;
    }

    public function createDtoFromResponse(Response $response): SystemVersion
    {
        $data = $response->json();

        return SystemVersion::fromArray(is_array($data) ? $data : []);
    }
}
