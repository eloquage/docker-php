<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Configs;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class CreateConfigRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/configs/create';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
