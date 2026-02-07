<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class ServiceList extends Component
{
    public array $services = [];

    public ?string $error = null;

    public ?string $message = null;

    public bool $showCreateModal = false;

    public string $createName = '';

    public string $createImage = '';

    public string $createCommand = '';

    public string $createArgs = '';

    public string $createEnv = '';

    public int $createReplicas = 1;

    public bool $createModeGlobal = false;

    public string $createPublishPort = '';

    public string $createTargetPort = '';

    public string $createProtocol = 'tcp';

    public string $createPublishMode = 'ingress';

    public string $createLabels = '';

    public string $createNetworks = '';

    public string $createHostname = '';

    public string $createUser = '';

    public string $createWorkdir = '';

    public bool $swarmInitialized = false;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->refreshSwarmStatus();
        $this->loadServices();
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

    public function loadServices(): void
    {
        $this->refreshSwarmStatus();
        $this->error = null;
        if (! $this->swarmInitialized) {
            $this->services = [];

            return;
        }
        try {
            $response = $this->docker->services()->list(null, null);
            $body = $response->successful() ? $response->json() : [];
            $this->services = is_array($body) && array_is_list($body) ? $body : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
        $this->createName = '';
        $this->createImage = '';
        $this->createCommand = '';
        $this->createArgs = '';
        $this->createEnv = '';
        $this->createReplicas = 1;
        $this->createModeGlobal = false;
        $this->createPublishPort = '';
        $this->createTargetPort = '';
        $this->createProtocol = 'tcp';
        $this->createPublishMode = 'ingress';
        $this->createLabels = '';
        $this->createNetworks = '';
        $this->createHostname = '';
        $this->createUser = '';
        $this->createWorkdir = '';
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createService(): void
    {
        if (! $this->swarmInitialized) {
            return;
        }
        $this->error = null;
        if ($this->createName === '' || $this->createImage === '') {
            $this->error = 'Name and Image are required.';

            return;
        }
        try {
            $containerSpec = [
                'Image' => $this->createImage,
            ];
            if ($this->createCommand !== '') {
                $containerSpec['Command'] = array_values(array_filter(explode(' ', $this->createCommand)));
            }
            if ($this->createArgs !== '') {
                $containerSpec['Args'] = array_values(array_filter(preg_split('/\r\n|\r|\n/', $this->createArgs)));
            }
            if ($this->createEnv !== '') {
                $containerSpec['Env'] = array_values(array_filter(preg_split('/\r\n|\r|\n/', trim($this->createEnv))));
            }
            if ($this->createHostname !== '') {
                $containerSpec['Hostname'] = $this->createHostname;
            }
            if ($this->createUser !== '') {
                $containerSpec['User'] = $this->createUser;
            }
            if ($this->createWorkdir !== '') {
                $containerSpec['Dir'] = $this->createWorkdir;
            }

            $taskTemplate = ['ContainerSpec' => $containerSpec];

            $networkTargets = array_map('trim', array_filter(explode(',', $this->createNetworks)));
            if ($networkTargets !== []) {
                $taskTemplate['Networks'] = array_map(fn (string $t) => ['Target' => $t], $networkTargets);
            }

            $body = [
                'Name' => $this->createName,
                'TaskTemplate' => $taskTemplate,
                'Mode' => $this->createModeGlobal
                    ? ['Global' => (object) []]
                    : ['Replicated' => ['Replicas' => max(0, $this->createReplicas)]],
            ];

            $labels = $this->parseKeyValue($this->createLabels);
            if ($labels !== []) {
                $body['Labels'] = $labels;
            }

            if ($this->createTargetPort !== '') {
                $targetPort = (int) $this->createTargetPort;
                $publishedPort = $this->createPublishPort !== '' ? (int) $this->createPublishPort : 0;
                $body['EndpointSpec'] = [
                    'Ports' => [[
                        'TargetPort' => $targetPort,
                        'PublishedPort' => $publishedPort,
                        'Protocol' => $this->createProtocol,
                        'PublishMode' => $this->createPublishMode,
                    ]],
                ];
            }

            $this->docker->services()->create($body);
            $this->message = 'Service created.';
            $this->closeCreateModal();
            $this->loadServices();
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
            $this->docker->services()->delete($id);
            $this->message = 'Service removed.';
            $this->loadServices();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.service-list');
    }
}
