---
title: Getting started
layout: default
nav_order: 2
---

# Getting started

## Requirements

- PHP 8.4+
- Laravel 12
- Livewire 3 (included for the UI)
- A running Docker daemon (local Unix socket, or remote TCP/TLS)

## Installation

Install the package via Composer:

```bash
composer require eloquage/docker-php
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=docker-php.config
```

This creates `config/docker-php.php`. Configure it directly or use environment variables (see [Configuration](configuration.md)).

## Connection setup

### Unix socket (default)

Use the default when Docker runs on the same host:

```env
DOCKER_CONNECTION=unix
DOCKER_UNIX_SOCKET=/var/run/docker.sock
DOCKER_API_VERSION=v1.53
```

No further config is needed; the client talks to the daemon over the socket.

### TCP

For a remote Docker host without TLS:

```env
DOCKER_CONNECTION=tcp
DOCKER_HOST=192.168.1.100
DOCKER_PORT=2375
DOCKER_API_VERSION=v1.53
```

{: .warning }
Exposing the Docker daemon over TCP without TLS is insecure. Prefer TLS or SSH tunneling in production.

### TLS

For a remote host with TLS:

```env
DOCKER_CONNECTION=tls
DOCKER_HOST=docker.example.com
DOCKER_PORT=2376
DOCKER_TLS_CERT=/path/to/client-cert.pem
DOCKER_TLS_KEY=/path/to/client-key.pem
DOCKER_TLS_CA=/path/to/ca.pem
DOCKER_TLS_VERIFY=true
```

See [Configuration](configuration.md) for all TLS and timeout options.

## Accessing the UI

If the UI is enabled (default), routes are registered under the `docker` prefix. Visit:

```
https://your-app.test/docker
```

You can change the prefix and middleware in `config/docker-php.php` under `ui.prefix` and `ui.middleware`. Use `ui.middleware => ['web', 'auth']` to require authentication.

## Programmatic usage

Use the `DockerPhp` facade to access resources and send requests:

```php
use Eloquage\DockerPhp\Facades\DockerPhp;

// List containers
$response = DockerPhp::containers()->list(all: true);
$containers = $response->json();

// List images (returns DTOs when the request supports it)
$response = DockerPhp::images()->list();
$images = $response->dto(); // array of ImageSummary

// System info
$response = DockerPhp::system()->info();
$info = $response->dto(); // SystemInfo

// Pull an image (streaming is done via the UI or the pull-stream HTTP endpoint)
DockerPhp::images()->create(fromImage: 'nginx', tag: 'alpine');
```

All API errors are thrown as `Eloquage\DockerPhp\Exceptions\DockerApiException` with the daemon’s error message. See [API reference](api/index.md) and [Exceptions](api/exceptions.md).

## Next steps

- [Configuration](configuration.md) — Full config and env reference
- [API reference](api/index.md) — Connector, resources, requests, DTOs
- [UI guide](ui/index.md) — Using the Livewire management interface
