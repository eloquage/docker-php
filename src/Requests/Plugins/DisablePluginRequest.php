<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DisablePluginRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/'.urlencode($this->name).'/disable';
    }
}
