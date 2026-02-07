<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Plugins\CreatePluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\DeletePluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\DisablePluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\EnablePluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\GetPluginPrivilegesRequest;
use Eloquage\DockerPhp\Requests\Plugins\InspectPluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\ListPluginsRequest;
use Eloquage\DockerPhp\Requests\Plugins\PullPluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\PushPluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\SetPluginRequest;
use Eloquage\DockerPhp\Requests\Plugins\UpgradePluginRequest;
use Saloon\Http\Response;

class PluginsResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListPluginsRequest($filters));
    }

    public function privileges(?string $remote = null): Response
    {
        return $this->connector->send(new GetPluginPrivilegesRequest($remote));
    }

    public function pull(?string $remote = null, ?string $name = null, array $body = []): Response
    {
        return $this->connector->send(new PullPluginRequest($remote, $name, $body));
    }

    public function create(string $name, mixed $body = null): Response
    {
        return $this->connector->send(new CreatePluginRequest($name, $body));
    }

    public function inspect(string $name): Response
    {
        return $this->connector->send(new InspectPluginRequest($name));
    }

    public function delete(string $name, ?bool $force = null): Response
    {
        return $this->connector->send(new DeletePluginRequest($name, $force));
    }

    public function enable(string $name, ?int $timeout = null): Response
    {
        return $this->connector->send(new EnablePluginRequest($name, $timeout));
    }

    public function disable(string $name): Response
    {
        return $this->connector->send(new DisablePluginRequest($name));
    }

    public function upgrade(string $name, mixed $body = null, ?string $remote = null): Response
    {
        return $this->connector->send(new UpgradePluginRequest($name, $body, $remote));
    }

    public function set(string $name, array $body): Response
    {
        return $this->connector->send(new SetPluginRequest($name, $body));
    }

    public function push(string $name): Response
    {
        return $this->connector->send(new PushPluginRequest($name));
    }
}
