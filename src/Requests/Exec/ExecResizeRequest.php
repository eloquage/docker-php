<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Exec;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ExecResizeRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?int $h = null,
        protected ?int $w = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/exec/'.urlencode($this->id).'/resize';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->h !== null) {
            $query['h'] = (string) $this->h;
        }
        if ($this->w !== null) {
            $query['w'] = (string) $this->w;
        }

        return $query;
    }
}
