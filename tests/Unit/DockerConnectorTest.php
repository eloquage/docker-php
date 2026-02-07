<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\Requests\System\PingRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('resolves unix base url with default version', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
    ]);
    $baseUrl = invokeProtected($connector, 'resolveBaseUrl');
    expect($baseUrl)->toBe('http://localhost/v1.53');
});

it('resolves unix base url with custom version', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'unix_socket' => '/var/run/docker.sock',
        'version' => 'v1.42',
    ]);
    $baseUrl = invokeProtected($connector, 'resolveBaseUrl');
    expect($baseUrl)->toBe('http://localhost/v1.42');
});

it('resolves tcp base url with default host and port', function () {
    $connector = new DockerConnector([
        'connection' => 'tcp',
    ]);
    $baseUrl = invokeProtected($connector, 'resolveBaseUrl');
    expect($baseUrl)->toBe('http://localhost:2375/v1.53');
});

it('resolves tcp base url with custom host and port', function () {
    $connector = new DockerConnector([
        'connection' => 'tcp',
        'host' => 'docker.example.com',
        'port' => 4243,
        'version' => 'v1.50',
    ]);
    $baseUrl = invokeProtected($connector, 'resolveBaseUrl');
    expect($baseUrl)->toBe('http://docker.example.com:4243/v1.50');
});

it('resolves tls base url', function () {
    $connector = new DockerConnector([
        'connection' => 'tls',
        'host' => 'secure.docker.local',
        'port' => 2376,
    ]);
    $baseUrl = invokeProtected($connector, 'resolveBaseUrl');
    expect($baseUrl)->toBe('https://secure.docker.local:2376/v1.53');
});

it('sends request to resolved base url', function () {
    $connector = new DockerConnector([
        'connection' => 'unix',
        'version' => 'v1.53',
    ]);
    $mockClient = new MockClient([
        MockResponse::make(['OK'], 200),
    ]);
    $connector->withMockClient($mockClient);
    $connector->send(new PingRequest);
    $mockClient->assertSent(PingRequest::class);
});

function invokeProtected(object $object, string $method, array $args = []): mixed
{
    $ref = new ReflectionMethod($object, $method);
    $ref->setAccessible(true);

    return $ref->invoke($object, ...$args);
}
