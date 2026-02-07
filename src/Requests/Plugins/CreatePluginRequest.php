<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasStreamBody;

class CreatePluginRequest extends DockerRequest implements HasBody
{
    use HasStreamBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected mixed $data = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/create';
    }

    protected function defaultQuery(): array
    {
        return ['name' => $this->name];
    }

    protected function defaultBody(): mixed
    {
        return $this->data;
    }
}
