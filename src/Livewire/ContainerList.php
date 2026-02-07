<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class ContainerList extends Component
{
    public array $containers = [];

    public string $search = '';

    public ?string $error = null;

    public ?string $message = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadContainers();
    }

    public function loadContainers(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->containers()->list(true, null, true, null);
            $body = $response->successful() ? $response->json() : [];
            $this->containers = is_array($body) && array_is_list($body) ? $body : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getFilteredContainers(): array
    {
        if ($this->search === '') {
            return $this->containers;
        }
        $term = strtolower($this->search);
        return array_values(array_filter($this->containers, function (array $c) use ($term) {
            $names = implode(' ', $c['Names'] ?? []);
            $id = $c['Id'] ?? '';
            $image = $c['Image'] ?? '';
            return str_contains(strtolower($names), $term) || str_contains(strtolower($id), $term) || str_contains(strtolower($image), $term);
        }));
    }

    public function start(string $id): void
    {
        try {
            $this->docker->containers()->start($id);
            $this->message = 'Container started.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function stop(string $id): void
    {
        try {
            $this->docker->containers()->stop($id);
            $this->message = 'Container stopped.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function restart(string $id): void
    {
        try {
            $this->docker->containers()->restart($id);
            $this->message = 'Container restarted.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function kill(string $id): void
    {
        try {
            $this->docker->containers()->kill($id);
            $this->message = 'Container killed.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function pause(string $id): void
    {
        try {
            $this->docker->containers()->pause($id);
            $this->message = 'Container paused.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function unpause(string $id): void
    {
        try {
            $this->docker->containers()->unpause($id);
            $this->message = 'Container unpaused.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function remove(string $id): void
    {
        try {
            $this->docker->containers()->delete($id, false, true);
            $this->message = 'Container removed.';
            $this->loadContainers();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.container-list');
    }
}
