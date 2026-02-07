<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Exceptions;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use Throwable;

class DockerApiException extends RequestException
{
    public static function fromResponse(Response $response, ?Throwable $senderException = null): self
    {
        $body = $response->json();
        $message = is_array($body) && isset($body['message'])
            ? (string) $body['message']
            : $response->body();

        return new self($response, $message, 0, $senderException);
    }
}
