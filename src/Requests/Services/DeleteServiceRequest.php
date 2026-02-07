<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Services;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteServiceRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/services/'.urlencode($this->id);
    }
}
