<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Images;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Enums\Method;

class TagImageRequest extends DockerRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected ?string $repo = null,
        protected ?string $tag = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/images/'.str_replace(['/', '+'], ['%2F', '%2B'], $this->name).'/tag';
    }

    protected function defaultQuery(): array
    {
        $query = [];
        if ($this->repo !== null) {
            $query['repo'] = $this->repo;
        }
        if ($this->tag !== null) {
            $query['tag'] = $this->tag;
        }

        return $query;
    }
}
