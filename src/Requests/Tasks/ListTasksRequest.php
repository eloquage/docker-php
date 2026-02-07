<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Tasks;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ListTasksRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tasks';
    }

    protected function defaultQuery(): array
    {
        return $this->filters !== null ? ['filters' => json_encode($this->filters)] : [];
    }
}
