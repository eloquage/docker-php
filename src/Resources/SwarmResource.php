<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Swarm\GetSwarmRequest;
use Eloquage\DockerPhp\Requests\Swarm\GetSwarmUnlockKeyRequest;
use Eloquage\DockerPhp\Requests\Swarm\InitSwarmRequest;
use Eloquage\DockerPhp\Requests\Swarm\JoinSwarmRequest;
use Eloquage\DockerPhp\Requests\Swarm\LeaveSwarmRequest;
use Eloquage\DockerPhp\Requests\Swarm\UnlockSwarmRequest;
use Eloquage\DockerPhp\Requests\Swarm\UpdateSwarmRequest;
use Saloon\Http\Response;

class SwarmResource extends BaseResource
{
    public function get(): Response
    {
        return $this->connector->send(new GetSwarmRequest);
    }

    public function init(array $body = []): Response
    {
        return $this->connector->send(new InitSwarmRequest($body));
    }

    public function join(array $body): Response
    {
        return $this->connector->send(new JoinSwarmRequest($body));
    }

    public function leave(?bool $force = null): Response
    {
        return $this->connector->send(new LeaveSwarmRequest($force));
    }

    public function update(array $body, ?int $version = null, ?bool $rotateWorkerToken = null, ?bool $rotateManagerToken = null, ?bool $rotateManagerUnlockKey = null): Response
    {
        return $this->connector->send(new UpdateSwarmRequest($body, $version, $rotateWorkerToken, $rotateManagerToken, $rotateManagerUnlockKey));
    }

    public function unlockKey(): Response
    {
        return $this->connector->send(new GetSwarmUnlockKeyRequest);
    }

    public function unlock(array $body): Response
    {
        return $this->connector->send(new UnlockSwarmRequest($body));
    }
}
