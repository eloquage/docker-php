<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Connectors;

use Eloquage\DockerPhp\Exceptions\DockerApiException;
use GuzzleHttp\RequestOptions;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

class DockerConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    /**
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        protected array $options = []
    ) {}

    public function resolveBaseUrl(): string
    {
        $version = trim($this->options['version'] ?? 'v1.53', '/');
        $connection = $this->options['connection'] ?? 'unix';

        if ($connection === 'unix') {
            return "http://localhost/{$version}";
        }

        $scheme = $connection === 'tls' ? 'https' : 'http';
        $host = $this->options['host'] ?? 'localhost';
        $port = (int) ($this->options['port'] ?? 2375);

        return "{$scheme}://{$host}:{$port}/{$version}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultHeaders(): array
    {
        return $this->options['headers'] ?? [
            'User-Agent' => 'Docker-PHP-Client/1.0',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultConfig(): array
    {
        $opts = [
            RequestOptions::CONNECT_TIMEOUT => $this->options['connect_timeout'] ?? 10,
            RequestOptions::TIMEOUT => $this->options['timeout'] ?? 30,
        ];

        $connection = $this->options['connection'] ?? 'unix';

        if ($connection === 'unix' && defined('CURLOPT_UNIX_SOCKET_PATH')) {
            $socket = $this->options['unix_socket'] ?? '/var/run/docker.sock';
            $opts['curl'] = [\CURLOPT_UNIX_SOCKET_PATH => $socket];
        }

        if ($connection === 'tls') {
            $cert = $this->options['tls_cert'] ?? null;
            $key = $this->options['tls_key'] ?? null;
            $ca = $this->options['tls_ca'] ?? null;
            $verify = $this->options['tls_verify'] ?? true;
            if ($cert) {
                $opts[RequestOptions::CERT] = $key ? [$cert, $key] : $cert;
            }
            if ($ca) {
                $opts[RequestOptions::VERIFY] = $ca;
            } elseif ($verify === false) {
                $opts[RequestOptions::VERIFY] = false;
            }
        }

        return $opts;
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return DockerApiException::fromResponse($response, $senderException);
    }
}
