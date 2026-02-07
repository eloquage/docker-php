<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Services\CreateServiceRequest;
use Eloquage\DockerPhp\Requests\Services\DeleteServiceRequest;
use Eloquage\DockerPhp\Requests\Services\InspectServiceRequest;
use Eloquage\DockerPhp\Requests\Services\ListServicesRequest;
use Eloquage\DockerPhp\Requests\Services\ServiceLogsRequest;
use Eloquage\DockerPhp\Requests\Services\UpdateServiceRequest;
use Saloon\Http\Response;

class ServicesResource extends BaseResource
{
    public function list(?array $filters = null, ?bool $status = null): Response
    {
        return $this->connector->send(new ListServicesRequest($filters, $status));
    }

    public function create(array $body, ?string $XRegistryAuth = null): Response
    {
        return $this->connector->send(new CreateServiceRequest($body, $XRegistryAuth));
    }

    public function inspect(string $id, ?bool $insertDefaults = null): Response
    {
        return $this->connector->send(new InspectServiceRequest($id, $insertDefaults));
    }

    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteServiceRequest($id));
    }

    public function update(string $id, array $body, ?int $version = null, ?string $registryAuthFrom = null, ?string $rollback = null, ?string $XRegistryAuth = null): Response
    {
        return $this->connector->send(new UpdateServiceRequest($id, $body, $version, $registryAuthFrom, $rollback, $XRegistryAuth));
    }

    public function logs(string $id, ?bool $details = null, ?bool $follow = null, ?bool $stdout = null, ?bool $stderr = null, ?int $since = null, ?bool $timestamps = null, ?string $tail = null): Response
    {
        return $this->connector->send(new ServiceLogsRequest($id, $details, $follow, $stdout, $stderr, $since, $timestamps, $tail));
    }
}
