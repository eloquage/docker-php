<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Secrets\CreateSecretRequest;
use Eloquage\DockerPhp\Requests\Secrets\DeleteSecretRequest;
use Eloquage\DockerPhp\Requests\Secrets\InspectSecretRequest;
use Eloquage\DockerPhp\Requests\Secrets\ListSecretsRequest;
use Eloquage\DockerPhp\Requests\Secrets\UpdateSecretRequest;
use Saloon\Http\Response;

class SecretsResource extends BaseResource
{
    public function list(?array $filters = null): Response
    {
        return $this->connector->send(new ListSecretsRequest($filters));
    }

    public function create(array $body): Response
    {
        return $this->connector->send(new CreateSecretRequest($body));
    }

    public function inspect(string $id): Response
    {
        return $this->connector->send(new InspectSecretRequest($id));
    }

    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteSecretRequest($id));
    }

    public function update(string $id, array $body, ?int $version = null): Response
    {
        return $this->connector->send(new UpdateSecretRequest($id, $body, $version));
    }
}
