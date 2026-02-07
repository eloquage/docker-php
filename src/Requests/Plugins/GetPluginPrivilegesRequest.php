<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class GetPluginPrivilegesRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $remote = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/privileges';
    }

    protected function defaultQuery(): array
    {
        return $this->remote !== null ? ['remote' => $this->remote] : [];
    }
}
