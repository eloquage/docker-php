<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class KillContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?string $signal = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/kill';
    }

    protected function defaultQuery(): array
    {
        return $this->signal !== null ? ['signal' => $this->signal] : [];
    }
}
