<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class SecretList extends Component
{
    public array $secrets = [];

    public ?string $error = null;

    public ?string $message = null;

    public bool $showCreateModal = false;

    public string $createName = '';

    public string $createData = '';

    public string $createLabels = '';

    public string $createDriverName = '';

    public string $createDriverOptions = '';

    public string $createTemplatingName = '';

    public string $createTemplatingOptions = '';

    public bool $swarmInitialized = false;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->refreshSwarmStatus();
        $this->loadSecrets();
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

    public function loadSecrets(): void
    {
        $this->refreshSwarmStatus();
        $this->error = null;
        if (! $this->swarmInitialized) {
            $this->secrets = [];

            return;
        }
        try {
            $response = $this->docker->secrets()->list(null);
            $this->secrets = $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
        $this->createName = '';
        $this->createData = '';
        $this->createLabels = '';
        $this->createDriverName = '';
        $this->createDriverOptions = '';
        $this->createTemplatingName = '';
        $this->createTemplatingOptions = '';
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createSecret(): void
    {
        if (! $this->swarmInitialized) {
            return;
        }
        if ($this->createName === '') {
            $this->error = 'Name is required.';

            return;
        }
        try {
            $body = [
                'Name' => $this->createName,
                'Data' => base64_encode($this->createData),
            ];
            $labels = $this->parseKeyValue($this->createLabels);
            if ($labels !== []) {
                $body['Labels'] = $labels;
            }
            if ($this->createDriverName !== '') {
                $body['Driver'] = [
                    'Name' => $this->createDriverName,
                    'Options' => $this->parseKeyValue($this->createDriverOptions),
                ];
            }
            if ($this->createTemplatingName !== '') {
                $body['Templating'] = [
                    'Name' => $this->createTemplatingName,
                    'Options' => $this->parseKeyValue($this->createTemplatingOptions),
                ];
            }
            $this->docker->secrets()->create($body);
            $this->message = 'Secret created.';
            $this->closeCreateModal();
            $this->loadSecrets();
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

    public function remove(string $id): void
    {
        if (! $this->swarmInitialized) {
            return;
        }
        try {
            $this->docker->secrets()->delete($id);
            $this->message = 'Secret removed.';
            $this->loadSecrets();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.secret-list');
    }
}
