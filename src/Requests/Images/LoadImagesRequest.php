<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasStreamBody;

class LoadImagesRequest extends DockerRequest implements HasBody
{
    use HasStreamBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, string>|null  $platform  JSON-encoded OCI platform(s)
     */
    public function __construct(
        protected mixed $data = null,
        protected ?bool $quiet = null,
        protected ?array $platform = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/load';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->quiet !== null) {
            $query['quiet'] = $this->quiet ? 'true' : 'false';
        }
        if ($this->platform !== null) {
            foreach ($this->platform as $p) {
                $query['platform'][] = $p;
            }
        }

        return $query;
    }

    protected function defaultBody(): mixed
    {
        return $this->data;
    }
}
