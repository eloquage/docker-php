<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Eloquage\DockerPhp\Requests\System\InfoRequest;
use Eloquage\DockerPhp\Requests\System\SystemDfRequest;
use Eloquage\DockerPhp\Requests\System\VersionRequest;
use Livewire\Component;
use Saloon\Http\Response;

class Dashboard extends Component
{
    public array $info = [];

    public array $version = [];

    public array $df = [];

    public ?string $error = null;

    public ?string $message = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->error = null;
        try {
            $connector = $this->docker->connector();
            $pool = $connector->pool(
                requests: [
                    'info' => new InfoRequest,
                    'version' => new VersionRequest,
                    'df' => new SystemDfRequest,
                ],
                concurrency: 3,
                responseHandler: function (Response $response, string $key): void {
                    if (! $response->successful()) {
                        return;
                    }
                    $data = $response->json();
                    match ($key) {
                        'info' => $this->info = is_array($data) ? $data : [],
                        'version' => $this->version = is_array($data) ? $data : [],
                        'df' => $this->df = is_array($data) ? $data : [],
                        default => null,
                    };
                },
                exceptionHandler: function (\Throwable $e): void {
                    $this->error = $e->getMessage();
                },
            );
            $pool->send()->wait();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function pruneContainers(): void
    {
        $this->message = null;
        try {
            $this->docker->containers()->prune(null);
            $this->message = 'Containers pruned.';
            $this->loadData();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function pruneImages(): void
    {
        $this->message = null;
        try {
            $this->docker->images()->prune(null);
            $this->message = 'Images pruned.';
            $this->loadData();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function pruneVolumes(): void
    {
        $this->message = null;
        try {
            $this->docker->volumes()->prune(null);
            $this->message = 'Volumes pruned.';
            $this->loadData();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function pruneNetworks(): void
    {
        $this->message = null;
        try {
            $this->docker->networks()->prune(null);
            $this->message = 'Networks pruned.';
            $this->loadData();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function pruneSystem(): void
    {
        $this->message = null;
        try {
            $this->docker->system()->prune(null);
            $this->message = 'System prune completed.';
            $this->loadData();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.dashboard');
    }
}
