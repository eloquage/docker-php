<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Eloquage\DockerPhp\Support\NetworkInterfaces;
use Livewire\Component;

class NetworkList extends Component
{
    public array $networks = [];

    public ?string $error = null;

    public ?string $message = null;

    public bool $showCreateModal = false;

    public string $createName = '';

    public string $createDriver = 'bridge';

    public bool $createCheckDuplicate = true;

    public bool $createInternal = false;

    public bool $createAttachable = false;

    public bool $createIngress = false;

    public bool $createEnableIPv6 = false;

    public string $createSubnet = '';

    public string $createGateway = '';

    public string $createIpRange = '';

    public string $createIpamDriver = 'default';

    public string $createLabels = '';

    public string $createOptions = '';

    public string $createHostInterface = '';

    /** @var array<int, string> */
    public array $hostNetworkInterfaces = [];

    public bool $showInspectModal = false;

    public array $inspectData = [];

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadNetworks();
    }

    public function loadNetworks(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->networks()->list(null);
            $body = $response->successful() ? $response->json() : [];
            $this->networks = is_array($body) && array_is_list($body) ? $body : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
        $this->createName = '';
        $this->createDriver = 'bridge';
        $this->createCheckDuplicate = true;
        $this->createInternal = false;
        $this->createAttachable = false;
        $this->createIngress = false;
        $this->createEnableIPv6 = false;
        $this->createSubnet = '';
        $this->createGateway = '';
        $this->createIpRange = '';
        $this->createIpamDriver = 'default';
        $this->createLabels = '';
        $this->createOptions = '';
        $this->createHostInterface = '';
        $this->hostNetworkInterfaces = NetworkInterfaces::getInterfaceNames();
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createNetwork(): void
    {
        try {
            $body = [
                'Name' => $this->createName ?: 'new-network',
                'CheckDuplicate' => $this->createCheckDuplicate,
                'Driver' => $this->createDriver ?: 'bridge',
                'Internal' => $this->createInternal,
                'Attachable' => $this->createAttachable,
                'Ingress' => $this->createIngress,
                'EnableIPv6' => $this->createEnableIPv6,
            ];
            $ipamConfig = [];
            if ($this->createSubnet !== '' || $this->createGateway !== '' || $this->createIpRange !== '') {
                $entry = [];
                if ($this->createSubnet !== '') {
                    $entry['Subnet'] = $this->createSubnet;
                }
                if ($this->createGateway !== '') {
                    $entry['Gateway'] = $this->createGateway;
                }
                if ($this->createIpRange !== '') {
                    $entry['IPRange'] = $this->createIpRange;
                }
                $ipamConfig[] = $entry;
            }
            if ($ipamConfig !== [] || $this->createIpamDriver !== 'default') {
                $body['IPAM'] = [
                    'Driver' => $this->createIpamDriver ?: 'default',
                    'Config' => $ipamConfig,
                ];
            }
            $labels = $this->parseLabels($this->createLabels);
            if ($labels !== []) {
                $body['Labels'] = $labels;
            }
            $options = $this->parseLabels($this->createOptions);
            if ($this->createHostInterface !== '') {
                if ($this->createDriver === 'bridge') {
                    $options['com.docker.network.bridge.name'] = $this->createHostInterface;
                }
                if ($this->createDriver === 'macvlan') {
                    $options['parent'] = $this->createHostInterface;
                }
            }
            if ($options !== []) {
                $body['Options'] = $options;
            }
            $this->docker->networks()->create($body);
            $this->message = 'Network created.';
            $this->closeCreateModal();
            $this->loadNetworks();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * @return array<string, string>
     */
    protected function parseLabels(string $input): array
    {
        $labels = [];
        foreach (preg_split('/\r\n|\r|\n/', trim($input), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $labels[trim($key)] = trim($value);
            }
        }

        return $labels;
    }

    public function inspectNetwork(string $id): void
    {
        try {
            $response = $this->docker->networks()->inspect($id);
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

    public function remove(string $id): void
    {
        try {
            $this->docker->networks()->delete($id);
            $this->message = 'Network removed.';
            $this->loadNetworks();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function prune(): void
    {
        try {
            $this->docker->networks()->prune(null);
            $this->message = 'Unused networks pruned.';
            $this->loadNetworks();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.network-list');
    }
}
