<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ContainerTopRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?string $psArgs = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/top';
    }

    protected function defaultQuery(): array
    {
        return $this->psArgs !== null ? ['ps_args' => $this->psArgs] : [];
    }
}
