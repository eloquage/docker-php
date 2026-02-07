<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ContainerStatsRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?bool $stream = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/stats';
    }

    protected function defaultQuery(): array
    {
        return $this->stream !== null ? ['stream' => $this->stream ? 'true' : 'false'] : [];
    }
}
