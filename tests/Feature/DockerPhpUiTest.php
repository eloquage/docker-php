<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);

    $mockClient = new MockClient([
        MockResponse::make([
            'Containers' => 2,
            'ContainersRunning' => 1,
            'Images' => 5,
            'KernelVersion' => '5.15.0',
            'NCPU' => 4,
            'MemTotal' => 16 * 1024 * 1024 * 1024,
            'Name' => 'docker-host',
            'OperatingSystem' => 'Linux',
        ], 200),
        MockResponse::make(['Version' => '24.0.0', 'ApiVersion' => '1.53'], 200),
        MockResponse::make(['LayersSize' => 1024 * 1024 * 1024, 'Images' => []], 200),
    ]);

    $connector->withMockClient($mockClient);

    $this->app->instance(DockerConnector::class, $connector);
    $this->app->bind('docker-php', fn () => new \Eloquage\DockerPhp\DockerPhp($connector));
});

it('dashboard page returns success and shows dashboard heading', function () {
    $response = $this->get(route('docker-php.dashboard'));

    $response->assertSuccessful();
    $response->assertSee('Dashboard');
});

it('containers list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.containers.index'));

    $response->assertSuccessful();
    $response->assertSee('Containers');
});

it('container inspect page returns success', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $connector->withMockClient(new MockClient([
        MockResponse::make([
            'Id' => 'abc123',
            'Name' => '/my-container',
            'State' => ['Status' => 'running'],
        ], 200),
    ]));
    $this->app->instance(DockerConnector::class, $connector);

    $response = $this->get(route('docker-php.containers.show', ['id' => 'abc123']));

    $response->assertSuccessful();
    $response->assertSee('Containers');
});

it('container logs page returns success', function () {
    $stdoutPayload = "log line\n";
    $header = "\x01\x00\x00\x00".pack('N', strlen($stdoutPayload));
    $body = $header.$stdoutPayload;
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $connector->withMockClient(new MockClient([
        MockResponse::make($body, 200, ['Content-Type' => 'application/vnd.docker.raw-stream']),
    ]));
    $this->app->instance(DockerConnector::class, $connector);

    $response = $this->get(route('docker-php.containers.logs', ['id' => 'abc123']));

    $response->assertSuccessful();
    $response->assertSee('Container');
});

it('images list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.images.index'));

    $response->assertSuccessful();
    $response->assertSee('Images');
});

it('image inspect page returns success', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $connector->withMockClient(new MockClient([
        MockResponse::make(['ID' => null], 200),
        MockResponse::make([
            'Id' => 'sha256:abc123',
            'Architecture' => 'amd64',
            'Os' => 'linux',
            'Size' => 1000000,
            'RepoTags' => ['nginx:latest'],
        ], 200),
        MockResponse::make([
            ['Id' => 'layer1', 'Created' => 0, 'CreatedBy' => 'COPY . .', 'Size' => 500],
        ], 200),
    ]));
    $this->app->instance(DockerConnector::class, $connector);
    $this->app->bind('docker-php', fn () => new \Eloquage\DockerPhp\DockerPhp($connector));

    $response = $this->get(route('docker-php.images.show', ['name' => 'nginx:latest']));

    $response->assertSuccessful();
    $response->assertSee('Images');
});

it('networks list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.networks.index'));

    $response->assertSuccessful();
    $response->assertSee('Networks');
});

it('volumes list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make(['Volumes' => [], 'Warnings' => []], 200));

    $response = $this->get(route('docker-php.volumes.index'));

    $response->assertSuccessful();
    $response->assertSee('Volumes');
});

it('swarm page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make(['ID' => 'swarm-id'], 200));
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.swarm.index'));

    $response->assertSuccessful();
    $response->assertSee('Swarm');
});

it('services list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.services.index'));

    $response->assertSuccessful();
    $response->assertSee('Services');
});

it('nodes list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.nodes.index'));

    $response->assertSuccessful();
    $response->assertSee('Nodes');
});

it('tasks list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.tasks.index'));

    $response->assertSuccessful();
    $response->assertSee('Tasks');
});

it('secrets list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.secrets.index'));

    $response->assertSuccessful();
    $response->assertSee('Secrets');
});

it('configs list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.configs.index'));

    $response->assertSuccessful();
    $response->assertSee('Configs');
});

it('plugins list page returns success', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    $response = $this->get(route('docker-php.plugins.index'));

    $response->assertSuccessful();
    $response->assertSee('Plugins');
});
