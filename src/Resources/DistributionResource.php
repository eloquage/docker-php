<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Distribution\InspectDistributionRequest;
use Saloon\Http\Response;

class DistributionResource extends BaseResource
{
    public function inspect(string $name): Response
    {
        return $this->connector->send(new InspectDistributionRequest($name));
    }
}
