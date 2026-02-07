<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\DockerPhp;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $this->docker = new DockerPhp($connector);
});

it('exposes system resource and ping succeeds with mock', function () {
    $mockClient = new MockClient([
        MockResponse::make(['OK'], 200),
    ]);
    $this->docker->connector()->withMockClient($mockClient);

    $response = $this->docker->system()->ping();

    expect($response->successful())->toBeTrue();
});

it('exposes containers resource', function () {
    expect($this->docker->containers())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\ContainersResource::class);
});

it('exposes images resource', function () {
    expect($this->docker->images())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\ImagesResource::class);
});

it('exposes all resource accessors', function () {
    expect($this->docker->system())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\SystemResource::class);
    expect($this->docker->auth())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\AuthResource::class);
    expect($this->docker->build())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\BuildResource::class);
    expect($this->docker->exec())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\ExecResource::class);
    expect($this->docker->networks())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\NetworksResource::class);
    expect($this->docker->volumes())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\VolumesResource::class);
    expect($this->docker->swarm())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\SwarmResource::class);
    expect($this->docker->services())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\ServicesResource::class);
    expect($this->docker->nodes())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\NodesResource::class);
    expect($this->docker->tasks())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\TasksResource::class);
    expect($this->docker->secrets())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\SecretsResource::class);
    expect($this->docker->configs())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\ConfigsResource::class);
    expect($this->docker->plugins())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\PluginsResource::class);
    expect($this->docker->distribution())->toBeInstanceOf(\Eloquage\DockerPhp\Resources\DistributionResource::class);
});
