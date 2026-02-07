<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Tasks;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectTaskRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tasks/'.urlencode($this->id);
    }
}
