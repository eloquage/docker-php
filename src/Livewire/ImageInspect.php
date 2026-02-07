<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class ImageInspect extends Component
{
    public string $name;

    public array $inspect = [];

    public array $history = [];

    public ?string $error = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(string $name): void
    {
        $this->name = $name;
        $this->load();
    }

    public function load(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->images()->inspect($this->name);
            $this->inspect = $response->successful() ? $response->json() : [];

            $historyResponse = $this->docker->images()->history($this->name);
            $this->history = $historyResponse->successful() ? $historyResponse->json() : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.image-inspect');
    }
}
