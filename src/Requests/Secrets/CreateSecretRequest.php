<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Secrets;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class CreateSecretRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/secrets/create';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
