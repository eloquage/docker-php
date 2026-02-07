<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class NodeList extends Component
{
    public array $nodes = [];

    public ?string $error = null;

    public ?string $message = null;

    public bool $swarmInitialized = false;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->refreshSwarmStatus();
        $this->loadNodes();
    }

    protected function refreshSwarmStatus(): void
    {
        try {
            $response = $this->docker->swarm()->get();
            $data = $response->successful() ? $response->json() : [];
            $this->swarmInitialized = is_array($data) && isset($data['ID']);
        } catch (\Throwable) {
            $this->swarmInitialized = false;
        }
    }

    public function loadNodes(): void
    {
        $this->refreshSwarmStatus();
        $this->error = null;
        if (! $this->swarmInitialized) {
            $this->nodes = [];

            return;
        }
        try {
            $response = $this->docker->nodes()->list(null);
            $this->nodes = $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function remove(string $id): void
    {
        if (! $this->swarmInitialized) {
            return;
        }
        try {
            $this->docker->nodes()->delete($id, true);
            $this->message = 'Node removed.';
            $this->loadNodes();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.node-list');
    }
}
