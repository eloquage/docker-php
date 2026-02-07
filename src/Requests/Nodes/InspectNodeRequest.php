<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Nodes;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectNodeRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/nodes/'.urlencode($this->id);
    }
}
