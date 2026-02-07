<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Volumes;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectVolumeRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $name,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/volumes/'.urlencode($this->name);
    }
}
