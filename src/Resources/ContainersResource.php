<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Containers\AttachContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\AttachContainerWebsocketRequest;
use Eloquage\DockerPhp\Requests\Containers\ContainerChangesRequest;
use Eloquage\DockerPhp\Requests\Containers\ContainerExecRequest;
use Eloquage\DockerPhp\Requests\Containers\ContainerLogsRequest;
use Eloquage\DockerPhp\Requests\Containers\ContainerStatsRequest;
use Eloquage\DockerPhp\Requests\Containers\ContainerTopRequest;
use Eloquage\DockerPhp\Requests\Containers\CreateContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\DeleteContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\ExportContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\GetContainerArchiveRequest;
use Eloquage\DockerPhp\Requests\Containers\InspectContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\KillContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\ListContainersRequest;
use Eloquage\DockerPhp\Requests\Containers\PauseContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\PruneContainersRequest;
use Eloquage\DockerPhp\Requests\Containers\PutContainerArchiveRequest;
use Eloquage\DockerPhp\Requests\Containers\RenameContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\ResizeContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\RestartContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\StartContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\StopContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\UnpauseContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\UpdateContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\WaitContainerRequest;
use Saloon\Http\Response;

class ContainersResource extends BaseResource
{
    public function list(?bool $all = null, ?int $limit = null, ?bool $size = null, ?array $filters = null): Response
    {
        return $this->connector->send(new ListContainersRequest($all, $limit, $size, $filters));
    }

    public function create(array $body, ?string $name = null): Response
    {
        return $this->connector->send(new CreateContainerRequest($body, $name));
    }

    public function inspect(string $id, ?bool $size = null): Response
    {
        return $this->connector->send(new InspectContainerRequest($id, $size));
    }

    public function top(string $id, ?string $psArgs = null): Response
    {
        return $this->connector->send(new ContainerTopRequest($id, $psArgs));
    }

    public function logs(string $id, ?bool $follow = null, ?bool $stdout = null, ?bool $stderr = null, ?int $since = null, ?int $until = null, ?bool $timestamps = null, ?string $tail = null): Response
    {
        return $this->connector->send(new ContainerLogsRequest($id, $follow, $stdout, $stderr, $since, $until, $timestamps, $tail));
    }

    public function changes(string $id): Response
    {
        return $this->connector->send(new ContainerChangesRequest($id));
    }

    public function export(string $id): Response
    {
        return $this->connector->send(new ExportContainerRequest($id));
    }

    public function stats(string $id, ?bool $stream = null): Response
    {
        return $this->connector->send(new ContainerStatsRequest($id, $stream));
    }

    public function resize(string $id, ?int $h = null, ?int $w = null): Response
    {
        return $this->connector->send(new ResizeContainerRequest($id, $h, $w));
    }

    public function start(string $id, ?string $detachKeys = null): Response
    {
        return $this->connector->send(new StartContainerRequest($id, $detachKeys));
    }

    public function stop(string $id, ?int $t = null): Response
    {
        return $this->connector->send(new StopContainerRequest($id, $t));
    }

    public function restart(string $id, ?int $t = null): Response
    {
        return $this->connector->send(new RestartContainerRequest($id, $t));
    }

    public function kill(string $id, ?string $signal = null): Response
    {
        return $this->connector->send(new KillContainerRequest($id, $signal));
    }

    public function update(string $id, array $body): Response
    {
        return $this->connector->send(new UpdateContainerRequest($id, $body));
    }

    public function rename(string $id, string $name): Response
    {
        return $this->connector->send(new RenameContainerRequest($id, $name));
    }

    public function pause(string $id): Response
    {
        return $this->connector->send(new PauseContainerRequest($id));
    }

    public function unpause(string $id): Response
    {
        return $this->connector->send(new UnpauseContainerRequest($id));
    }

    public function attach(string $id, ?string $detachKeys = null, ?bool $logs = null, ?bool $stream = null, ?bool $stdin = null, ?bool $stdout = null, ?bool $stderr = null): Response
    {
        return $this->connector->send(new AttachContainerRequest($id, $detachKeys, $logs, $stream, $stdin, $stdout, $stderr));
    }

    public function attachWebsocket(string $id, ?string $detachKeys = null, ?bool $logs = null, ?bool $stream = null, ?bool $stdin = null, ?bool $stdout = null, ?bool $stderr = null): Response
    {
        return $this->connector->send(new AttachContainerWebsocketRequest($id, $detachKeys, $logs, $stream, $stdin, $stdout, $stderr));
    }

    public function wait(string $id, ?string $condition = null): Response
    {
        return $this->connector->send(new WaitContainerRequest($id, $condition));
    }

    public function delete(string $id, ?bool $v = null, ?bool $force = null, ?bool $link = null): Response
    {
        return $this->connector->send(new DeleteContainerRequest($id, $v, $force, $link));
    }

    public function getArchive(string $id, string $path): Response
    {
        return $this->connector->send(new GetContainerArchiveRequest($id, $path));
    }

    public function putArchive(string $id, string $path, mixed $body = null, ?bool $noOverwriteDirNonDir = null, ?string $copyUidgid = null): Response
    {
        return $this->connector->send(new PutContainerArchiveRequest($id, $path, $body, $noOverwriteDirNonDir, $copyUidgid));
    }

    public function exec(string $id, array $body): Response
    {
        return $this->connector->send(new ContainerExecRequest($id, $body));
    }

    public function prune(?array $filters = null): Response
    {
        return $this->connector->send(new PruneContainersRequest($filters));
    }
}
