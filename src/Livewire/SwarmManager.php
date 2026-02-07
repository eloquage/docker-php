<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class SwarmManager extends Component
{
    public array $swarm = [];

    public ?string $error = null;

    public ?string $message = null;

    public string $initListenAddr = '0.0.0.0:2377';

    public string $initAdvertiseAddr = '';

    public string $initDataPathAddr = '';

    public string $initDataPathPort = '';

    public string $initDefaultAddrPool = '';

    public string $initSubnetSize = '24';

    public bool $initForceNewCluster = false;

    public string $joinListenAddr = '0.0.0.0:2377';

    public string $joinDataPathAddr = '';

    public string $joinAdvertiseAddr = '';

    public string $joinToken = '';

    public string $joinRemoteAddrs = '';

    public bool $leaveForce = false;

    public ?string $unlockKeyDisplay = null;

    public string $unlockKeyInput = '';

    public bool $showUnlockKey = false;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadSwarm();
    }

    public function loadSwarm(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->swarm()->get();
            $data = $response->successful() ? $response->json() : null;
            $this->swarm = is_array($data) ? $data : [];
        } catch (\Throwable $e) {
            $this->swarm = [];
            $this->error = $e->getMessage();
        }
    }

    public function initSwarm(): void
    {
        $this->message = null;
        $this->error = null;
        try {
            $body = [
                'ListenAddr' => $this->initListenAddr ?: '0.0.0.0:2377',
                'ForceNewCluster' => $this->initForceNewCluster,
            ];
            if ($this->initAdvertiseAddr !== '') {
                $body['AdvertiseAddr'] = $this->initAdvertiseAddr;
            }
            if ($this->initDataPathAddr !== '') {
                $body['DataPathAddr'] = $this->initDataPathAddr;
            }
            if ($this->initDataPathPort !== '') {
                $body['DataPathPort'] = (int) $this->initDataPathPort;
            }
            if ($this->initDefaultAddrPool !== '') {
                $body['DefaultAddrPool'] = array_map('trim', array_filter(explode(',', $this->initDefaultAddrPool)));
            }
            if ($this->initSubnetSize !== '') {
                $body['SubnetSize'] = (int) $this->initSubnetSize;
            }
            $this->docker->swarm()->init($body);
            $this->message = 'Swarm initialized.';
            $this->loadSwarm();
            if (empty($this->swarm) && $this->error === null) {
                sleep(1);
                $this->loadSwarm();
            }
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function joinSwarm(): void
    {
        $this->message = null;
        $this->error = null;
        if ($this->joinToken === '' || $this->joinRemoteAddrs === '') {
            $this->error = 'Join token and remote address(es) are required.';

            return;
        }
        try {
            $remoteAddrs = array_map('trim', array_filter(explode(',', $this->joinRemoteAddrs)));
            $body = [
                'ListenAddr' => $this->joinListenAddr ?: '0.0.0.0:2377',
                'JoinToken' => $this->joinToken,
                'RemoteAddrs' => $remoteAddrs,
            ];
            if ($this->joinAdvertiseAddr !== '') {
                $body['AdvertiseAddr'] = $this->joinAdvertiseAddr;
            }
            if ($this->joinDataPathAddr !== '') {
                $body['DataPathAddr'] = $this->joinDataPathAddr;
            }
            $this->docker->swarm()->join($body);
            $this->message = 'Joined swarm.';
            $this->loadSwarm();
            if (empty($this->swarm) && $this->error === null) {
                sleep(1);
                $this->loadSwarm();
            }
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function leaveSwarm(): void
    {
        $this->message = null;
        $this->error = null;
        try {
            $this->docker->swarm()->leave($this->leaveForce);
            $this->message = 'Left swarm.';
            $this->loadSwarm();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function loadUnlockKey(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->swarm()->unlockKey();
            $data = $response->successful() ? $response->json() : [];
            $this->unlockKeyDisplay = $data['UnlockKey'] ?? null;
            $this->showUnlockKey = true;
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function unlockSwarm(): void
    {
        $this->message = null;
        $this->error = null;
        if ($this->unlockKeyInput === '') {
            $this->error = 'Unlock key is required.';

            return;
        }
        try {
            $this->docker->swarm()->unlock(['UnlockKey' => $this->unlockKeyInput]);
            $this->message = 'Swarm unlocked.';
            $this->unlockKeyInput = '';
            $this->loadSwarm();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.swarm-manager');
    }
}
