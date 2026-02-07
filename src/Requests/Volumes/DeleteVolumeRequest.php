<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Volumes;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteVolumeRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $name,
        protected ?bool $force = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/volumes/'.urlencode($this->name);
    }

    protected function defaultQuery(): array
    {
        return $this->force !== null ? ['force' => $this->force ? 'true' : 'false'] : [];
    }
}
