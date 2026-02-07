<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Exec;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class ExecStartRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/exec/'.urlencode($this->id).'/start';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
