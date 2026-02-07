<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Swarm;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class GetSwarmRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/swarm';
    }
}
