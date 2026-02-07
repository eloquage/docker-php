---
title: Testing
layout: default
nav_order: 3
parent: Advanced
---

# Testing

The package uses [Pest](https://pestphp.com) for tests. No running Docker daemon is required; tests use Saloon’s **MockClient** to fake API responses.

## Run tests

From the package root:

```bash
composer install
vendor/bin/pest --compact
```

To run a subset:

```bash
vendor/bin/pest tests/Feature
vendor/bin/pest tests/Unit
vendor/bin/pest tests/Architecture
vendor/bin/pest --filter "image pull"
```

## Architecture tests (Lawman)

`tests/Architecture/SaloonArchTest.php` uses [Lawman](https://github.com/jonpurvis/lawman) to enforce Saloon conventions:

- The connector extends Saloon’s Connector and uses `AcceptsJson` and `AlwaysThrowOnErrors`.
- Request classes are Saloon requests and use the correct HTTP method (e.g. CreateImageRequest sends POST, ListImagesRequest sends GET).

Lawman is loaded from the gem when present (see `tests/Pest.php`).

## Mocking the API

Use Saloon’s `MockClient` and `MockResponse` in tests:

```php
use Eloquage\DockerPhp\Connectors\DockerConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$connector = new DockerConnector(config('docker-php', []));
$connector->withMockClient(new MockClient([
    MockResponse::make(['Id' => 'abc', 'Name' => '/foo'], 200),
]));

$this->app->instance(DockerConnector::class, $connector);
```

Then perform requests via the facade or connector; they will receive the mocked responses. See `tests/Feature/DockerPhpServiceTest.php` and `tests/Feature/DockerPhpUiTest.php` for examples.

## Pull-stream in tests

The image pull-stream endpoint uses a connector that can be swapped in tests. Bind `docker-php.pull-stream.connector` to a connector with a MockClient that returns a string body (NDJSON lines). See `tests/Feature/ImagePullStreamTest.php`.
