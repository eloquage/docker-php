<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ContainerLogsRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?bool $follow = null,
        protected ?bool $stdout = null,
        protected ?bool $stderr = null,
        protected ?int $since = null,
        protected ?int $until = null,
        protected ?bool $timestamps = null,
        protected ?string $tail = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/logs';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->follow !== null) {
            $query['follow'] = $this->follow ? 'true' : 'false';
        }
        if ($this->stdout !== null) {
            $query['stdout'] = $this->stdout ? 'true' : 'false';
        }
        if ($this->stderr !== null) {
            $query['stderr'] = $this->stderr ? 'true' : 'false';
        }
        if ($this->since !== null) {
            $query['since'] = (string) $this->since;
        }
        if ($this->until !== null) {
            $query['until'] = (string) $this->until;
        }
        if ($this->timestamps !== null) {
            $query['timestamps'] = $this->timestamps ? 'true' : 'false';
        }
        if ($this->tail !== null) {
            $query['tail'] = $this->tail;
        }

        return $query;
    }
}
