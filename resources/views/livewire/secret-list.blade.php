<div wire:poll.5s="loadSecrets">
    @if(!$swarmInitialized)
        <flux:callout variant="warning" class="mb-6">
            <flux:callout.text>Docker Swarm must be initialized to use Secrets. <a href="{{ route('docker-php.swarm.index') }}" class="font-medium underline">Go to Swarm</a></flux:callout.text>
        </flux:callout>
    @endif
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Secrets</flux:heading>
        <div class="flex gap-2">
            @if($swarmInitialized)
                <flux:button wire:click="openCreateModal">Create secret</flux:button>
                <flux:button wire:click="loadSecrets">Refresh</flux:button>
            @else
                <flux:button disabled>Create secret</flux:button>
                <flux:button disabled>Refresh</flux:button>
            @endif
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
            <flux:table.column>Created</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($secrets as $s)
                <flux:table.row :key="$s['ID']">
                    <flux:table.cell class="font-mono text-xs">{{ substr($s['ID'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell>{{ $s['Spec']['Name'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ isset($s['CreatedAt']) ? date('Y-m-d H:i', strtotime($s['CreatedAt'])) : '—' }}</flux:table.cell>
                    <flux:table.cell>
                        @if($swarmInitialized)
                            <flux:button size="sm" variant="outline" wire:click="remove('{{ $s['ID'] }}')" wire:confirm="Remove this secret?">Remove</flux:button>
                        @else
                            <flux:button size="sm" variant="outline" disabled>Remove</flux:button>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($secrets))
        <flux:callout variant="info" class="mt-6">No secrets found.</flux:callout>
    @endif

    @if($showCreateModal)
        <flux:modal name="create-secret" wire:model="showCreateModal" class="md:w-[40rem]">
            <flux:heading size="lg" class="mb-4">Create secret</flux:heading>
            <flux:field class="mb-4">
                <flux:label>Name</flux:label>
                <flux:input wire:model="createName" placeholder="my-secret" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Data (plain text, will be base64-encoded)</flux:label>
                <flux:textarea wire:model="createData" placeholder="secret value" rows="3" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Labels (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createLabels" placeholder="com.example.secret=my-secret" rows="2" />
            </flux:field>
            <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Driver (optional)</flux:subheading>
            <flux:field class="mb-2">
                <flux:label>Driver name</flux:label>
                <flux:input wire:model="createDriverName" placeholder="internal" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Driver options (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createDriverOptions" placeholder="key=value" rows="2" />
            </flux:field>
            <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Templating (optional)</flux:subheading>
            <flux:field class="mb-2">
                <flux:label>Templating driver name</flux:label>
                <flux:input wire:model="createTemplatingName" placeholder="golang" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Templating options (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createTemplatingOptions" placeholder="key=value" rows="2" />
            </flux:field>
            <div class="flex gap-2">
                <flux:button wire:click="createSecret">Create</flux:button>
                <flux:button variant="outline" wire:click="closeCreateModal">Cancel</flux:button>
            </div>
        </flux:modal>
    @endif
</div>
