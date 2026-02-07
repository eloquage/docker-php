---
title: Caching
layout: default
nav_order: 1
parent: Advanced
---

# Caching

The package uses the [Saloon Cache plugin](https://saloon.dev/docs/cache-plugin) for read-only, rarely-changing requests. When `cache.enabled` is `true` (default), the Laravel cache driver (or `cache.store` if set) is used; when disabled, a no-op driver is used so no cache is read or written.

## Cached requests

| Request | TTL |
|---------|-----|
| InfoRequest | 60 s |
| VersionRequest | 300 s |
| InspectImageRequest | 120 s |
| ImageHistoryRequest | 120 s |

Container list, image list, logs, stats, and events are **not** cached.

## Configuration

In `config/docker-php.php`:

```php
'cache' => [
    'enabled' => env('DOCKER_PHP_CACHE_ENABLED', true),
    'store' => env('DOCKER_PHP_CACHE_STORE'), // null = default store
],
```

Use `DOCKER_PHP_CACHE_ENABLED=false` or `cache.enabled => false` to disable. Set `cache.store` to a specific Laravel cache store name to use that store for Docker API cache.
