<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Configs;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteConfigRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/configs/'.urlencode($this->id);
    }
}
