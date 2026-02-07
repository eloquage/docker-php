<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeletePluginRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $name,
        protected ?bool $force = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/'.urlencode($this->name);
    }

    protected function defaultQuery(): array
    {
        return $this->force !== null ? ['force' => $this->force ? 'true' : 'false'] : [];
    }
}
