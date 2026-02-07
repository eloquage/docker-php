<div wire:poll.5s="load">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('docker-php.containers.index') }}" class="text-zinc-600 dark:text-zinc-400 hover:underline">← Containers</a>
        <flux:heading size="xl">Container {{ substr($id, 0, 12) }}</flux:heading>
    </div>

    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error])
    @elseif(!empty($inspect))
        <div class="space-y-6">
            <flux:card>
                <flux:heading size="base" class="mb-4">Overview</flux:heading>
                <dl class="grid gap-2 text-sm sm:grid-cols-2">
                    <div><span class="text-zinc-500 dark:text-zinc-400">Name:</span> {{ $inspect['Name'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Image:</span> {{ $inspect['Config']['Image'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">State:</span> {{ $inspect['State']['Status'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Created:</span> {{ isset($inspect['Created']) ? date('Y-m-d H:i:s', strtotime($inspect['Created'])) : '—' }}</div>
                </dl>
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
