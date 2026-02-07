---
title: Exceptions
layout: default
nav_order: 5
parent: API reference
---

# Exceptions

## DockerApiException

`Eloquage\DockerPhp\Exceptions\DockerApiException` extends Saloon’s `RequestException`. It is thrown for any non-2xx response when the connector’s **AlwaysThrowOnErrors** trait is used (default).

- **Message:** Taken from the response JSON key `message` when present; otherwise the raw body.
- **Code:** HTTP status code.
- **Previous:** Optional sender exception.

Created via `DockerApiException::fromResponse(Response $response, ?Throwable $senderException)`; the connector’s `getRequestException()` uses this.

## Catching errors

```php
use Eloquage\DockerPhp\Exceptions\DockerApiException;
use Eloquage\DockerPhp\Facades\DockerPhp;

try {
    DockerPhp::containers()->start('nonexistent');
} catch (DockerApiException $e) {
    // $e->getMessage() is the daemon's error message
    // $e->getCode() is the HTTP status (e.g. 404)
}
```

Livewire components in the package catch this (or `Throwable`) and set an `$error` property for display in the UI.
