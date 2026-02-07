<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Secrets;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ListSecretsRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/secrets';
    }

    protected function defaultQuery(): array
    {
        return $this->filters !== null ? ['filters' => json_encode($this->filters)] : [];
    }
}
