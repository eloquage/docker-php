<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ResizeContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?int $h = null,
        protected ?int $w = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/resize';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->h !== null) {
            $query['h'] = (string) $this->h;
        }
        if ($this->w !== null) {
            $query['w'] = (string) $this->w;
        }

        return $query;
    }
}
