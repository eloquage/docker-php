<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Networks;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class DisconnectNetworkRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/networks/'.urlencode($this->id).'/disconnect';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
