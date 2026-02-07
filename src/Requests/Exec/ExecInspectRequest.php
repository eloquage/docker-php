<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Exec;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ExecInspectRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/exec/'.urlencode($this->id).'/json';
    }
}
