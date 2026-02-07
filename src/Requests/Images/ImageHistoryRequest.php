<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\DataTransferObjects\ImageHistoryItem;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Eloquage\DockerPhp\Support\NullCacheDriver;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class ImageHistoryRequest extends DockerRequest implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $name,
        protected ?string $platform = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name).'/history';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->platform !== null) {
            $query['platform'] = $this->platform;
        }

        return $query;
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
        return 120;
    }

    public function createDtoFromResponse(Response $response): array
    {
        $items = $response->json();
        if (! is_array($items)) {
            return [];
        }

        return array_map(fn (array $data): ImageHistoryItem => ImageHistoryItem::fromArray($data), $items);
    }
}
