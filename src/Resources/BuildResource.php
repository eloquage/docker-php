<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Build\BuildPruneRequest;
use Eloquage\DockerPhp\Requests\Build\BuildRequest;
use Eloquage\DockerPhp\Requests\Build\CommitRequest;
use Saloon\Http\Response;

class BuildResource extends BaseResource
{
    public function build(mixed $body = null, array $query = []): Response
    {
        return $this->connector->send(new BuildRequest($body, $query));
    }

    public function prune(?array $filters = null): Response
    {
        return $this->connector->send(new BuildPruneRequest($filters));
    }

    public function commit(string $container, ?string $repo = null, ?string $tag = null, ?string $comment = null, ?string $author = null, ?array $changes = null, ?bool $pause = null, array $config = []): Response
    {
        return $this->connector->send(new CommitRequest($container, $repo, $tag, $comment, $author, $changes, $pause, $config));
    }
}
