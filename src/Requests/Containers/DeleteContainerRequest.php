<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteContainerRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $id,
        protected ?bool $v = null,
        protected ?bool $force = null,
        protected ?bool $link = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id);
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->v !== null) {
            $query['v'] = $this->v ? 'true' : 'false';
        }
        if ($this->force !== null) {
            $query['force'] = $this->force ? 'true' : 'false';
        }
        if ($this->link !== null) {
            $query['link'] = $this->link ? 'true' : 'false';
        }

        return $query;
    }
}
