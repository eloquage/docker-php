<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Volumes\CreateVolumeRequest;
use Eloquage\DockerPhp\Requests\Volumes\DeleteVolumeRequest;
use Eloquage\DockerPhp\Requests\Volumes\InspectVolumeRequest;
use Eloquage\DockerPhp\Requests\Volumes\ListVolumesRequest;
use Eloquage\DockerPhp\Requests\Volumes\PruneVolumesRequest;
use Saloon\Http\Response;

class VolumesResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListVolumesRequest($filters));
    }

    public function create(array $body = []): Response
    {
        return $this->connector->send(new CreateVolumeRequest($body));
    }

    public function inspect(string $name): Response
    {
        return $this->connector->send(new InspectVolumeRequest($name));
    }

    public function delete(string $name, ?bool $force = null): Response
    {
        return $this->connector->send(new DeleteVolumeRequest($name, $force));
    }

    public function prune(?array $filters = null): Response
    {
        return $this->connector->send(new PruneVolumesRequest($filters));
    }
}
