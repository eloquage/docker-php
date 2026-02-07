---
title: Connector
layout: default
nav_order: 1
parent: API reference
---

# DockerConnector

`Eloquage\DockerPhp\Connectors\DockerConnector` extends Saloon’s `Connector` and is registered as a singleton. It is configured via `config/docker-php.php`.

## Connection modes

| Mode | Config `connection` | Description |
|------|---------------------|-------------|
| Unix | `unix` | Uses `CURLOPT_UNIX_SOCKET_PATH`; default socket `/var/run/docker.sock`. |
| TCP | `tcp` | HTTP to `host:port` (default `localhost:2375`). |
| TLS | `tls` | HTTPS with client certs; `tls_cert`, `tls_key`, `tls_ca`, `tls_verify`. |

## Traits

- **AcceptsJson** — Sends and expects JSON.
- **AlwaysThrowOnErrors** — Non-2xx responses throw; see [Exceptions](exceptions.md).

## Custom exception

`getRequestException()` is overridden to return `DockerApiException` with the Docker daemon’s `message` from the JSON body.

## Timeouts

- `connect_timeout` — Seconds to establish connection (default `10`).
- `timeout` — Seconds for request (default `30`). Use `0` for streaming (e.g. image pull); the pull-stream controller creates a connector with `timeout => 0`.

## Headers

Default `User-Agent` is `Docker-PHP-Client/1.0`. Override with `DOCKER_USER_AGENT` or the `headers` config key.
