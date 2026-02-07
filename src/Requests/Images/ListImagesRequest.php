<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\DataTransferObjects\ImageSummary;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class ListImagesRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
        protected ?bool $all = null,
        protected ?bool $digests = null,
        protected ?bool $sharedSize = null,
        protected ?bool $manifests = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/json';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->filters !== null) {
            $query['filters'] = json_encode($this->filters);
        }
        if ($this->all !== null) {
            $query['all'] = $this->all ? 'true' : 'false';
        }
        if ($this->digests !== null) {
            $query['digests'] = $this->digests ? 'true' : 'false';
        }
        if ($this->sharedSize !== null) {
            $query['shared-size'] = $this->sharedSize ? 'true' : 'false';
        }
        if ($this->manifests !== null) {
            $query['manifests'] = $this->manifests ? 'true' : 'false';
        }

        return $query;
    }

    public function createDtoFromResponse(Response $response): array
    {
        $items = $response->json();
        if (! is_array($items)) {
            return [];
        }

        return array_map(fn (array $data): ImageSummary => ImageSummary::fromArray($data), $items);
    }
}
