<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\DataTransferObjects\ImageSummary;
use Eloquage\DockerPhp\DataTransferObjects\SystemInfo;
use Eloquage\DockerPhp\Requests\Containers\InspectContainerRequest;
use Eloquage\DockerPhp\Requests\Containers\ListContainersRequest;
use Eloquage\DockerPhp\Requests\Images\ListImagesRequest;
use Eloquage\DockerPhp\Requests\Networks\ListNetworksRequest;
use Eloquage\DockerPhp\Requests\System\InfoRequest;
use Eloquage\DockerPhp\Requests\System\PingRequest;
use Eloquage\DockerPhp\Requests\System\VersionRequest;
use Eloquage\DockerPhp\Requests\Volumes\ListVolumesRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.53',
    ]);
});

it('sends ping request to correct endpoint', function () {
    $mockClient = new MockClient([
        MockResponse::make(['OK'], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $response = $this->connector->send(new PingRequest);

    expect($response->successful())->toBeTrue();
    $mockClient->assertSent(PingRequest::class);
});

it('sends list containers request', function () {
    $mockClient = new MockClient([
        MockResponse::make([], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $this->connector->send(new ListContainersRequest(all: true));

    $mockClient->assertSent(ListContainersRequest::class);
});

it('sends list images request', function () {
    $mockClient = new MockClient([
        MockResponse::make([], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $this->connector->send(new ListImagesRequest);

    $mockClient->assertSent(ListImagesRequest::class);
});

it('returns image list as DTOs', function () {
    $mockClient = new MockClient([
        MockResponse::make([
            ['Id' => 'sha256:abc', 'RepoTags' => ['nginx:latest'], 'Size' => 1000000],
        ], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $response = $this->connector->send(new ListImagesRequest);
    $dtos = $response->dto();

    expect($dtos)->toBeArray()->toHaveCount(1);
    expect($dtos[0])->toBeInstanceOf(ImageSummary::class);
    expect($dtos[0]->id)->toBe('sha256:abc');
    expect($dtos[0]->repoTags)->toBe(['nginx:latest']);
});

it('returns system info as DTO', function () {
    $mockClient = new MockClient([
        MockResponse::make(['Name' => 'docker-host', 'NCPU' => 4], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $response = $this->connector->send(new InfoRequest);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(SystemInfo::class);
    expect($dto->name())->toBe('docker-host');
    expect($dto->ncpu())->toBe(4);
});

it('sends list networks request', function () {
    $mockClient = new MockClient([
        MockResponse::make([], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $this->connector->send(new ListNetworksRequest);

    $mockClient->assertSent(ListNetworksRequest::class);
});

it('sends list volumes request', function () {
    $mockClient = new MockClient([
        MockResponse::make(['Volumes' => [], 'Warnings' => []], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $this->connector->send(new ListVolumesRequest);

    $mockClient->assertSent(ListVolumesRequest::class);
});

it('sends system info request', function () {
    $mockClient = new MockClient([
        MockResponse::make(['Name' => 'docker-host', 'NCPU' => 4], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $response = $this->connector->send(new InfoRequest);

    expect($response->successful())->toBeTrue();
    $mockClient->assertSent(InfoRequest::class);
});

it('sends version request', function () {
    $mockClient = new MockClient([
        MockResponse::make(['Version' => '24.0.0', 'ApiVersion' => '1.53'], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $response = $this->connector->send(new VersionRequest);

    expect($response->successful())->toBeTrue();
    $mockClient->assertSent(VersionRequest::class);
});

it('sends inspect container request with optional size', function () {
    $mockClient = new MockClient([
        MockResponse::make(['Id' => 'abc123', 'Name' => '/foo'], 200),
        MockResponse::make(['Id' => 'abc123', 'Name' => '/foo', 'SizeRw' => 0], 200),
    ]);
    $this->connector->withMockClient($mockClient);

    $this->connector->send(new InspectContainerRequest('abc123'));
    $mockClient->assertSent(InspectContainerRequest::class);

    $this->connector->send(new InspectContainerRequest('abc123', size: true));
    $mockClient->assertSent(InspectContainerRequest::class);
});
