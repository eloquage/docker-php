<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp;

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\Resources\AuthResource;
use Eloquage\DockerPhp\Resources\BuildResource;
use Eloquage\DockerPhp\Resources\ConfigsResource;
use Eloquage\DockerPhp\Resources\ContainersResource;
use Eloquage\DockerPhp\Resources\DistributionResource;
use Eloquage\DockerPhp\Resources\ExecResource;
use Eloquage\DockerPhp\Resources\ImagesResource;
use Eloquage\DockerPhp\Resources\NetworksResource;
use Eloquage\DockerPhp\Resources\NodesResource;
use Eloquage\DockerPhp\Resources\PluginsResource;
use Eloquage\DockerPhp\Resources\SecretsResource;
use Eloquage\DockerPhp\Resources\ServicesResource;
use Eloquage\DockerPhp\Resources\SwarmResource;
use Eloquage\DockerPhp\Resources\SystemResource;
use Eloquage\DockerPhp\Resources\TasksResource;
use Eloquage\DockerPhp\Resources\VolumesResource;
use Eloquage\DockerPhp\Support\StreamDecoder;
use Saloon\Http\Response;

class DockerPhp
{
    public function __construct(
        protected DockerConnector $connector
    ) {}

    public function connector(): DockerConnector
    {
        return $this->connector;
    }

    public function system(): SystemResource
    {
        return new SystemResource($this->connector);
    }

    public function auth(): AuthResource
    {
        return new AuthResource($this->connector);
    }

    public function build(): BuildResource
    {
        return new BuildResource($this->connector);
    }

    public function containers(): ContainersResource
    {
        return new ContainersResource($this->connector);
    }

    public function exec(): ExecResource
    {
        return new ExecResource($this->connector);
    }

    public function images(): ImagesResource
    {
        return new ImagesResource($this->connector);
    }

    public function networks(): NetworksResource
    {
        return new NetworksResource($this->connector);
    }

    public function volumes(): VolumesResource
    {
        return new VolumesResource($this->connector);
    }

    public function swarm(): SwarmResource
    {
        return new SwarmResource($this->connector);
    }

    public function services(): ServicesResource
    {
        return new ServicesResource($this->connector);
    }

    public function nodes(): NodesResource
    {
        return new NodesResource($this->connector);
    }

    public function tasks(): TasksResource
    {
        return new TasksResource($this->connector);
    }

    public function secrets(): SecretsResource
    {
        return new SecretsResource($this->connector);
    }

    public function configs(): ConfigsResource
    {
        return new ConfigsResource($this->connector);
    }

    public function plugins(): PluginsResource
    {
        return new PluginsResource($this->connector);
    }

    public function distribution(): DistributionResource
    {
        return new DistributionResource($this->connector);
    }

    /**
     * Container logs as raw response (use ->stream() for PSR-7 stream).
     */
    public function containerLogsStream(string $id, ?bool $follow = null, ?bool $stdout = null, ?bool $stderr = null, ?int $since = null, ?int $until = null, ?bool $timestamps = null, ?string $tail = null): Response
    {
        return $this->containers()->logs($id, $follow, $stdout, $stderr, $since, $until, $timestamps, $tail);
    }

    /**
     * Container logs decoded into stdout and stderr strings (non-streaming; reads full response).
     *
     * @return array{stdout: string, stderr: string}
     */
    public function containerLogsDecoded(string $id, ?bool $stdout = true, ?bool $stderr = true, ?int $since = null, ?int $until = null, ?bool $timestamps = null, ?string $tail = null): array
    {
        $response = $this->containers()->logs($id, false, $stdout, $stderr, $since, $until, $timestamps, $tail);

        return StreamDecoder::decodeToStdoutStderr($response->stream());
    }

    /**
     * Attach to container; use ->stream() on the response for raw PSR-7 stream.
     */
    public function containerAttachStream(string $id, ?string $detachKeys = null, ?bool $logs = null, ?bool $stream = null, ?bool $stdin = null, ?bool $stdout = null, ?bool $stderr = null): Response
    {
        return $this->containers()->attach($id, $detachKeys, $logs, $stream, $stdin, $stdout, $stderr);
    }

    /**
     * Attach output decoded into stdout and stderr (non-streaming; reads full response).
     *
     * @return array{stdout: string, stderr: string}
     */
    public function containerAttachDecoded(string $id, ?string $detachKeys = null, ?bool $logs = null, ?bool $stdin = null, ?bool $stdout = null, ?bool $stderr = null): array
    {
        $response = $this->containers()->attach($id, $detachKeys, $logs, false, $stdin, $stdout, $stderr);

        return StreamDecoder::decodeToStdoutStderr($response->stream());
    }

    /**
     * Events stream; use ->stream() for raw PSR-7 stream (e.g. for follow).
     */
    public function eventsStream(?string $since = null, ?string $until = null, ?array $filters = null): Response
    {
        return $this->system()->events($since, $until, $filters);
    }

    /**
     * Events decoded as array of event arrays (reads response body; for non-follow usage).
     *
     * @return array<int, array<string, mixed>>
     */
    public function eventsDecoded(?string $since = null, ?string $until = null, ?array $filters = null): array
    {
        $response = $this->system()->events($since, $until, $filters);
        $body = $response->body();
        $events = [];
        foreach (explode("\n", $body) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $decoded = json_decode($line, true);
                if (is_array($decoded)) {
                    $events[] = $decoded;
                }
            }
        }

        return $events;
    }
}
