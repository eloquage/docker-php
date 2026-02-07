---
title: Docker Engine API for Laravel
layout: default
nav_order: 1
---

<div class="hero">
  <p class="hero-title">Docker Engine API for Laravel</p>
  <p class="hero-subtitle">A comprehensive API client with a built-in Livewire management UI. Connect via Unix socket, TCP, or TLS.</p>
</div>

## Features

<div class="feature-grid">

<div class="feature-card">
<strong>100+ API endpoints</strong>
Full Docker Engine API v1.53 coverage: containers, images, networks, volumes, swarm, services, nodes, tasks, secrets, configs, plugins, build, exec, and more.
</div>

<div class="feature-card">
<strong>Livewire UI</strong>
15 management components with Flux UI styling: dashboard, containers, images, networks, volumes, swarm, services, nodes, tasks, secrets, configs, plugins. Dark mode supported.
</div>

<div class="feature-card">
<strong>Real-time streaming</strong>
Image pull progress via Server-Sent Events; container logs and stats; multiplexed stream decoding.
</div>

<div class="feature-card">
<strong>Type-safe DTOs</strong>
Data Transfer Objects for list and inspect responses: ImageSummary, ContainerSummary, SystemInfo, DiskUsage, and more.
</div>

<div class="feature-card">
<strong>Selective caching</strong>
Saloon cache plugin for read-only requests (info, version, image inspect/history) with configurable TTLs and Laravel cache driver.
</div>

<div class="feature-card">
<strong>Connection flexibility</strong>
Unix socket (default), TCP, or TLS with certificate support. Configurable timeouts and headers.
</div>

<div class="feature-card">
<strong>Docker Swarm</strong>
Initialize, join, leave; manage services, nodes, tasks, secrets, and configs from the UI or API.
</div>

<div class="feature-card">
<strong>Architecture tested</strong>
Lawman expectations for Saloon connector and request classes; Pest for feature and unit tests.
</div>

</div>

## Quick install

```bash
composer require eloquage/docker-php
php artisan vendor:publish --tag=docker-php.config
```

Set your connection in `.env` (Unix socket is the default):

```env
DOCKER_CONNECTION=unix
DOCKER_UNIX_SOCKET=/var/run/docker.sock
```

Visit `/docker` (or your configured prefix) to use the management UI.

## Next steps

- [Getting started](getting-started.md) — Installation, configuration, and first usage
- [Configuration](configuration.md) — All config keys and environment variables
- [API reference](api/index.md) — Connector, resources, requests, and DTOs
- [UI guide](ui/index.md) — Dashboard, containers, images, and swarm
