<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class AttachContainerRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $id,
        protected ?string $detachKeys = null,
        protected ?bool $logs = null,
        protected ?bool $stream = null,
        protected ?bool $stdin = null,
        protected ?bool $stdout = null,
        protected ?bool $stderr = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/attach';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->detachKeys !== null) {
            $query['detachKeys'] = $this->detachKeys;
        }
        if ($this->logs !== null) {
            $query['logs'] = $this->logs ? 'true' : 'false';
        }
        if ($this->stream !== null) {
            $query['stream'] = $this->stream ? 'true' : 'false';
        }
        if ($this->stdin !== null) {
            $query['stdin'] = $this->stdin ? 'true' : 'false';
        }
        if ($this->stdout !== null) {
            $query['stdout'] = $this->stdout ? 'true' : 'false';
        }
        if ($this->stderr !== null) {
            $query['stderr'] = $this->stderr ? 'true' : 'false';
        }

        return $query;
    }
}
