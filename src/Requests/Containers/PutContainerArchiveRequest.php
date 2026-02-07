<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Containers;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasStreamBody;

class PutContainerArchiveRequest extends DockerRequest implements HasBody
{
    use HasStreamBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $id,
        protected string $path,
        protected mixed $data = null,
        protected ?bool $noOverwriteDirNonDir = null,
        protected ?string $copyUidgid = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/containers/'.urlencode($this->id).'/archive';
    }

    protected function defaultQuery(): array
    {
        $query = ['path' => $this->path];
        if ($this->noOverwriteDirNonDir !== null) {
            $query['noOverwriteDirNonDir'] = $this->noOverwriteDirNonDir ? 'true' : 'false';
        }
        if ($this->copyUidgid !== null) {
            $query['copyUIDGID'] = $this->copyUidgid;
        }

        return $query;
    }

    protected function defaultBody(): mixed
    {
        return $this->data;
    }
}
