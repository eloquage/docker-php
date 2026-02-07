<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Networks;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class InspectNetworkRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?bool $verbose = null,
        protected ?string $scope = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/networks/'.urlencode($this->id);
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->verbose !== null) {
            $query['verbose'] = $this->verbose ? 'true' : 'false';
        }
        if ($this->scope !== null) {
            $query['scope'] = $this->scope;
        }

        return $query;
    }
}
