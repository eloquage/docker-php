<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Auth;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class AuthRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/auth';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
