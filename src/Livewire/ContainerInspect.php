<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class ContainerInspect extends Component
{
    public string $id;

    public array $inspect = [];

    public ?string $error = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(string $id): void
    {
        $this->id = $id;
        $this->load();
    }

    public function load(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->containers()->inspect($this->id, true);
            $this->inspect = $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.container-inspect');
    }
}
