<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Services;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ListServicesRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
        protected ?bool $status = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/services';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->filters !== null) {
            $query['filters'] = json_encode($this->filters);
        }
        if ($this->status !== null) {
            $query['status'] = $this->status ? 'true' : 'false';
        }

        return $query;
    }
}
