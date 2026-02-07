<div wire:poll.5s="loadServices">
    @if(!$swarmInitialized)
        <flux:callout variant="warning" class="mb-6">
            <flux:callout.text>Docker Swarm must be initialized to use Services. <a href="{{ route('docker-php.swarm.index') }}" class="font-medium underline">Go to Swarm</a></flux:callout.text>
        </flux:callout>
    @endif
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Services</flux:heading>
        <div class="flex gap-2">
            @if($swarmInitialized)
                <flux:button wire:click="openCreateModal">Create service</flux:button>
                <flux:button wire:click="loadServices">Refresh</flux:button>
            @else
                <flux:button disabled>Create service</flux:button>
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
            <flux:table.column>Image</flux:table.column>
            <flux:table.column>Replicas</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($services as $s)
                @php $spec = $s['Spec'] ?? []; $meta = $s['Meta'] ?? []; @endphp
                <flux:table.row :key="$s['ID']">
                    <flux:table.cell class="font-mono text-xs">{{ substr($s['ID'] ?? '', 0, 12) }}</flux:table.cell>
                    <flux:table.cell>{{ $spec['Name'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ $spec['TaskTemplate']['ContainerSpec']['Image'] ?? '—' }}</flux:table.cell>
                    <flux:table.cell>{{ ($s['UpdateStatus'] ?? []) ? ($s['Spec']['Mode']['Replicated']['Replicas'] ?? '—') : '—' }}</flux:table.cell>
                    <flux:table.cell>
                        @if($swarmInitialized)
                            <flux:button size="sm" variant="outline" wire:click="remove('{{ $s['ID'] }}')" wire:confirm="Remove this service?">Remove</flux:button>
                        @else
                            <flux:button size="sm" variant="outline" disabled>Remove</flux:button>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($services))
        <flux:callout variant="info" class="mt-6">No services found. Swarm may not be initialized.</flux:callout>
    @endif

    @if($showCreateModal)
        <flux:modal name="create-service" wire:model="showCreateModal" class="md:w-[40rem]">
            <flux:heading size="lg" class="mb-4">Create service</flux:heading>
            <flux:field class="mb-4">
                <flux:label>Name</flux:label>
                <flux:input wire:model="createName" placeholder="my-service" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Image</flux:label>
                <flux:input wire:model="createImage" placeholder="nginx:alpine" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Command (space-separated, optional)</flux:label>
                <flux:input wire:model="createCommand" placeholder="nginx -g daemon off;" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Args (one per line, optional)</flux:label>
                <flux:textarea wire:model="createArgs" placeholder="arg1" rows="2" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Env (one per line KEY=value, optional)</flux:label>
                <flux:textarea wire:model="createEnv" placeholder="NODE_ENV=production" rows="2" />
            </flux:field>
            <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Mode</flux:subheading>
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <flux:checkbox wire:model="createModeGlobal" label="Global (one task per node)" />
                @if(!$createModeGlobal)
                    <flux:field class="w-24">
                        <flux:label>Replicas</flux:label>
                        <flux:input type="number" wire:model="createReplicas" min="0" />
                    </flux:field>
                @endif
            </div>
            <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Publish port (optional)</flux:subheading>
            <div class="flex flex-wrap gap-4 mb-4">
                <flux:field class="w-24">
                    <flux:label>Target (container)</flux:label>
                    <flux:input wire:model="createTargetPort" placeholder="80" />
                </flux:field>
                <flux:field class="w-24">
                    <flux:label>Published (host)</flux:label>
                    <flux:input wire:model="createPublishPort" placeholder="8080" />
                </flux:field>
                <flux:field class="w-28">
                    <flux:label>Protocol</flux:label>
                    <flux:select wire:model="createProtocol">
                        <option value="tcp">tcp</option>
                        <option value="udp">udp</option>
                    </flux:select>
                </flux:field>
                <flux:field class="w-28">
                    <flux:label>Publish mode</flux:label>
                    <flux:select wire:model="createPublishMode">
                        <option value="ingress">ingress</option>
                        <option value="host">host</option>
                    </flux:select>
                </flux:field>
            </div>
            <flux:field class="mb-4">
                <flux:label>Networks (comma-separated, optional)</flux:label>
                <flux:input wire:model="createNetworks" placeholder="ingress,my-net" />
            </flux:field>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <flux:field>
                    <flux:label>Hostname</flux:label>
                    <flux:input wire:model="createHostname" placeholder="optional" />
                </flux:field>
                <flux:field>
                    <flux:label>User</flux:label>
                    <flux:input wire:model="createUser" placeholder="optional" />
                </flux:field>
                <flux:field>
                    <flux:label>Work dir</flux:label>
                    <flux:input wire:model="createWorkdir" placeholder="optional" />
                </flux:field>
            </div>
            <flux:field class="mb-4">
                <flux:label>Labels (one per line: key=value)</flux:label>
                <flux:textarea wire:model="createLabels" placeholder="com.example.service=my-svc" rows="2" />
            </flux:field>
            <div class="flex gap-2">
                <flux:button wire:click="createService">Create</flux:button>
                <flux:button variant="outline" wire:click="closeCreateModal">Cancel</flux:button>
            </div>
        </flux:modal>
    @endif
</div>
