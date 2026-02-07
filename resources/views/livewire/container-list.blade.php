<div wire:poll.5s="loadContainers">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Containers</flux:heading>
        <div class="flex gap-2">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." />
            <flux:button wire:click="loadContainers">Refresh</flux:button>
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
            <flux:table.column>Name / ID</flux:table.column>
            <flux:table.column>Image</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Ports</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->getFilteredContainers() as $c)
                <flux:table.row :key="$c['Id']">
                    <flux:table.cell>
                        <a href="{{ route('docker-php.containers.show', ['id' => $c['Id']]) }}" class="text-zinc-900 dark:text-white font-medium hover:underline">
                            {{ implode(', ', array_map(fn($n) => ltrim($n, '/'), $c['Names'] ?? [])) ?: substr($c['Id'] ?? '', 0, 12) }}
                        </a>
                        <div class="text-xs text-zinc-500">{{ substr($c['Id'] ?? '', 0, 12) }}</div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $c['Image'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>
                        @php $status = $c['State'] ?? 'unknown'; @endphp
                        @if($status === 'running')
                            <flux:badge color="success">running</flux:badge>
                        @elseif($status === 'paused')
                            <flux:badge color="warning">paused</flux:badge>
                        @else
                            <flux:badge color="danger">exited</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if(!empty($c['Ports']))
                            @foreach(array_slice($c['Ports'], 0, 3) as $p)
                                {{ $p['PublicPort'] ?? '' }}/{{ $p['Type'] ?? 'tcp' }}@if(!$loop->last), @endif
                            @endforeach
                            @if(count($c['Ports']) > 3)… @endif
                        @else
                            —
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button size="sm" variant="outline">Actions</flux:button>
                            <flux:menu>
                                @if(($c['State'] ?? '') !== 'running')
                                    <flux:menu.item wire:click="start('{{ $c['Id'] }}')" wire:confirm="Start this container?">Start</flux:menu.item>
                                @endif
                                @if(($c['State'] ?? '') === 'running')
                                    <flux:menu.item wire:click="stop('{{ $c['Id'] }}')" wire:confirm="Stop this container?">Stop</flux:menu.item>
                                    <flux:menu.item wire:click="restart('{{ $c['Id'] }}')" wire:confirm="Restart this container?">Restart</flux:menu.item>
                                    <flux:menu.item wire:click="pause('{{ $c['Id'] }}')">Pause</flux:menu.item>
                                    <flux:menu.item wire:click="kill('{{ $c['Id'] }}')" wire:confirm="Kill this container?">Kill</flux:menu.item>
                                @endif
                                @if(($c['State'] ?? '') === 'paused')
                                    <flux:menu.item wire:click="unpause('{{ $c['Id'] }}')">Unpause</flux:menu.item>
                                @endif
                                <flux:menu.separator />
                                <flux:menu.item wire:click="remove('{{ $c['Id'] }}')" wire:confirm="Remove this container? This cannot be undone.">Remove</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <a href="{{ route('docker-php.containers.logs', ['id' => $c['Id']]) }}" class="text-zinc-600 dark:text-zinc-400 hover:underline text-sm">Logs</a>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($this->getFilteredContainers()))
        <flux:callout variant="info" class="mt-6">No containers found.</flux:callout>
    @endif
</div>
