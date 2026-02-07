<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectContainerRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?bool $size = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/json';
    }

    protected function defaultQuery(): array
    {
        return $this->size !== null ? ['size' => $this->size ? 'true' : 'false'] : [];
    }
}
