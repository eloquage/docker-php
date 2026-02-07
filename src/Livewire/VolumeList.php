<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class VolumeList extends Component
{
    public array $volumes = [];

    public ?string $error = null;

    public ?string $message = null;

    public bool $showCreateModal = false;

    public string $createName = '';

    public string $createDriver = 'local';

    public string $createDriverOpts = '';

    public string $createLabels = '';

    public bool $showInspectModal = false;

    public array $inspectData = [];

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadVolumes();
    }

    public function loadVolumes(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->volumes()->list(null);
            $data = $response->successful() ? $response->json() : [];
            $this->volumes = $data['Volumes'] ?? [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
        $this->createName = '';
        $this->createDriver = 'local';
        $this->createDriverOpts = '';
        $this->createLabels = '';
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createVolume(): void
    {
        try {
            $body = [];
            if ($this->createName !== '') {
                $body['Name'] = $this->createName;
            }
            if ($this->createDriver !== '') {
                $body['Driver'] = $this->createDriver;
            }
            $driverOpts = $this->parseKeyValue($this->createDriverOpts);
            if ($driverOpts !== []) {
                $body['DriverOpts'] = $driverOpts;
            }
            $labels = $this->parseKeyValue($this->createLabels);
            if ($labels !== []) {
                $body['Labels'] = $labels;
            }
            $this->docker->volumes()->create($body);
            $this->message = 'Volume created.';
            $this->closeCreateModal();
            $this->loadVolumes();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * @return array<string, string>
     */
    protected function parseKeyValue(string $input): array
    {
        $result = [];
        foreach (preg_split('/\r\n|\r|\n/', trim($input), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || ! str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $result[trim($key)] = trim($value);
        }

        return $result;
    }

    public function inspectVolume(string $name): void
    {
        try {
            $response = $this->docker->volumes()->inspect($name);
            $this->inspectData = $response->successful() ? $response->json() : [];
            $this->showInspectModal = true;
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function closeInspectModal(): void
    {
        $this->showInspectModal = false;
    }

    public function remove(string $name): void
    {
        try {
            $this->docker->volumes()->delete($name, false);
            $this->message = 'Volume removed.';
            $this->loadVolumes();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function prune(): void
    {
        try {
            $this->docker->volumes()->prune(null);
            $this->message = 'Unused volumes pruned.';
            $this->loadVolumes();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.volume-list');
    }
}
