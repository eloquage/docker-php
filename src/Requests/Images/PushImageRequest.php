<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class PushImageRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected ?string $tag = null,
        protected ?string $platform = null,
        protected ?string $xRegistryAuth = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name).'/push';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultHeaders(): array
    {
        $headers = [];
        if ($this->xRegistryAuth !== null) {
            $headers['X-Registry-Auth'] = $this->xRegistryAuth;
        }

        return array_merge(parent::defaultHeaders(), $headers);
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->tag !== null) {
            $query['tag'] = $this->tag;
        }
        if ($this->platform !== null) {
            $query['platform'] = $this->platform;
        }

        return $query;
    }
}
