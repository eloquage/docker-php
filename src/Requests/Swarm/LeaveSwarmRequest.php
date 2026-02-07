<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Swarm;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class LeaveSwarmRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected ?bool $force = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/swarm/leave';
    }

    protected function defaultQuery(): array
    {
        return $this->force !== null ? ['force' => $this->force ? 'true' : 'false'] : [];
    }
}
