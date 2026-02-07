<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class UnpauseContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/unpause';
    }
}
