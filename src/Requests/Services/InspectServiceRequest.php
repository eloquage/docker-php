<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Services;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectServiceRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?bool $insertDefaults = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/services/'.urlencode($this->id);
    }

    protected function defaultQuery(): array
    {
        return $this->insertDefaults !== null ? ['insertDefaults' => $this->insertDefaults ? 'true' : 'false'] : [];
    }
}
