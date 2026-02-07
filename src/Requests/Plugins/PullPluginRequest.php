<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Plugins;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class PullPluginRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $remote = null,
        protected ?string $name = null,
        protected array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/plugins/pull';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->remote !== null) {
            $query['remote'] = $this->remote;
        }
        if ($this->name !== null) {
            $query['name'] = $this->name;
        }

        return $query;
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
