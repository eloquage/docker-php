<div
    @if($autoRefresh) wire:poll.3s="poll" @endif
    x-data="{
        autoScroll: true,
        scrollToBottom() {
            if (this.autoScroll) {
                this.$nextTick(() => {
                    const el = document.getElementById('log-output');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
        }
    }"
    x-init="scrollToBottom()"
    x-effect="scrollToBottom()"
>
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a href="{{ route('docker-php.containers.show', ['id' => $id]) }}"
           class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors">
            <flux:icon name="arrow-left" class="size-4" />
            Container
        </a>
        <span class="text-zinc-300 dark:text-zinc-600">/</span>
        <div class="flex items-center gap-2">
            <flux:icon name="command-line" class="size-5 text-emerald-500" />
            <flux:heading size="xl">Logs</flux:heading>
            <flux:badge size="sm" color="zinc" class="font-mono">{{ substr($id, 0, 12) }}</flux:badge>
        </div>
    </div>

    @if($error)
        @include('docker-php::partials.notification-callout', [
            'type' => 'danger',
            'message' => $error,
            'heading' => __('Error loading logs'),
        ])
    @else
        {{-- Toolbar --}}
        <div class="mb-4 flex flex-wrap items-end gap-3">
            <flux:field class="w-36">
                <flux:label>Lines</flux:label>
                <flux:select wire:model.live="tail" size="sm">
                    <option value="50">Last 50</option>
                    <option value="100">Last 100</option>
                    <option value="250">Last 250</option>
                    <option value="500">Last 500</option>
                    <option value="1000">Last 1,000</option>
                    <option value="all">All</option>
                </flux:select>
            </flux:field>

            <flux:field class="flex-1 min-w-48 max-w-xs">
                <flux:label>Filter</flux:label>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search in logs..." size="sm" icon="magnifying-glass" />
            </flux:field>

            <div class="flex items-center gap-2 pb-0.5">
                <flux:button wire:click="loadLogs" size="sm" variant="outline" icon="arrow-path" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="loadLogs">Refresh</span>
                    <span wire:loading wire:target="loadLogs">Loading...</span>
                </flux:button>

                <flux:button
                    wire:click="toggleAutoRefresh"
                    size="sm"
                    :variant="$autoRefresh ? 'primary' : 'outline'"
                >
                    @if($autoRefresh)
                        <span class="relative flex size-2 mr-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white/75"></span>
                            <span class="relative inline-flex rounded-full size-2 bg-white"></span>
                        </span>
                    @endif
                    Live
                </flux:button>
            </div>

            <div class="flex items-center gap-3 pb-0.5 ml-auto">
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 cursor-pointer select-none">
                    <input type="checkbox" wire:model.live="wordWrap"
                           class="size-3.5 rounded border-zinc-300 dark:border-zinc-600 text-sky-500 focus:ring-sky-500/30" />
                    Wrap
                </label>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 cursor-pointer select-none">
                    <input type="checkbox" wire:model.live="showTimestamps"
                           class="size-3.5 rounded border-zinc-300 dark:border-zinc-600 text-sky-500 focus:ring-sky-500/30" />
                    Timestamps
                </label>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 cursor-pointer select-none"
                       x-on:click="autoScroll = !autoScroll">
                    <input type="checkbox" x-model="autoScroll"
                           class="size-3.5 rounded border-zinc-300 dark:border-zinc-600 text-sky-500 focus:ring-sky-500/30" />
                    Auto-scroll
                </label>
            </div>
        </div>

        {{-- Tabs --}}
        @php
            $stdoutData = $this->getFilteredLines($stdout);
            $stderrData = $this->getFilteredLines($stderr);
            $hasStdout = $stdout !== '';
            $hasStderr = $stderr !== '';
        @endphp

        <div class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-800 shadow-lg animate-[fadeIn_0.3s_ease-out]">
            {{-- Tab bar --}}
            <div class="flex items-center bg-zinc-100 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
                <button wire:click="$set('activeTab', 'stdout')"
                        class="relative px-4 py-2.5 text-sm font-medium transition-colors flex items-center gap-2
                               {{ $activeTab === 'stdout'
                                   ? 'text-zinc-900 dark:text-white'
                                   : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                    <span class="size-2 rounded-full {{ $hasStdout ? 'bg-emerald-400' : 'bg-zinc-300 dark:bg-zinc-600' }}"></span>
                    stdout
                    @if($hasStdout)
                        <span class="text-[10px] text-zinc-400 dark:text-zinc-500 tabular-nums">{{ $stdoutData['lineCount'] }}</span>
                    @endif
                    @if($activeTab === 'stdout')
                        <span class="absolute bottom-0 inset-x-0 h-0.5 bg-emerald-500"></span>
                    @endif
                </button>
                <button wire:click="$set('activeTab', 'stderr')"
                        class="relative px-4 py-2.5 text-sm font-medium transition-colors flex items-center gap-2
                               {{ $activeTab === 'stderr'
                                   ? 'text-zinc-900 dark:text-white'
                                   : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                    <span class="size-2 rounded-full {{ $hasStderr ? 'bg-red-400' : 'bg-zinc-300 dark:bg-zinc-600' }}"></span>
                    stderr
                    @if($hasStderr)
                        <span class="text-[10px] text-zinc-400 dark:text-zinc-500 tabular-nums">{{ $stderrData['lineCount'] }}</span>
                    @endif
                    @if($activeTab === 'stderr')
                        <span class="absolute bottom-0 inset-x-0 h-0.5 bg-red-500"></span>
                    @endif
                </button>

                {{-- Search match count --}}
                @if($search !== '')
                    @php $currentData = $activeTab === 'stdout' ? $stdoutData : $stderrData; @endphp
                    <div class="ml-auto mr-3 text-xs tabular-nums text-zinc-400 dark:text-zinc-500">
                        {{ $currentData['matchCount'] }} {{ $currentData['matchCount'] === 1 ? 'match' : 'matches' }}
                    </div>
                @endif

                {{-- Loading indicator --}}
                <div wire:loading wire:target="loadLogs, poll" class="ml-auto mr-3">
                    <span class="flex items-center gap-1.5 text-xs text-sky-500">
                        <svg class="animate-spin size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading
                    </span>
                </div>
            </div>

            {{-- Terminal output --}}
            @php $data = $activeTab === 'stdout' ? $stdoutData : $stderrData; @endphp
            @php $isStderr = $activeTab === 'stderr'; @endphp

            <div id="log-output"
                 class="relative overflow-auto scroll-smooth {{ $wordWrap ? '' : 'overflow-x-auto' }}"
                 style="max-height: 70vh; min-height: 16rem;">
                {{-- Background --}}
                <div class="absolute inset-0 {{ $isStderr ? 'bg-linear-to-b from-zinc-950 to-red-950/20' : 'bg-linear-to-b from-zinc-950 to-zinc-900' }}"></div>

                {{-- Scanline overlay (subtle CRT effect) --}}
                <div class="absolute inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.04]"
                     style="background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,0.08) 2px, rgba(255,255,255,0.08) 4px);"></div>

                @if(count($data['lines']) > 0)
                    <table class="w-full relative z-10 border-collapse">
                        <tbody>
                            @foreach($data['lines'] as $idx => $line)
                                <tr class="group hover:bg-white/3 transition-colors duration-75">
                                    {{-- Line number --}}
                                    <td class="sticky left-0 w-[1%] px-2 py-0 text-right select-none align-top
                                               text-[11px] font-mono tabular-nums leading-5
                                               text-zinc-600 border-r border-zinc-800/60
                                               {{ $isStderr ? 'bg-linear-to-r from-zinc-950 to-red-950/20' : 'bg-zinc-950' }}
                                               group-hover:text-zinc-400 transition-colors duration-75">
                                        {{ $idx + 1 }}
                                    </td>
                                    {{-- Log content --}}
                                    <td class="px-3 py-0 align-top">
                                        <pre class="text-[12px] leading-5 font-mono m-0
                                                    {{ $isStderr ? 'text-red-300/90' : 'text-emerald-100/90' }}
                                                    {{ $wordWrap ? 'whitespace-pre-wrap break-all' : 'whitespace-pre' }}">@if($search !== ''){!! preg_replace(
                                                '/(' . preg_quote(e($search), '/') . ')/i',
                                                '<mark class="bg-amber-400/30 text-amber-200 rounded-sm px-0.5">$1</mark>',
                                                e($line)
                                            ) !!}@else{{ $line }}@endif</pre>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    {{-- Empty state --}}
                    <div class="relative z-10 flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="rounded-full bg-zinc-800/60 p-4 mb-4">
                            <flux:icon name="command-line" class="size-8 text-zinc-500" />
                        </div>
                        @if($search !== '')
                            <p class="text-sm text-zinc-400 mb-1">No matching lines</p>
                            <p class="text-xs text-zinc-600">Try a different filter or clear the search.</p>
                            <flux:button wire:click="clearSearch" size="sm" variant="outline" class="mt-3">Clear filter</flux:button>
                        @elseif(!$hasStdout && !$hasStderr)
                            <p class="text-sm text-zinc-400 mb-1">No logs yet</p>
                            <p class="text-xs text-zinc-600">This container hasn't written any log output.</p>
                        @else
                            <p class="text-sm text-zinc-400">No {{ $activeTab }} output</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Footer status bar --}}
            <div class="flex items-center justify-between px-3 py-1.5 text-[11px] font-mono
                        bg-zinc-100 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800
                        text-zinc-500 dark:text-zinc-500 tabular-nums select-none">
                <div class="flex items-center gap-3">
                    <span>{{ count($data['lines']) }} {{ count($data['lines']) === 1 ? 'line' : 'lines' }}</span>
                    @if($search !== '' && $data['matchCount'] < $data['lineCount'])
                        <span class="text-amber-500">filtered from {{ $data['lineCount'] }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @if($autoRefresh)
                        <span class="flex items-center gap-1.5 text-emerald-500">
                            <span class="relative flex size-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full size-1.5 bg-emerald-500"></span>
                            </span>
                            live
                        </span>
                    @endif
                    <span>tail={{ $tail }}</span>
                    <span>{{ $wordWrap ? 'wrap' : 'nowrap' }}</span>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>
