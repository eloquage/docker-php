<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class WaitContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?string $condition = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/wait';
    }

    protected function defaultQuery(): array
    {
        return $this->condition !== null ? ['condition' => $this->condition] : [];
    }
}
