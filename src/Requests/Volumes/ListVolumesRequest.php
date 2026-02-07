<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Volumes;

use Eloquage\DockerPhp\DataTransferObjects\VolumeSummary;
use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

class ListVolumesRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?array $filters = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/volumes';
    }

    protected function defaultQuery(): array
    {
        return $this->filters !== null ? ['filters' => json_encode($this->filters)] : [];
    }

    public function createDtoFromResponse(Response $response): array
    {
        $body = $response->json();
        if (! is_array($body) || ! isset($body['Volumes']) || ! is_array($body['Volumes'])) {
            return [];
        }

        return array_map(fn (array $data): VolumeSummary => VolumeSummary::fromArray($data), $body['Volumes']);
    }
}
