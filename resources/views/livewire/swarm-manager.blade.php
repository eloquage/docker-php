<div wire:poll.5s="loadSwarm">
    <flux:heading size="xl" class="mb-6">Swarm</flux:heading>

    @if($message)
        @include('docker-php::partials.notification-callout', ['type' => 'success', 'message' => $message])
    @endif
    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error])
    @endif

    @if(!empty($swarm))
        <flux:card class="mb-8">
            <flux:heading size="base" class="mb-4">Swarm status</flux:heading>
            <dl class="grid gap-2 text-sm sm:grid-cols-2">
                <div><span class="text-zinc-500 dark:text-zinc-400">Cluster ID:</span> <span class="font-mono">{{ $swarm['ID'] ?? '—' }}</span></div>
                <div><span class="text-zinc-500 dark:text-zinc-400">Created:</span> {{ isset($swarm['CreatedAt']) ? \Carbon\Carbon::parse($swarm['CreatedAt'])->toDateTimeString() : '—' }}</div>
                <div><span class="text-zinc-500 dark:text-zinc-400">Updated:</span> {{ isset($swarm['UpdatedAt']) ? \Carbon\Carbon::parse($swarm['UpdatedAt'])->toDateTimeString() : '—' }}</div>
                @if(isset($swarm['Spec']['Version']['Index']))
                    <div><span class="text-zinc-500 dark:text-zinc-400">Version index:</span> {{ $swarm['Spec']['Version']['Index'] }}</div>
                @endif
            </dl>
        </flux:card>

        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <flux:card>
                <flux:heading size="base" class="mb-4">Leave swarm</flux:heading>
                <flux:checkbox wire:model="leaveForce" label="Force leave" class="mb-4" />
                <flux:button variant="danger" wire:click="leaveSwarm" wire:confirm="Leave the swarm? This node will no longer be part of the cluster.">Leave swarm</flux:button>
            </flux:card>
            <flux:card>
                <flux:heading size="base" class="mb-4">Unlock key</flux:heading>
                @if($showUnlockKey && $unlockKeyDisplay)
                    <p class="font-mono text-sm break-all mb-4 rounded bg-zinc-100 dark:bg-zinc-800 p-3">{{ $unlockKeyDisplay }}</p>
                @endif
                <flux:button variant="outline" wire:click="loadUnlockKey">Show unlock key</flux:button>
            </flux:card>
        </div>

        <flux:card class="mb-8">
            <flux:heading size="base" class="mb-4">Unlock swarm</flux:heading>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Use this when the swarm is locked (e.g. after restart).</p>
            <flux:field class="mb-4">
                <flux:label>Unlock key</flux:label>
                <flux:input type="password" wire:model="unlockKeyInput" placeholder="Paste unlock key" />
            </flux:field>
            <flux:button wire:click="unlockSwarm">Unlock</flux:button>
        </flux:card>
    @else
        <flux:callout variant="info" class="mb-8">This node is not in a swarm. Initialize a new swarm or join an existing one.</flux:callout>

        <div class="grid gap-6 lg:grid-cols-2">
            <flux:card>
                <flux:heading size="base" class="mb-4">Init swarm</flux:heading>
                <flux:field class="mb-4">
                    <flux:label>Listen address</flux:label>
                    <flux:input wire:model="initListenAddr" placeholder="0.0.0.0:2377" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Advertise address (optional)</flux:label>
                    <flux:input wire:model="initAdvertiseAddr" placeholder="192.168.1.100:2377" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Data path address (optional)</flux:label>
                    <flux:input wire:model="initDataPathAddr" placeholder="eth0" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Data path port (1024-49151, optional)</flux:label>
                    <flux:input type="number" wire:model="initDataPathPort" placeholder="4789" min="1024" max="49151" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Default address pool (comma-separated CIDRs, optional)</flux:label>
                    <flux:input wire:model="initDefaultAddrPool" placeholder="10.10.0.0/16" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Subnet size (optional)</flux:label>
                    <flux:input wire:model="initSubnetSize" placeholder="24" />
                </flux:field>
                <flux:checkbox wire:model="initForceNewCluster" label="Force new cluster" class="mb-4" />
                <flux:button wire:click="initSwarm">Initialize swarm</flux:button>
            </flux:card>
            <flux:card>
                <flux:heading size="base" class="mb-4">Join swarm</flux:heading>
                <flux:field class="mb-4">
                    <flux:label>Remote address(es)</flux:label>
                    <flux:input wire:model="joinRemoteAddrs" placeholder="192.168.1.1:2377 or host1:2377,host2:2377" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Join token</flux:label>
                    <flux:input wire:model="joinToken" placeholder="SWMTKN-1-..." />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Listen address</flux:label>
                    <flux:input wire:model="joinListenAddr" placeholder="0.0.0.0:2377" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Advertise address (optional)</flux:label>
                    <flux:input wire:model="joinAdvertiseAddr" placeholder="192.168.1.100:2377" />
                </flux:field>
                <flux:field class="mb-4">
                    <flux:label>Data path address (optional)</flux:label>
                    <flux:input wire:model="joinDataPathAddr" placeholder="eth0" />
                </flux:field>
                <flux:button wire:click="joinSwarm">Join swarm</flux:button>
            </flux:card>
        </div>
    @endif
</div>
