<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Requests\Build;

use Eloquage\DockerPhp\Requests\DockerRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

class CommitRequest extends DockerRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $container,
        protected ?string $repo = null,
        protected ?string $tag = null,
        protected ?string $comment = null,
        protected ?string $author = null,
        protected ?array $changes = null,
        protected ?bool $pause = null,
        protected array $config = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/commit';
    }

    protected function defaultQuery(): array
    {
        $query = ['container' => $this->container];
        if ($this->repo !== null) {
            $query['repo'] = $this->repo;
        }
        if ($this->tag !== null) {
            $query['tag'] = $this->tag;
        }
        if ($this->comment !== null) {
            $query['comment'] = $this->comment;
        }
        if ($this->author !== null) {
            $query['author'] = $this->author;
        }
        if ($this->changes !== null) {
            $query['changes'] = $this->changes;
        }
        if ($this->pause !== null) {
            $query['pause'] = $this->pause ? '1' : '0';
        }

        return $query;
    }

    protected function defaultBody(): array
    {
        return $this->config;
    }
}
