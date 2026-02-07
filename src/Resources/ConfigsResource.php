<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Configs\CreateConfigRequest;
use Eloquage\DockerPhp\Requests\Configs\DeleteConfigRequest;
use Eloquage\DockerPhp\Requests\Configs\InspectConfigRequest;
use Eloquage\DockerPhp\Requests\Configs\ListConfigsRequest;
use Eloquage\DockerPhp\Requests\Configs\UpdateConfigRequest;
use Saloon\Http\Response;

class ConfigsResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListConfigsRequest($filters));
    }

    public function create(array $body): Response
    {
        return $this->connector->send(new CreateConfigRequest($body));
    }

    public function inspect(string $id): Response
    {
        return $this->connector->send(new InspectConfigRequest($id));
    }

    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteConfigRequest($id));
    }

    public function update(string $id, array $body, ?int $version = null): Response
    {
        return $this->connector->send(new UpdateConfigRequest($id, $body, $version));
    }
}
