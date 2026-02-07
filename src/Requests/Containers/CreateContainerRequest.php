<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class CreateContainerRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
        protected ?string $name = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/create';
    }

    protected function defaultQuery(): array
    {
        return $this->name !== null ? ['name' => $this->name] : [];
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
