<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class DeleteImageRequest extends DockerRequest
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<int, string>|null  $platforms  JSON-encoded OCI platform strings
     */
    public function __construct(
        protected string $name,
        protected ?bool $force = null,
        protected ?bool $noprune = null,
        protected ?array $platforms = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name);
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->force !== null) {
            $query['force'] = $this->force ? 'true' : 'false';
        }
        if ($this->noprune !== null) {
            $query['noprune'] = $this->noprune ? 'true' : 'false';
        }
        if ($this->platforms !== null) {
            foreach ($this->platforms as $platform) {
                $query['platforms'][] = $platform;
            }
        }

        return $query;
    }
}
