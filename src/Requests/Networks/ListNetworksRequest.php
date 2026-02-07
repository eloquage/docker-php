<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Networks;

use Eloquage\DockerPhp\DataTransferObjects\NetworkSummary;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class ListNetworksRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/networks';
    }

    protected function defaultQuery(): array
    {
        return $this->filters !== null ? ['filters' => json_encode($this->filters)] : [];
    }

    public function createDtoFromResponse(Response $response): array
    {
        $items = $response->json();
        if (! is_array($items)) {
            return [];
        }

        return array_map(fn (array $data): NetworkSummary => NetworkSummary::fromArray($data), $items);
    }
}
