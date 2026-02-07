<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\Livewire\ImageList;
use Livewire\Livewire;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $connector->withMockClient(new MockClient([
        MockResponse::make([], 200),
        MockResponse::make([], 200),
        MockResponse::make([], 200),
        MockResponse::make([], 200),
    ]));
    $this->app->instance(DockerConnector::class, $connector);
    $this->app->bind('docker-php', fn () => new \Eloquage\DockerPhp\DockerPhp($connector));
});

it('search Docker Hub returns results and fills pull form', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $connector->withMockClient(new MockClient([
        MockResponse::make([], 200),
        MockResponse::make([
            ['name' => 'library/nginx', 'description' => 'Web server', 'star_count' => 10000, 'is_official' => true],
        ], 200),
        MockResponse::make([], 200),
    ]));
    $this->app->instance(DockerConnector::class, $connector);
    $this->app->bind('docker-php', fn () => new \Eloquage\DockerPhp\DockerPhp($connector));

    Livewire::test(ImageList::class)
        ->set('searchHubTerm', 'nginx')
        ->call('searchDockerHub')
        ->assertSet('searchHubResults.0.name', 'library/nginx')
        ->assertSet('searchHubResults.0.description', 'Web server');

    Livewire::test(ImageList::class)
        ->call('fillPullFromSearch', 'library/nginx', 'alpine')
        ->assertSet('pullImage', 'library/nginx')
        ->assertSet('pullTag', 'alpine');
});

it('tag image requires repo and calls API', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));

    Livewire::test(ImageList::class)
        ->set('tagImageId', 'sha256:abc')
        ->set('tagRepo', '')
        ->call('tagImage')
        ->assertSet('error', 'Repository name is required for tag.');

    Livewire::test(ImageList::class)
        ->set('tagImageId', 'sha256:abc')
        ->set('tagRepo', 'myrepo')
        ->set('tagTag', 'v1')
        ->call('tagImage')
        ->assertSet('message', 'Image tagged.')
        ->assertSet('error', null);
});

it('prune sets deleted count and reclaimed space in message', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
    $connector->withMockClient(new MockClient([
        MockResponse::make([], 200),
        MockResponse::make([
            'ImagesDeleted' => [['Deleted' => 'sha256:old1'], ['Deleted' => 'sha256:old2']],
            'SpaceReclaimed' => 150 * 1024 * 1024,
        ], 200),
        MockResponse::make([], 200),
    ]));
    $this->app->instance(DockerConnector::class, $connector);
    $this->app->bind('docker-php', fn () => new \Eloquage\DockerPhp\DockerPhp($connector));

    Livewire::test(ImageList::class)
        ->call('prune')
        ->assertSet('pruneDeleted', 2)
        ->assertSet('pruneReclaimed', 157286400)
        ->assertSet('message', 'Pruned: 2 image(s) deleted, 150.00 MB reclaimed.');
});

it('prune shows nothing to remove when no images deleted', function () {
    $connector = $this->app->make(DockerConnector::class);
    $connector->getMockClient()->addResponse(MockResponse::make([], 200));
    $connector->getMockClient()->addResponse(MockResponse::make([
        'ImagesDeleted' => null,
        'SpaceReclaimed' => 0,
    ], 200));

    Livewire::test(ImageList::class)
        ->call('prune')
        ->assertSet('message', 'Unused images pruned (nothing to remove).');
});
