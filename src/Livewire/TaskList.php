<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class TaskList extends Component
{
    public array $tasks = [];

    public ?string $error = null;

    public bool $swarmInitialized = false;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->refreshSwarmStatus();
        $this->loadTasks();
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

    public function loadTasks(): void
    {
        $this->refreshSwarmStatus();
        $this->error = null;
        if (! $this->swarmInitialized) {
            $this->tasks = [];

            return;
        }
        try {
            $response = $this->docker->tasks()->list(null);
            $this->tasks = $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.task-list');
    }
}
