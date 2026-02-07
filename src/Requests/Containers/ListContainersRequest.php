<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\DataTransferObjects\ContainerSummary;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class ListContainersRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?bool $all = null,
        protected ?int $limit = null,
        protected ?bool $size = null,
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/json';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->all !== null) {
            $query['all'] = $this->all ? 'true' : 'false';
        }
        if ($this->limit !== null) {
            $query['limit'] = (string) $this->limit;
        }
        if ($this->size !== null) {
            $query['size'] = $this->size ? 'true' : 'false';
        }
        if ($this->filters !== null) {
            $query['filters'] = json_encode($this->filters);
        }

        return $query;
    }

    public function createDtoFromResponse(Response $response): array
    {
        $items = $response->json();
        if (! is_array($items)) {
            return [];
        }

        return array_map(fn (array $data): ContainerSummary => ContainerSummary::fromArray($data), $items);
    }
}
