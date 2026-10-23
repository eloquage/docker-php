# Docker PHP

![Docker PHP package banner](art/banner.png)

[![PHP 8.4][ico-php]][link-php]
[![Laravel 12][ico-laravel]][link-laravel]
[![Tests][ico-tests]][link-tests]
[![License][ico-license]][link-license]
[![Packagist][ico-packagist]][link-packagist]
[![Downloads][ico-downloads]][link-downloads]

**A Docker Engine API client for Laravel with a built-in Livewire management UI.**

Connect to your Docker daemon via Unix socket, TCP, or TLS. Use the fluent API in code or the included UI to manage containers, images, networks, volumes, and Swarm.

---

## Features

### 🔌 Docker Engine API
Resources cover containers, images, networks, volumes, Swarm, services, nodes, tasks, secrets, configs, plugins, builds, exec, and distribution. The default API version is v1.53.

### 🖥️ Built-in Livewire UI
The included dashboard and management screens use Flux UI and support dark mode. The UI is served at `/docker` by default and can be disabled or moved with configuration.

### ⚡ Real-time Streaming
Watch image pulls with live progress bars powered by Server-Sent Events. Stream container logs and stats with multiplexed stream decoding built in.

### 🧩 Type-safe DTOs
First-class Data Transfer Objects for list and inspect responses — `ImageSummary`, `ContainerSummary`, `SystemInfo`, `DiskUsage`, and more — so you never work with raw arrays.

### 🗄️ Selective Caching
Saloon cache plugin for read-only requests (info, version, image inspect/history) with configurable TTLs. Keep things fast without stale data.

### 🔗 Flexible Connections
Connect via Unix socket (default), TCP, or TLS with full certificate support. Configurable timeouts and custom headers out of the box.

### 🐝 Docker Swarm Ready
Initialize, join, and leave clusters. Manage services, nodes, tasks, secrets, and configs from the UI or programmatically through the API.

### ✅ Automated tests
Pest feature and unit tests use Saloon's MockClient, so they run without a Docker daemon. Lawman checks the connector and request architecture.

## Requirements

- PHP version supported by Laravel 12 (PHP 8.2 or newer)
- Laravel 12
- Livewire 4 and Flux 2
- A Docker Engine daemon for real API operations; a daemon is not needed to install the package or run its mocked tests

---

## Quick start

**1. Install**

```bash
composer require eloquage/docker-php
```

**2. Publish config**

```bash
php artisan vendor:publish --tag=docker-php.config
```

**3. Configure** (default: Unix socket)

```env
DOCKER_CONNECTION=unix
DOCKER_UNIX_SOCKET=/var/run/docker.sock
DOCKER_API_VERSION=v1.53
```

**4. Open the UI** at `/docker` (or your configured prefix).

---

## Programmatic usage

```php
use Eloquage\DockerPhp\Facades\DockerPhp;

// List containers
$response = DockerPhp::containers()->list(all: true);
$containers = $response->json();

// List images (returns DTOs)
$response = DockerPhp::images()->list();
$images = $response->dto(); // ImageSummary[]

// System info
$response = DockerPhp::system()->info();
$info = $response->dto(); // SystemInfo
$hostname = $info->name();
```

Errors are thrown as `Eloquage\DockerPhp\Exceptions\DockerApiException` with the daemon’s message.

---

## Configuration overview

| Env / config | Default | Description |
|--------------|---------|-------------|
| `DOCKER_CONNECTION` | `unix` | `unix`, `tcp`, or `tls` |
| `DOCKER_UNIX_SOCKET` | `/var/run/docker.sock` | Socket path (unix) |
| `DOCKER_HOST` | `localhost` | Host (tcp/tls) |
| `DOCKER_PORT` | `2375` | Port (tcp/tls) |
| `DOCKER_API_VERSION` | `v1.53` | Engine API version |
| `DOCKER_PHP_UI_PREFIX` | `docker` | URL prefix for the UI |
| `DOCKER_PHP_CACHE_ENABLED` | `true` | Cache for read-only requests |

See `config/docker-php.php` and the [documentation](https://eloquage.github.io/docker-php) for the full reference.

---

## UI preview

The UI includes a sidebar with **Dashboard**, **Containers**, **Images**, **Volumes**, **Networks**, and (when Swarm is initialized) **Swarm**, **Services**, **Nodes**, **Tasks**, **Secrets**, and **Configs**. The dashboard loads system info, version, and disk usage in parallel. Images support pull with progress bar (SSE), Docker Hub search, tag, inspect with history, and prune with feedback.

---

## Testing

```bash
vendor/bin/pest --compact
```

Tests use Saloon’s MockClient; no Docker daemon is required. See [Testing](docs/advanced/testing.md) in the docs.


Livewire components use the same `DockerPhp` service and resources to drive the UI.

---

## Documentation

Full documentation (getting started, configuration, API reference, UI guide, caching, streaming, testing) is published at:

**[https://eloquage.github.io/docker-php](https://eloquage.github.io/docker-php)**

---

## Changelog

See [changelog.md](changelog.md) for what has changed recently.

## Contributing

See [contributing.md](contributing.md) for details.

## Security

If you discover any security-related issues, please email the maintainers instead of using the issue tracker.

## License

MIT. See the [license file](license.md) for more information.

---

[ico-php]: https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php
[ico-laravel]: https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel
[ico-tests]: https://github.com/eloquage/docker-php/actions/workflows/tests.yml/badge.svg?style=flat-square
[ico-license]: https://img.shields.io/packagist/l/eloquage/docker-php?style=flat-square
[ico-packagist]: https://img.shields.io/packagist/v/eloquage/docker-php?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/eloquage/docker-php?style=flat-square

[link-php]: https://www.php.net/
[link-laravel]: https://laravel.com
[link-tests]: https://github.com/eloquage/docker-php/actions/workflows/tests.yml
[link-license]: license.md
[link-packagist]: https://packagist.org/packages/eloquage/docker-php
[link-downloads]: https://packagist.org/packages/eloquage/docker-php
