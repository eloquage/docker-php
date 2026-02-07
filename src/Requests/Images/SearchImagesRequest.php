<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\DataTransferObjects\ImageSearchResult;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class SearchImagesRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $term = null,
        protected ?int $limit = null,
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/search';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->term !== null) {
            $query['term'] = $this->term;
        }
        if ($this->limit !== null) {
            $query['limit'] = (string) $this->limit;
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

        return array_map(fn (array $data): ImageSearchResult => ImageSearchResult::fromArray($data), $items);
    }
}
