<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class StartContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?string $detachKeys = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/start';
    }

    protected function defaultQuery(): array
    {
        return $this->detachKeys !== null ? ['detachKeys' => $this->detachKeys] : [];
    }
}
