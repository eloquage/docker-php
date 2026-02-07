<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class StopContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?int $t = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/stop';
    }

    protected function defaultQuery(): array
    {
        return $this->t !== null ? ['t' => (string) $this->t] : [];
    }
}
