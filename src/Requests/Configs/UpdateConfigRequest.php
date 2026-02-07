<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Configs;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class UpdateConfigRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected array $data,
        protected ?int $version = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/configs/'.urlencode($this->id).'/update';
    }

    protected function defaultQuery(): array
    {
        return $this->version !== null ? ['version' => (string) $this->version] : [];
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
