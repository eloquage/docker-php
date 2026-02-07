<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Networks\ConnectNetworkRequest;
use Eloquage\DockerPhp\Requests\Networks\CreateNetworkRequest;
use Eloquage\DockerPhp\Requests\Networks\DeleteNetworkRequest;
use Eloquage\DockerPhp\Requests\Networks\DisconnectNetworkRequest;
use Eloquage\DockerPhp\Requests\Networks\InspectNetworkRequest;
use Eloquage\DockerPhp\Requests\Networks\ListNetworksRequest;
use Eloquage\DockerPhp\Requests\Networks\PruneNetworksRequest;
use Saloon\Http\Response;

class NetworksResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListNetworksRequest($filters));
    }

    public function create(array $body): Response
    {
        return $this->connector->send(new CreateNetworkRequest($body));
    }

    public function inspect(string $id, ?bool $verbose = null, ?string $scope = null): Response
    {
        return $this->connector->send(new InspectNetworkRequest($id, $verbose, $scope));
    }

    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteNetworkRequest($id));
    }

    public function connect(string $id, array $body): Response
    {
        return $this->connector->send(new ConnectNetworkRequest($id, $body));
    }

    public function disconnect(string $id, array $body): Response
    {
        return $this->connector->send(new DisconnectNetworkRequest($id, $body));
    }

    public function prune(?array $filters = null): Response
    {
        return $this->connector->send(new PruneNetworksRequest($filters));
    }
}
