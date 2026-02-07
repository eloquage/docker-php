<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Swarm;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class JoinSwarmRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/swarm/join';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
