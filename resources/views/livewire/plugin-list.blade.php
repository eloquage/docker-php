<div wire:poll.5s="loadPlugins">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Plugins</flux:heading>
        <flux:button wire:click="loadPlugins">Refresh</flux:button>
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
            <flux:table.column>Enabled</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($plugins as $p)
                <flux:table.row :key="$p['Name']">
                    <flux:table.cell>{{ $p['Name'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>
                        @if($p['Enabled'] ?? false)
                            <flux:badge color="success">Enabled</flux:badge>
                        @else
                            <flux:badge color="danger">Disabled</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($p['Enabled'] ?? false)
                            <flux:button size="sm" variant="outline" wire:click="disable('{{ $p['Name'] }}')">Disable</flux:button>
                        @else
                            <flux:button size="sm" variant="outline" wire:click="enable('{{ $p['Name'] }}')">Enable</flux:button>
                        @endif
                        <flux:button size="sm" variant="outline" wire:click="remove('{{ $p['Name'] }}')" wire:confirm="Remove this plugin?">Remove</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($plugins))
        <flux:callout variant="info" class="mt-6">No plugins found.</flux:callout>
    @endif
</div>
