<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Services;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class UpdateServiceRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected array $data,
        protected ?int $version = null,
        protected ?string $registryAuthFrom = null,
        protected ?string $rollback = null,
        protected ?string $XRegistryAuth = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/services/'.urlencode($this->id).'/update';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->version !== null) {
            $query['version'] = (string) $this->version;
        }
        if ($this->registryAuthFrom !== null) {
            $query['registryAuthFrom'] = $this->registryAuthFrom;
        }
        if ($this->rollback !== null) {
            $query['rollback'] = $this->rollback;
        }

        return $query;
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    protected function defaultHeaders(): array
    {
        return $this->XRegistryAuth !== null ? ['X-Registry-Auth' => $this->XRegistryAuth] : [];
    }
}
