<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\DataTransferObjects\ImageInspectDto;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Eloquage\DockerPhp\Support\NullCacheDriver;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class InspectImageRequest extends DockerRequest implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $name,
        protected ?bool $manifests = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name).'/json';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->manifests !== null) {
            $query['manifests'] = $this->manifests ? 'true' : 'false';
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

    public function createDtoFromResponse(Response $response): ImageInspectDto
    {
        $data = $response->json();

        return ImageInspectDto::fromArray(is_array($data) ? $data : []);
    }
}
