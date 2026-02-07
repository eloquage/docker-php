<div wire:poll.5s="loadVolumes">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Volumes</flux:heading>
        <div class="flex gap-2">
            <flux:button wire:click="openCreateModal">Create volume</flux:button>
            <flux:button variant="outline" wire:click="prune" wire:confirm="Prune unused volumes?">Prune</flux:button>
            <flux:button wire:click="loadVolumes">Refresh</flux:button>
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
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Driver</flux:table.column>
            <flux:table.column>Scope</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($volumes as $v)
                <flux:table.row :key="$v['Name']">
                    <flux:table.cell>{{ $v['Name'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $v['Driver'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $v['Scope'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" variant="outline" wire:click="inspectVolume('{{ $v['Name'] }}')">Inspect</flux:button>
                        <flux:button size="sm" variant="outline" wire:click="remove('{{ $v['Name'] }}')" wire:confirm="Remove this volume?">Remove</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($volumes))
        <flux:callout variant="info" class="mt-6">No volumes found.</flux:callout>
    @endif

    @if($showCreateModal)
        <flux:modal name="create-volume" wire:model="showCreateModal" class="md:w-[40rem]">
            <flux:heading size="lg" class="mb-4">Create volume</flux:heading>
            <flux:field class="mb-4">
                <flux:label>Name (optional)</flux:label>
                <flux:input wire:model="createName" placeholder="volume-name" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Driver</flux:label>
                <flux:input wire:model="createDriver" placeholder="local" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Driver options (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createDriverOpts" placeholder="type=nfs" rows="2" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Labels (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createLabels" placeholder="com.example.volume=my-vol" rows="2" />
            </flux:field>
            <div class="flex gap-2">
                <flux:button wire:click="createVolume">Create</flux:button>
                <flux:button variant="outline" wire:click="closeCreateModal">Cancel</flux:button>
            </div>
        </flux:modal>
    @endif

    @if($showInspectModal)
        <flux:modal name="inspect-volume" wire:model="showInspectModal" class="md:w-[40rem]">
            <flux:heading size="lg" class="mb-4">Volume details</flux:heading>
            <pre class="text-xs overflow-auto p-4 bg-zinc-100 dark:bg-zinc-800 rounded-lg max-h-96">{{ json_encode($inspectData, JSON_PRETTY_PRINT) }}</pre>
            <flux:button variant="outline" wire:click="closeInspectModal" class="mt-4">Close</flux:button>
        </flux:modal>
    @endif
</div>
