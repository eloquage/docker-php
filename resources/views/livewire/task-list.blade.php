<div wire:poll.5s="loadTasks">
    @if(!$swarmInitialized)
        <flux:callout variant="warning" class="mb-6">
            <flux:callout.text>Docker Swarm must be initialized to use Tasks. <a href="{{ route('docker-php.swarm.index') }}" class="font-medium underline">Go to Swarm</a></flux:callout.text>
        </flux:callout>
    @endif
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Tasks</flux:heading>
        @if($swarmInitialized)
            <flux:button wire:click="loadTasks">Refresh</flux:button>
        @else
            <flux:button disabled>Refresh</flux:button>
        @endif
    </div>

    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error])
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Service ID</flux:table.column>
            <flux:table.column>Node ID</flux:table.column>
            <flux:table.column>Desired state</flux:table.column>
            <flux:table.column>Current state</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($tasks as $t)
                <flux:table.row :key="$t['ID']">
                    <flux:table.cell class="font-mono text-xs">{{ substr($t['ID'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell class="font-mono text-xs">{{ substr($t['ServiceID'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell class="font-mono text-xs">{{ substr($t['NodeID'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell>{{ $t['DesiredState'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $t['Status']['State'] ?? '—' }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($tasks))
        <flux:callout variant="info" class="mt-6">No tasks found.</flux:callout>
    @endif
</div>
