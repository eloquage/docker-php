<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\System;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class GetEventsRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $since = null,
        protected ?string $until = null,
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/events';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->since !== null) {
            $query['since'] = $this->since;
        }
        if ($this->until !== null) {
            $query['until'] = $this->until;
        }
        if ($this->filters !== null) {
            $query['filters'] = json_encode($this->filters);
        }

        return $query;
    }
}
