<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Nodes;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteNodeRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $id,
        protected ?bool $force = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/nodes/'.urlencode($this->id);
    }

    protected function defaultQuery(): array
    {
        return $this->force !== null ? ['force' => $this->force ? 'true' : 'false'] : [];
    }
}
