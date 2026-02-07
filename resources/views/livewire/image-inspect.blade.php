<div wire:poll.5s="load">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('docker-php.images.index') }}" class="text-zinc-600 dark:text-zinc-400 hover:underline">← Images</a>
        <flux:heading size="xl">Image {{ $name }}</flux:heading>
    </div>

    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error])
    @elseif(!empty($inspect))
        <div class="space-y-6">
            <flux:card>
                <flux:heading size="base" class="mb-4">Overview</flux:heading>
                <dl class="grid gap-2 text-sm sm:grid-cols-2">
                    <div><span class="text-zinc-500 dark:text-zinc-400">ID:</span> <span class="font-mono">{{ substr($inspect['Id'] ?? '', 7, 12) }}</span></div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Architecture:</span> {{ $inspect['Architecture'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">OS:</span> {{ $inspect['Os'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Size:</span> {{ isset($inspect['Size']) ? number_format($inspect['Size'] / 1024 / 1024) . ' MB' : '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Created:</span> {{ isset($inspect['Created']) ? date('Y-m-d H:i:s', strtotime($inspect['Created'])) : '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">RepoTags:</span> {{ implode(', ', $inspect['RepoTags'] ?? []) ?: '—' }}</div>
                </dl>
            </flux:card>

            @if(!empty($inspect['Config']))
                <flux:card>
                    <flux:heading size="base" class="mb-4">Config</flux:heading>
                    <dl class="grid gap-2 text-sm sm:grid-cols-2">
                        @if(isset($inspect['Config']['Cmd']) && $inspect['Config']['Cmd'] !== [])
                            <div class="sm:col-span-2"><span class="text-zinc-500 dark:text-zinc-400">Cmd:</span> <code class="text-xs">{{ json_encode($inspect['Config']['Cmd']) }}</code></div>
                        @endif
                        @if(isset($inspect['Config']['Entrypoint']) && $inspect['Config']['Entrypoint'] !== [])
                            <div class="sm:col-span-2"><span class="text-zinc-500 dark:text-zinc-400">Entrypoint:</span> <code class="text-xs">{{ json_encode($inspect['Config']['Entrypoint']) }}</code></div>
                        @endif
                        @if(!empty($inspect['Config']['Env']))
                            <div class="sm:col-span-2"><span class="text-zinc-500 dark:text-zinc-400">Env:</span>
                                <ul class="mt-1 list-inside list-disc text-xs font-mono">
                                    @foreach(array_slice($inspect['Config']['Env'], 0, 20) as $env)
                                        <li>{{ $env }}</li>
                                    @endforeach
                                    @if(count($inspect['Config']['Env']) > 20)
                                        <li class="text-zinc-500">… and {{ count($inspect['Config']['Env']) - 20 }} more</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        @if(!empty($inspect['Config']['ExposedPorts']))
                            <div><span class="text-zinc-500 dark:text-zinc-400">ExposedPorts:</span> {{ implode(', ', array_keys($inspect['Config']['ExposedPorts'])) }}</div>
                        @endif
                        @if(!empty($inspect['Config']['WorkingDir']))
                            <div><span class="text-zinc-500 dark:text-zinc-400">WorkingDir:</span> {{ $inspect['Config']['WorkingDir'] }}</div>
                        @endif
                        @if(!empty($inspect['Config']['User']))
                            <div><span class="text-zinc-500 dark:text-zinc-400">User:</span> {{ $inspect['Config']['User'] }}</div>
                        @endif
                    </dl>
                </flux:card>
            @endif

            @if(!empty($inspect['RootFS']['Layers']))
                <flux:card>
                    <flux:heading size="base" class="mb-4">Layers ({{ count($inspect['RootFS']['Layers']) }})</flux:heading>
                    <ul class="space-y-1 text-xs font-mono max-h-48 overflow-auto">
                        @foreach(array_slice($inspect['RootFS']['Layers'], 0, 15) as $layer)
                            <li class="truncate">{{ $layer }}</li>
                        @endforeach
                        @if(count($inspect['RootFS']['Layers']) > 15)
                            <li class="text-zinc-500">… and {{ count($inspect['RootFS']['Layers']) - 15 }} more</li>
                        @endif
                    </ul>
                </flux:card>
            @endif

            @if(!empty($inspect['Config']['Labels']))
                <flux:card>
                    <flux:heading size="base" class="mb-4">Labels</flux:heading>
                    <dl class="grid gap-2 text-sm sm:grid-cols-2">
                        @foreach($inspect['Config']['Labels'] as $k => $v)
                            <div><span class="text-zinc-500 dark:text-zinc-400">{{ $k }}:</span> {{ $v }}</div>
                        @endforeach
                    </dl>
                </flux:card>
            @endif

            <flux:card>
                <flux:heading size="base" class="mb-4">History</flux:heading>
                @if(!empty($history))
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>ID</flux:table.column>
                            <flux:table.column>Created</flux:table.column>
                            <flux:table.column>Size</flux:table.column>
                            <flux:table.column>CreatedBy</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach($history as $item)
                                <flux:table.row>
                                    <flux:table.cell class="font-mono text-xs">{{ substr($item['Id'] ?? '', 7, 12) }}</flux:table.cell>
                                    <flux:table.cell>{{ isset($item['Created']) ? date('Y-m-d H:i', $item['Created']) : '—' }}</flux:table.cell>
                                    <flux:table.cell>{{ isset($item['Size']) ? number_format($item['Size'] / 1024 / 1024, 2) . ' MB' : '—' }}</flux:table.cell>
                                    <flux:table.cell class="max-w-xs truncate text-xs" title="{{ $item['CreatedBy'] ?? '' }}">{{ $item['CreatedBy'] ?? '—' }}</flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No history.</p>
                @endif
            </flux:card>

            <flux:card>
                <flux:heading size="base" class="mb-4">Full JSON</flux:heading>
                <pre class="text-xs overflow-auto p-4 bg-zinc-100 dark:bg-zinc-800 rounded-lg max-h-[70vh]">{{ json_encode($inspect, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </flux:card>
        </div>
    @else
        <flux:callout variant="info">No inspect data.</flux:callout>
    @endif
</div>
