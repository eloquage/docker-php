<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class PluginList extends Component
{
    public array $plugins = [];

    public ?string $error = null;

    public ?string $message = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadPlugins();
    }

    public function loadPlugins(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->plugins()->list(null);
            $body = $response->successful() ? $response->json() : [];
            $this->plugins = is_array($body) && array_is_list($body) ? $body : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function enable(string $name): void
    {
        try {
            $this->docker->plugins()->enable($name);
            $this->message = 'Plugin enabled.';
            $this->loadPlugins();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function disable(string $name): void
    {
        try {
            $this->docker->plugins()->disable($name);
            $this->message = 'Plugin disabled.';
            $this->loadPlugins();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function remove(string $name): void
    {
        try {
            $this->docker->plugins()->delete($name, true);
            $this->message = 'Plugin removed.';
            $this->loadPlugins();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.plugin-list');
    }
}
