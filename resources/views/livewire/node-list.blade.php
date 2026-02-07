<div wire:poll.5s="loadNodes">
    @if(!$swarmInitialized)
        <flux:callout variant="warning" class="mb-6">
            <flux:callout.text>Docker Swarm must be initialized to use Nodes. <a href="{{ route('docker-php.swarm.index') }}" class="font-medium underline">Go to Swarm</a></flux:callout.text>
        </flux:callout>
    @endif
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Nodes</flux:heading>
        @if($swarmInitialized)
            <flux:button wire:click="loadNodes">Refresh</flux:button>
        @else
            <flux:button disabled>Refresh</flux:button>
        @endif
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
            <flux:table.column>Hostname</flux:table.column>
            <flux:table.column>Role</flux:table.column>
            <flux:table.column>Availability</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($nodes as $n)
                @php $desc = $n['Description'] ?? []; $status = $n['Status'] ?? []; $spec = $n['Spec'] ?? []; @endphp
                <flux:table.row :key="$n['ID']">
                    <flux:table.cell class="font-mono text-xs">{{ substr($n['ID'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell>{{ $desc['Hostname'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $spec['Role'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $spec['Availability'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $status['State'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>
                        @if($swarmInitialized)
                            <flux:button size="sm" variant="outline" wire:click="remove('{{ $n['ID'] }}')" wire:confirm="Remove this node from swarm?">Remove</flux:button>
                        @else
                            <flux:button size="sm" variant="outline" disabled>Remove</flux:button>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($nodes))
        <flux:callout variant="info" class="mt-6">No nodes found. Swarm may not be initialized.</flux:callout>
    @endif
</div>
