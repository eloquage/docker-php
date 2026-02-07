<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Nodes\DeleteNodeRequest;
use Eloquage\DockerPhp\Requests\Nodes\InspectNodeRequest;
use Eloquage\DockerPhp\Requests\Nodes\ListNodesRequest;
use Eloquage\DockerPhp\Requests\Nodes\UpdateNodeRequest;
use Saloon\Http\Response;

class NodesResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListNodesRequest($filters));
    }

    public function inspect(string $id): Response
    {
        return $this->connector->send(new InspectNodeRequest($id));
    }

    public function delete(string $id, ?bool $force = null): Response
    {
        return $this->connector->send(new DeleteNodeRequest($id, $force));
    }

    public function update(string $id, array $body, ?int $version = null): Response
    {
        return $this->connector->send(new UpdateNodeRequest($id, $body, $version));
    }
}
