<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Networks;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class PruneNetworksRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/networks/prune';
    }

    protected function defaultQuery(): array
    {
        return $this->filters !== null ? ['filters' => json_encode($this->filters)] : [];
    }
}
