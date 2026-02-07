<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Services;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class CreateServiceRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
        protected ?string $XRegistryAuth = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/services/create';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    protected function defaultHeaders(): array
    {
        $headers = [];
        if ($this->XRegistryAuth !== null) {
            $headers['X-Registry-Auth'] = $this->XRegistryAuth;
        }

        return $headers;
    }
}
