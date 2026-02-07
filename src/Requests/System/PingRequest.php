<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\System;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class PingRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/_ping';
    }
}
