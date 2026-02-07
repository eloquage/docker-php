<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Build;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasStreamBody;

class BuildRequest extends DockerRequest implements HasBody
{
    use HasStreamBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected mixed $data = null,
        protected array $query = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/build';
    }

    protected function defaultQuery(): array
    {
        return $this->query;
    }

    protected function defaultBody(): mixed
    {
        return $this->data;
    }
}
