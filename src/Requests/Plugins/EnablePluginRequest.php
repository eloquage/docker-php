<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class EnablePluginRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected ?int $timeout = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/'.urlencode($this->name).'/enable';
    }

    protected function defaultQuery(): array
    {
        return $this->timeout !== null ? ['timeout' => (string) $this->timeout] : [];
    }
}
