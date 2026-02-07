<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Support;

use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Data\CachedResponse;

class NullCacheDriver implements Driver
{
    public function set(string $key, CachedResponse $cachedResponse): void
    {
        // No-op when cache is disabled.
    }

    public function get(string $cacheKey): ?CachedResponse
    {
        return null;
    }

    public function delete(string $cacheKey): void
    {
        // No-op.
    }
}
