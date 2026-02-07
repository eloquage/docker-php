<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\System;

use Eloquage\DockerPhp\DataTransferObjects\DiskUsage;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class SystemDfRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/system/df';
    }

    public function createDtoFromResponse(Response $response): DiskUsage
    {
        $data = $response->json();

        return DiskUsage::fromArray(is_array($data) ? $data : []);
    }
}
