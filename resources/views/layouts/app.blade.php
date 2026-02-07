<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Docker – {{ config('app.name') }}</title>
    @fluxAppearance
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        };
    </script>
    @livewireStyles
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 font-sans antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 shrink-0 border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex flex-col">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 bg-gradient-to-br from-sky-500/10 to-blue-600/5 dark:from-sky-500/15 dark:to-blue-600/10">
                <a href="{{ route('docker-php.dashboard') }}" class="flex items-center gap-2 text-lg font-semibold text-zinc-900 dark:text-white">
                    <flux:icon name="cube" class="size-7 text-sky-600 dark:text-sky-400" />
                    Docker
                </a>
            </div>
            <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider px-3 py-2">Overview</div>
                @php
                    $nav = function (string $route, string $label, string $icon) {
                        $active = request()->routeIs($route);
                        $classes = $active
                            ? 'flex items-center gap-2 px-3 py-2 rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-700 dark:text-sky-300 font-medium'
                            : 'flex items-center gap-2 px-3 py-2 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800';
                        return compact('route', 'label', 'icon', 'classes');
                    };
                @endphp
                @php $item = $nav('docker-php.dashboard', 'Dashboard', 'squares-2x2'); @endphp
                <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider px-3 py-2 mt-3">Resources</div>
                @php $item = $nav('docker-php.containers.*', 'Containers', 'cube'); @endphp
                <a href="{{ route('docker-php.containers.index') }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
                @php $item = $nav('docker-php.images.index', 'Images', 'photo'); @endphp
                <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
                @php $item = $nav('docker-php.volumes.index', 'Volumes', 'circle-stack'); @endphp
                <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
                @php $item = $nav('docker-php.networks.index', 'Networks', 'globe-alt'); @endphp
                <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider px-3 py-2 mt-3">Swarm</div>
                @php $item = $nav('docker-php.swarm.index', 'Swarm', 'server-stack'); @endphp
                <a href="{{ route('docker-php.swarm.index') }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    Swarm
                </a>
                @php
                    $swarmNav = function (string $route, string $label, string $icon) use ($nav, $swarmInitialized) {
                        $item = $nav($route, $label, $icon);
                        $disabled = !($swarmInitialized ?? false);
                        $item['disabled'] = $disabled;
                        $item['disabledClasses'] = 'flex items-center gap-2 px-3 py-2 rounded-lg text-zinc-400 dark:text-zinc-500 cursor-not-allowed opacity-60';
                        return $item;
                    };
                @endphp
                @php $item = $swarmNav('docker-php.services.index', 'Services', 'rectangle-group'); @endphp
                @if($item['disabled'])
                    <span class="{{ $item['disabledClasses'] }}" title="Initialize Docker Swarm to use this page">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endif
                @php $item = $swarmNav('docker-php.nodes.index', 'Nodes', 'computer-desktop'); @endphp
                @if($item['disabled'])
                    <span class="{{ $item['disabledClasses'] }}" title="Initialize Docker Swarm to use this page">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endif
                @php $item = $swarmNav('docker-php.tasks.index', 'Tasks', 'list-bullet'); @endphp
                @if($item['disabled'])
                    <span class="{{ $item['disabledClasses'] }}" title="Initialize Docker Swarm to use this page">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endif
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider px-3 py-2 mt-3">Security</div>
                @php $item = $swarmNav('docker-php.secrets.index', 'Secrets', 'key'); @endphp
                @if($item['disabled'])
                    <span class="{{ $item['disabledClasses'] }}" title="Initialize Docker Swarm to use this page">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endif
                @php $item = $swarmNav('docker-php.configs.index', 'Configs', 'document-text'); @endphp
                @if($item['disabled'])
                    <span class="{{ $item['disabledClasses'] }}" title="Initialize Docker Swarm to use this page">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                @else
                    <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                        <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endif
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider px-3 py-2 mt-3">Extensions</div>
                @php $item = $nav('docker-php.plugins.index', 'Plugins', 'puzzle-piece'); @endphp
                <a href="{{ route($item['route']) }}" class="{{ $item['classes'] }}">
                    <flux:icon name="{{ $item['icon'] }}" class="size-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
            </nav>
        </aside>
        <main class="flex-1 p-6 lg:p-8 overflow-auto">
            @if(session('message') || session('error'))
                <div class="space-y-4 mb-6" aria-label="{{ __('Notifications') }}">
                    @if(session('message'))
                        @include('docker-php::partials.notification-callout', [
                            'type' => 'success',
                            'message' => session('message'),
                            'heading' => __('Success'),
                        ])
                    @endif
                    @if(session('error'))
                        @include('docker-php::partials.notification-callout', [
                            'type' => 'danger',
                            'message' => session('error'),
                            'heading' => __('Error'),
                        ])
                    @endif
                </div>
            @endif
            @if(isset($livewireComponent))
                @livewire($livewireComponent, $livewireParams ?? [])
            @endif
        </main>
    </div>
    @fluxScripts
    @livewireScripts
</body>
</html>
