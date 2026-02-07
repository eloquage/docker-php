<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Auth\AuthRequest;
use Saloon\Http\Response;

class AuthResource extends BaseResource
{
    public function auth(array $body): Response
    {
        return $this->connector->send(new AuthRequest($body));
    }
}
