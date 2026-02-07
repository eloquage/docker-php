<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Distribution;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectDistributionRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $name,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/distribution/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name).'/json';
    }
}
