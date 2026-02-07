<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class CreateImageRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $fromImage = null,
        protected ?string $fromSrc = null,
        protected ?string $repo = null,
        protected ?string $tag = null,
        protected ?string $message = null,
        protected ?string $platform = null,
        /** @var array<int, string>|null Dockerfile instructions e.g. ['ENV DEBUG=true'] */
        protected ?array $changes = null,
        protected ?string $xRegistryAuth = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/create';
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
        if ($this->fromImage !== null) {
            $query['fromImage'] = $this->fromImage;
        }
        if ($this->fromSrc !== null) {
            $query['fromSrc'] = $this->fromSrc;
        }
        if ($this->repo !== null) {
            $query['repo'] = $this->repo;
        }
        if ($this->tag !== null) {
            $query['tag'] = $this->tag;
        }
        if ($this->message !== null) {
            $query['message'] = $this->message;
        }
        if ($this->platform !== null) {
            $query['platform'] = $this->platform;
        }
        if ($this->changes !== null) {
            foreach ($this->changes as $change) {
                $query['changes'][] = $change;
            }
        }

        return $query;
    }
}
