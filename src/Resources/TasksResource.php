<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Tasks\InspectTaskRequest;
use Eloquage\DockerPhp\Requests\Tasks\ListTasksRequest;
use Saloon\Http\Response;

class TasksResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListTasksRequest($filters));
    }

    public function inspect(string $id): Response
    {
        return $this->connector->send(new InspectTaskRequest($id));
    }
}
