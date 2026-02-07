<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Swarm;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class UpdateSwarmRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
        protected ?int $version = null,
        protected ?bool $rotateWorkerToken = null,
        protected ?bool $rotateManagerToken = null,
        protected ?bool $rotateManagerUnlockKey = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/swarm/update';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->version !== null) {
            $query['version'] = (string) $this->version;
        }
        if ($this->rotateWorkerToken !== null) {
            $query['rotateWorkerToken'] = $this->rotateWorkerToken ? 'true' : 'false';
        }
        if ($this->rotateManagerToken !== null) {
            $query['rotateManagerToken'] = $this->rotateManagerToken ? 'true' : 'false';
        }
        if ($this->rotateManagerUnlockKey !== null) {
            $query['rotateManagerUnlockKey'] = $this->rotateManagerUnlockKey ? 'true' : 'false';
        }

        return $query;
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
