<div wire:poll.5s="loadNetworks">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Networks</flux:heading>
        <div class="flex gap-2">
            <flux:button wire:click="openCreateModal">Create network</flux:button>
            <flux:button variant="outline" wire:click="prune" wire:confirm="Prune unused networks?">Prune</flux:button>
            <flux:button wire:click="loadNetworks">Refresh</flux:button>
        </div>
    </div>

    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error])
    @endif
    @if($message)
        @include('docker-php::partials.notification-callout', ['type' => 'success', 'message' => $message])
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Driver</flux:table.column>
            <flux:table.column>Scope</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($networks as $n)
                <flux:table.row :key="$n['Id']">
                    <flux:table.cell class="font-mono text-xs">{{ substr($n['Id'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell>{{ $n['Name'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $n['Driver'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $n['Scope'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" variant="outline" wire:click="inspectNetwork('{{ $n['Id'] }}')">Inspect</flux:button>
                        @if(($n['Name'] ?? '') !== 'bridge' && ($n['Name'] ?? '') !== 'host' && ($n['Name'] ?? '') !== 'none')
                            <flux:button size="sm" variant="outline" wire:click="remove('{{ $n['Id'] }}')" wire:confirm="Remove this network?">Remove</flux:button>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($networks))
        <flux:callout variant="info" class="mt-6">No networks found.</flux:callout>
    @endif

    @if($showCreateModal)
        <flux:modal name="create-network" wire:model="showCreateModal" class="md:w-[40rem]">
            <flux:heading size="lg" class="mb-4">Create network</flux:heading>
            <flux:field class="mb-4">
                <flux:label>Name</flux:label>
                <flux:input wire:model="createName" placeholder="my-network" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Driver</flux:label>
                <flux:select wire:model="createDriver">
                    <option value="bridge">bridge</option>
                    <option value="overlay">overlay</option>
                    <option value="macvlan">macvlan</option>
                    <option value="none">none</option>
                </flux:select>
            </flux:field>
            @if(in_array($createDriver, ['bridge', 'macvlan'], true) && count($hostNetworkInterfaces) > 0)
                <flux:field class="mb-4">
                    <flux:label>Host interface (optional)</flux:label>
                    <flux:select wire:model="createHostInterface" placeholder="{{ __('Default') }}">
                        <option value="">{{ __('Default') }}</option>
                        @foreach($hostNetworkInterfaces as $iface)
                            <option value="{{ $iface }}">{{ $iface }}</option>
                        @endforeach
                    </flux:select>
                    <flux:description>
                        {{ $createDriver === 'bridge' ? __('Sets com.docker.network.bridge.name.') : __('Parent interface for macvlan.') }}
                    </flux:description>
                </flux:field>
            @endif
            <div class="flex flex-wrap gap-4 mb-4">
                <flux:checkbox wire:model="createCheckDuplicate" label="Check duplicate" />
                <flux:checkbox wire:model="createInternal" label="Internal" />
                <flux:checkbox wire:model="createAttachable" label="Attachable" />
                <flux:checkbox wire:model="createIngress" label="Ingress" />
                <flux:checkbox wire:model="createEnableIPv6" label="Enable IPv6" />
            </div>
            <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">IPAM (optional)</flux:subheading>
            <flux:field class="mb-2">
                <flux:label>Subnet</flux:label>
                <flux:input wire:model="createSubnet" placeholder="172.28.0.0/16" />
            </flux:field>
            <flux:field class="mb-2">
                <flux:label>Gateway</flux:label>
                <flux:input wire:model="createGateway" placeholder="172.28.0.1" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>IP range</flux:label>
                <flux:input wire:model="createIpRange" placeholder="172.28.5.0/24" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>IPAM driver</flux:label>
                <flux:input wire:model="createIpamDriver" placeholder="default" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Options (driver-specific, one per line: key=value)</flux:label>
                <flux:textarea wire:model="createOptions" placeholder="com.docker.network.bridge.name=br0" rows="2" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Labels (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createLabels" placeholder="com.example.network=my-net" rows="3" />
            </flux:field>
            <div class="flex gap-2">
                <flux:button wire:click="createNetwork">Create</flux:button>
                <flux:button variant="outline" wire:click="closeCreateModal">Cancel</flux:button>
            </div>
        </flux:modal>
    @endif

    @if($showInspectModal)
        <flux:modal name="inspect-network" wire:model="showInspectModal" class="md:w-[40rem]">
            <flux:heading size="lg" class="mb-4">Network details</flux:heading>
            <pre class="text-xs overflow-auto p-4 bg-zinc-100 dark:bg-zinc-800 rounded-lg max-h-96">{{ json_encode($inspectData, JSON_PRETTY_PRINT) }}</pre>
            <flux:button variant="outline" wire:click="closeInspectModal" class="mt-4">Close</flux:button>
        </flux:modal>
    @endif
</div>
