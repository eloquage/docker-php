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
    $ndjson = "{\"status\":\"Pulling from library/nginx\"}\n{\"status\":\"Pull complete\"}\n";
    $connector->withMockClient(new MockClient([
        MockResponse::make($ndjson, 200),
    ]));
    $this->app->instance('docker-php.pull-stream.connector', $connector);
});

it('validates fromImage is required', function () {
    $response = $this->postJson(route('docker-php.images.pull-stream'), []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['fromImage']);
});

it('returns SSE stream with pull progress and complete status', function () {
    $response = $this->post(route('docker-php.images.pull-stream'), [
        'fromImage' => 'nginx',
        'tag' => 'latest',
    ], ['Accept' => 'text/event-stream']);

    $response->assertSuccessful();
    $response->assertHeaderContains('Content-Type', 'text/event-stream');
    $body = $response->streamedContent();
    expect($body)->toContain('data: {"status":"Pulling from library/nginx"}');
    expect($body)->toContain('data: {"status":"Pull complete"}');
    expect($body)->toContain('data: {"status":"complete"}');
});
