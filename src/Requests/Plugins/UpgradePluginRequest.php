<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasStreamBody;

class UpgradePluginRequest extends DockerRequest implements HasBody
{
    use HasStreamBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected mixed $data = null,
        protected ?string $remote = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/'.urlencode($this->name).'/upgrade';
    }

    protected function defaultQuery(): array
    {
        return $this->remote !== null ? ['remote' => $this->remote] : [];
    }

    protected function defaultBody(): mixed
    {
        return $this->data;
    }
}
