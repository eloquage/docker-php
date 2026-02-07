<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\System\GetEventsRequest;
use Eloquage\DockerPhp\Requests\System\InfoRequest;
use Eloquage\DockerPhp\Requests\System\PingRequest;
use Eloquage\DockerPhp\Requests\System\SystemDfRequest;
use Eloquage\DockerPhp\Requests\System\SystemPruneRequest;
use Eloquage\DockerPhp\Requests\System\VersionRequest;
use Saloon\Http\Response;

class SystemResource extends BaseResource
{
    public function ping(): Response
    {
        return $this->connector->send(new PingRequest);
    }

    public function info(): Response
    {
        return $this->connector->send(new InfoRequest);
    }

    public function version(): Response
    {
        return $this->connector->send(new VersionRequest);
    }

    public function events(?string $since = null, ?string $until = null, ?array $filters = null): Response
    {
        return $this->connector->send(new GetEventsRequest($since, $until, $filters));
    }

    public function df(): Response
    {
        return $this->connector->send(new SystemDfRequest);
    }

    public function prune(?array $filters = null): Response
    {
        return $this->connector->send(new SystemPruneRequest($filters));
    }
}
