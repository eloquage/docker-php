<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Networks;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteNetworkRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/networks/'.urlencode($this->id);
    }
}
