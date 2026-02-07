<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Exec\ExecInspectRequest;
use Eloquage\DockerPhp\Requests\Exec\ExecResizeRequest;
use Eloquage\DockerPhp\Requests\Exec\ExecStartRequest;
use Saloon\Http\Response;

class ExecResource extends BaseResource
{
    public function inspect(string $id): Response
    {
        return $this->connector->send(new ExecInspectRequest($id));
    }

    public function start(string $id, array $body): Response
    {
        return $this->connector->send(new ExecStartRequest($id, $body));
    }

    public function resize(string $id, ?int $h = null, ?int $w = null): Response
    {
        return $this->connector->send(new ExecResizeRequest($id, $h, $w));
    }
}
