<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class SetPluginRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/'.urlencode($this->name).'/set';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
