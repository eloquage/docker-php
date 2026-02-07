<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Nodes;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ListNodesRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/nodes';
    }

    protected function defaultQuery(): array
    {
        return $this->filters !== null ? ['filters' => json_encode($this->filters)] : [];
    }
}
