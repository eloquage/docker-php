<div wire:poll.5s="loadData">
    <flux:heading size="xl" class="mb-6">Dashboard</flux:heading>

    @if($message)
        @include('docker-php::partials.notification-callout', ['type' => 'success', 'message' => $message])
    @endif
    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error, 'heading' => __('Connection error')])
    @else
        @php
            $containersTotal = (int) ($info['Containers'] ?? 0);
            $containersRunning = (int) ($info['ContainersRunning'] ?? 0);
            $containersPaused = (int) ($info['ContainersPaused'] ?? 0);
            $containersStopped = max(0, $containersTotal - $containersRunning - $containersPaused);
        @endphp

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <flux:card>
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400">Docker version</flux:subheading>
                <flux:heading size="lg">{{ $version['Version'] ?? '—' }}</flux:heading>
            </flux:card>
            <flux:card>
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400">Containers</flux:subheading>
                <flux:heading size="lg" class="flex flex-wrap items-center gap-2">
                    <flux:badge color="green" size="sm">{{ $containersRunning }} running</flux:badge>
                    @if($containersPaused > 0)
                        <flux:badge color="amber" size="sm">{{ $containersPaused }} paused</flux:badge>
                    @endif
                    @if($containersStopped > 0)
                        <flux:badge color="zinc" size="sm">{{ $containersStopped }} stopped</flux:badge>
                    @endif
                    @if($containersTotal === 0)
                        <span>0</span>
                    @endif
                </flux:heading>
            </flux:card>
            <flux:card>
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400">Images</flux:subheading>
                <flux:heading size="lg">{{ $info['Images'] ?? 0 }}</flux:heading>
            </flux:card>
            <flux:card>
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400">Server</flux:subheading>
                <flux:heading size="lg">{{ $info['OperatingSystem'] ?? '—' }}</flux:heading>
            </flux:card>
        </div>

        <flux:heading size="base" class="mb-3">Prune</flux:heading>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5 mb-8">
            <flux:card class="flex flex-col">
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Containers</flux:subheading>
                <flux:button wire:click="pruneContainers" variant="outline" size="sm" class="mt-auto">Prune</flux:button>
            </flux:card>
            <flux:card class="flex flex-col">
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Images</flux:subheading>
                <flux:button wire:click="pruneImages" variant="outline" size="sm" class="mt-auto">Prune</flux:button>
            </flux:card>
            <flux:card class="flex flex-col">
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Volumes</flux:subheading>
                <flux:button wire:click="pruneVolumes" variant="outline" size="sm" class="mt-auto">Prune</flux:button>
            </flux:card>
            <flux:card class="flex flex-col">
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">Networks</flux:subheading>
                <flux:button wire:click="pruneNetworks" variant="outline" size="sm" class="mt-auto">Prune</flux:button>
            </flux:card>
            <flux:card class="flex flex-col">
                <flux:subheading size="sm" class="text-zinc-500 dark:text-zinc-400 mb-2">System</flux:subheading>
                <flux:button wire:click="pruneSystem" variant="danger" size="sm" class="mt-auto">Prune all</flux:button>
            </flux:card>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <flux:card>
                <flux:heading size="base" class="mb-4">System info</flux:heading>
                <dl class="space-y-2 text-sm">
                    <div><span class="text-zinc-500 dark:text-zinc-400">API version:</span> {{ $version['ApiVersion'] ?? $info['ApiVersion'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Go version:</span> {{ $version['GoVersion'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Architecture:</span> {{ $info['Arch'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Storage driver:</span> {{ $info['Driver'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Logging driver:</span> {{ $info['LoggingDriver'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Cgroup driver:</span> {{ $info['CgroupDriver'] ?? $info['CgroupVersion'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Docker Root Dir:</span> {{ $info['DockerRootDir'] ?? '—' }}</div>
                    @if(!empty($info['Runtimes']))
                        <div><span class="text-zinc-500 dark:text-zinc-400">Runtimes:</span> {{ implode(', ', array_keys($info['Runtimes'])) }}</div>
                    @endif
                    <div><span class="text-zinc-500 dark:text-zinc-400">Kernel:</span> {{ $info['KernelVersion'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">CPUs:</span> {{ $info['NCPU'] ?? '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Memory:</span> {{ isset($info['MemTotal']) ? number_format($info['MemTotal'] / 1024 / 1024 / 1024, 2) . ' GB' : '—' }}</div>
                    <div><span class="text-zinc-500 dark:text-zinc-400">Name:</span> {{ $info['Name'] ?? '—' }}</div>
                </dl>
            </flux:card>
            <flux:card>
                <flux:heading size="base" class="mb-4">Disk usage</flux:heading>
                @if(!empty($df['LayersSize']))
                    <div class="text-sm mb-2"><span class="text-zinc-500 dark:text-zinc-400">Layers size:</span> {{ number_format($df['LayersSize'] / 1024 / 1024 / 1024, 2) }} GB</div>
                @endif
                @if(!empty($df['Images']))
                    <div class="text-sm mb-2">
                        <span class="text-zinc-500 dark:text-zinc-400">Images:</span> {{ count($df['Images']) }}
                        @php
                            $imagesSize = array_sum(array_column($df['Images'], 'Size'));
                        @endphp
                        @if($imagesSize > 0)
                            ({{ number_format($imagesSize / 1024 / 1024 / 1024, 2) }} GB)
                        @endif
                    </div>
                @endif
                @if(!empty($df['Containers']))
                    <div class="text-sm mb-2">
                        <span class="text-zinc-500 dark:text-zinc-400">Containers:</span> {{ count(array_filter($df['Containers'] ?? [], fn ($c) => ($c['SizeRw'] ?? 0) > 0 || ($c['SizeRootFs'] ?? 0) > 0)) }}
                        @php
                            $containersSize = array_sum(array_map(fn ($c) => ($c['SizeRw'] ?? 0) + ($c['SizeRootFs'] ?? 0), $df['Containers'] ?? []));
                        @endphp
                        @if($containersSize > 0)
                            ({{ number_format($containersSize / 1024 / 1024, 2) }} MB)
                        @endif
                    </div>
                @endif
                @if(!empty($df['Volumes']))
                    @php
                        $volumesSize = is_array($df['Volumes']) ? array_reduce($df['Volumes'], fn ($sum, $v) => $sum + (isset($v['UsageData']['Size']) ? (int) $v['UsageData']['Size'] : 0), 0) : 0;
                    @endphp
                    <div class="text-sm mb-2">
                        <span class="text-zinc-500 dark:text-zinc-400">Volumes:</span> {{ count($df['Volumes']) }}
                        @if($volumesSize > 0)
                            ({{ number_format($volumesSize / 1024 / 1024 / 1024, 2) }} GB)
                        @endif
                    </div>
                @endif
                @if(empty($df['LayersSize']) && empty($df['Images']) && empty($df['Containers']) && empty($df['Volumes']))
                    <flux:callout variant="info">No disk usage data available.</flux:callout>
                @endif
            </flux:card>
        </div>
    @endif
</div>
