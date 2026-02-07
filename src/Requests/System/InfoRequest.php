<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\System;

use Eloquage\DockerPhp\DataTransferObjects\SystemInfo;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Eloquage\DockerPhp\Support\NullCacheDriver;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class InfoRequest extends DockerRequest implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/info';
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
        return 60;
    }

    public function createDtoFromResponse(Response $response): SystemInfo
    {
        $data = $response->json();

        return SystemInfo::fromArray(is_array($data) ? $data : []);
    }
}
