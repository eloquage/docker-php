<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Services;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class ServiceLogsRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $id,
        protected ?bool $details = null,
        protected ?bool $follow = null,
        protected ?bool $stdout = null,
        protected ?bool $stderr = null,
        protected ?int $since = null,
        protected ?bool $timestamps = null,
        protected ?string $tail = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/services/'.urlencode($this->id).'/logs';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->details !== null) {
            $query['details'] = $this->details ? 'true' : 'false';
        }
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
        if ($this->timestamps !== null) {
            $query['timestamps'] = $this->timestamps ? 'true' : 'false';
        }
        if ($this->tail !== null) {
            $query['tail'] = $this->tail;
        }

        return $query;
    }
}
