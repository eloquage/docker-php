<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class GetImageRequest extends DockerRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int, string>|null  $platform  JSON-encoded OCI platform
     */
    public function __construct(
        protected string $name,
        protected ?array $platform = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name).'/get';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->platform !== null) {
            foreach ($this->platform as $p) {
                $query['platform'][] = $p;
            }
        }

        return $query;
    }
}
