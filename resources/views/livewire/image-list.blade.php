<div wire:poll.1s="loadImages" x-data="imageListPull()">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <flux:heading size="xl">Images</flux:heading>
        <div class="flex gap-2">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." />
            <flux:button wire:click="openSearchModal" variant="outline">Search Hub</flux:button>
            <flux:button wire:click="openPullModal">Pull image</flux:button>
            <flux:button variant="outline" wire:click="prune" wire:confirm="Prune unused images?">Prune</flux:button>
            <flux:button wire:click="loadImages">Refresh</flux:button>
        </div>
    </div>

    @if($error)
        @include('docker-php::partials.notification-callout', ['type' => 'danger', 'message' => $error])
    @endif
    @if($message)
        <div x-data="{ visible: true }" x-show="visible" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mb-6">
            <flux:callout variant="success" icon="check-circle" heading="{{ __('Success') }}">
                <flux:callout.text>
                    {{ $message }}
                    @if($pruneDeleted !== null || $pruneReclaimed !== null)
                        <span class="block mt-1 text-sm opacity-90">
                            @if($pruneDeleted !== null) {{ $pruneDeleted }} image(s) deleted. @endif
                            @if($pruneReclaimed !== null && $pruneReclaimed > 0) {{ number_format($pruneReclaimed / 1024 / 1024, 2) }} MB reclaimed. @endif
                        </span>
                    @endif
                </flux:callout.text>
                <x-slot name="controls">
                    <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" aria-label="{{ __('Dismiss') }}" />
                </x-slot>
            </flux:callout>
        </div>
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Tags</flux:table.column>
            <flux:table.column>Architecture / OS</flux:table.column>
            <flux:table.column>Size</flux:table.column>
            <flux:table.column>Created</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->getFilteredImages() as $img)
                @php
                    $firstTag = !empty($img['RepoTags']) ? $img['RepoTags'][0] : $img['Id'] ?? '';
                    $inspectUrl = $firstTag ? route('docker-php.images.show', ['name' => $firstTag]) : '#';
                @endphp
                <flux:table.row :key="$img['Id']">
                    <flux:table.cell class="font-mono text-xs">{{ substr($img['Id'] ?? '', 7, 12) }}</flux:table.cell>
                    <flux:table.cell>
                        @if($inspectUrl !== '#')
                            <a href="{{ $inspectUrl }}" class="text-zinc-900 dark:text-white hover:underline">{{ implode(', ', $img['RepoTags'] ?? ['<none>']) }}</a>
                        @else
                            {{ implode(', ', $img['RepoTags'] ?? ['<none>']) }}
                        @endif
                    </flux:table.cell>
                    <flux:table.cell class="text-xs">{{ ($img['Architecture'] ?? '—') . ' / ' . ($img['Os'] ?? '—') }}</flux:table.cell>
                    <flux:table.cell>{{ isset($img['Size']) ? number_format($img['Size'] / 1024 / 1024) . ' MB' : '—' }}</flux:table.cell>
                    <flux:table.cell>{{ isset($img['Created']) ? date('Y-m-d H:i', $img['Created']) : '—' }}</flux:table.cell>
                    <flux:table.cell class="flex flex-wrap gap-1">
                        <flux:button size="sm" variant="outline" wire:click="openTagModal('{{ $img['Id'] }}')">Tag</flux:button>
                        <flux:button size="sm" variant="outline" wire:click="remove('{{ $img['Id'] }}')" wire:confirm="Remove this image?">Remove</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @if(empty($this->getFilteredImages()))
        <flux:callout variant="info" class="mt-6">No images found.</flux:callout>
    @endif

    <flux:modal name="pull-image" wire:model="showPullModal" class="md:w-176 max-w-[95vw]">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex size-10 items-center justify-center rounded-lg bg-sky-100 dark:bg-sky-900/40">
                    <flux:icon name="arrow-down-tray" class="size-5 text-sky-600 dark:text-sky-400" />
                </div>
                <flux:heading size="lg">Pull image</flux:heading>
            </div>
            <div x-show="!pullInProgress" class="space-y-4">
                <flux:field>
                    <flux:label>Image name</flux:label>
                    <flux:input wire:model.live.debounce.300ms="pullImage" placeholder="e.g. nginx or nginx:alpine" />
                </flux:field>
                <flux:field>
                    <flux:label>Tag</flux:label>
                    <flux:input wire:model="pullTag" placeholder="latest" />
                </flux:field>
                <flux:field>
                    <flux:label>Platform (optional)</flux:label>
                    <flux:input wire:model="pullPlatform" placeholder="e.g. linux/amd64" />
                </flux:field>
                <div class="flex gap-2 pt-2">
                    <flux:button variant="primary" @click="startPull()">Pull</flux:button>
                    <flux:button variant="outline" wire:click="closePullModal">Cancel</flux:button>
                </div>
            </div>
            <div x-show="pullInProgress" x-cloak class="space-y-5">
                {{-- Progress percentage --}}
                <div class="flex items-baseline justify-between">
                    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300" x-text="pullStatus || 'Pulling...'"></p>
                    <span class="tabular-nums text-sm font-semibold text-sky-600 dark:text-sky-400" x-text="pullProgress + '%'"></span>
                </div>
                {{-- Progress bar --}}
                <div class="relative h-4 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700/60">
                    <div
                        class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-sky-500 via-indigo-500 to-sky-500 bg-[length:200%_100%] transition-all duration-500 ease-out"
                        :class="pullProgress > 0 && pullProgress < 100 ? 'animate-[shimmer_2s_linear_infinite]' : ''"
                        :style="'width: ' + pullProgress + '%'"
                    ></div>
                    <div class="absolute inset-0 rounded-full ring-1 ring-inset ring-black/5 dark:ring-white/5"></div>
                </div>
                {{-- Log output --}}
                <div class="max-h-48 overflow-auto rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/60 p-3 text-xs font-mono leading-relaxed text-zinc-600 dark:text-zinc-400 space-y-0.5" x-ref="pullLog"></div>
                <div class="flex justify-end">
                    <flux:button variant="outline" size="sm" @click="cancelPull()" x-show="pullInProgress">Cancel</flux:button>
                </div>
            </div>
    </flux:modal>

    <style>
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>

    <flux:modal name="search-hub" wire:model="showSearchModal" class="md:w-208 max-w-[95vw]">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex size-12 items-center justify-center rounded-xl bg-sky-100 dark:bg-sky-900/40">
                        <flux:icon name="magnifying-glass" class="size-6 text-sky-600 dark:text-sky-400" />
                    </div>
                    <div>
                        <flux:heading size="xl">Search Docker Hub</flux:heading>
                        <flux:text class="mt-0.5 text-zinc-500 dark:text-zinc-400">Find and pull images from the Docker Hub registry</flux:text>
                    </div>
                </div>
            </div>
            <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/50 p-4">
                <flux:input
                    wire:model.live.debounce.350ms="searchHubTerm"
                    placeholder="e.g. nginx, postgres, redis... (min 3 characters)"
                    class="text-base"
                    icon="magnifying-glass"
                    :loading="false"
                />
            </div>
            <div wire:loading wire:target="searchHubTerm" class="flex items-center justify-center gap-2 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                <flux:icon.loading class="size-4" />
                {{ __('Searching Docker Hub...') }}
            </div>
            <div wire:loading.remove wire:target="searchHubTerm" class="max-h-112 overflow-auto space-y-3 pr-1 -mr-1">
                @forelse($searchHubResults as $result)
                    @php $imageName = $result['name'] ?? ''; $imageTags = $searchHubTags[$imageName] ?? ['latest']; @endphp
                    <div
                        x-data="{ tag: '{{ $imageTags[0] ?? 'latest' }}' }"
                        class="group rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 transition hover:border-sky-300 dark:hover:border-sky-600 hover:shadow-md dark:hover:shadow-sky-950/20"
                    >
                        <div class="flex items-start gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $imageName }}</span>
                                    @if(!empty($result['is_official']))
                                        <flux:badge size="sm" color="sky">official</flux:badge>
                                    @endif
                                </div>
                                @if(!empty($result['description']))
                                    <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $result['description'] }}</p>
                                @endif
                                <div class="mt-2 flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400">
                                    <flux:icon name="star" class="size-3.5 text-amber-500 dark:text-amber-400" variant="solid" />
                                    <span>{{ number_format($result['star_count'] ?? 0) }} stars</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-3 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                            <div class="relative flex-1 min-w-0 max-w-56">
                                <select
                                    x-model="tag"
                                    class="block w-full appearance-none rounded-lg border border-zinc-200 bg-white py-2 pr-8 pl-3 text-sm text-zinc-900 shadow-xs transition focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 focus:outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                                >
                                    @foreach($imageTags as $t)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5">
                                    <svg class="size-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            <button
                                x-on:click="$wire.fillPullFromSearch({{ json_encode($imageName) }}, tag)"
                                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-gradient-to-b from-sky-500 to-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-sky-600 transition hover:from-sky-400 hover:to-sky-500 active:from-sky-600 active:to-sky-700 dark:ring-sky-500/50 dark:shadow-sky-900/30"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4"><path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" /><path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" /></svg>
                                Pull
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/30 py-12 text-center">
                        <flux:icon name="photo" class="size-12 text-zinc-300 dark:text-zinc-600 mb-3" />
                        <flux:text class="text-zinc-500 dark:text-zinc-400">
                            {{ strlen($searchHubTerm) < 3 ? __('Type at least 3 characters to search Docker Hub.') : __('No images found. Try a different search term.') }}
                        </flux:text>
                    </div>
                @endforelse
            </div>
            <div class="mt-6 flex justify-end border-t border-zinc-200 dark:border-zinc-700 pt-4">
                <flux:button variant="outline" wire:click="closeSearchModal">Close</flux:button>
            </div>
    </flux:modal>

    <flux:modal name="tag-image" wire:model="showTagModal">
            <flux:heading size="lg" class="mb-4">Tag image</flux:heading>
            <flux:field class="mb-4">
                <flux:label>New repository</flux:label>
                <flux:input wire:model="tagRepo" placeholder="e.g. myrepo/myimage" />
            </flux:field>
            <flux:field class="mb-4">
                <flux:label>Tag</flux:label>
                <flux:input wire:model="tagTag" placeholder="latest" />
            </flux:field>
            <div class="flex gap-2">
                <flux:button wire:click="tagImage">Tag</flux:button>
                <flux:button variant="outline" wire:click="closeTagModal">Cancel</flux:button>
            </div>
    </flux:modal>

    <script>
        function imageListPull() {
            return {
                pullInProgress: false,
                pullProgress: 0,
                pullStatus: '',
                pullLogs: [],
                pullAbort: null,
                async startPull() {
                    const fromImage = this.$wire.get('pullImage');
                    const tag = this.$wire.get('pullTag') || 'latest';
                    const platform = this.$wire.get('pullPlatform') || '';
                    if (!fromImage) return;
                    this.pullInProgress = true;
                    this.pullProgress = 0;
                    this.pullStatus = 'Connecting...';
                    this.pullLogs = [];
                    this.pullAbort = new AbortController();
                    const url = '{{ route("docker-php.images.pull-stream") }}';
                    const body = JSON.stringify({ fromImage, tag, platform: platform || null });
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'text/event-stream',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body,
                            signal: this.pullAbort.signal
                        });
                        if (!res.ok) {
                            this.pullStatus = 'Error: ' + res.status;
                            this.pullInProgress = false;
                            return;
                        }
                        const reader = res.body.getReader();
                        const decoder = new TextDecoder();
                        let buffer = '';
                        while (true) {
                            const { done, value } = await reader.read();
                            if (done) break;
                            buffer += decoder.decode(value, { stream: true });
                            const lines = buffer.split('\n');
                            buffer = lines.pop() || '';
                            for (const line of lines) {
                                if (line.startsWith('data: ')) {
                                    const data = line.slice(6);
                                    this.pullLogs.push(data);
                                    try {
                                        const obj = JSON.parse(data);
                                        if (obj.error) {
                                            this.pullStatus = obj.error;
                                            this.pullInProgress = false;
                                            this.$wire.loadImages();
                                            return;
                                        }
                                        if (obj.status) this.pullStatus = obj.status + (obj.id ? ' (' + obj.id + ')' : '');
                                        if (obj.progressDetail && typeof obj.progressDetail.total === 'number' && obj.progressDetail.total > 0) {
                                            this.pullProgress = Math.round(100 * obj.progressDetail.current / obj.progressDetail.total);
                                        }
                                        if (obj.status === 'complete' || (obj.status && obj.status.toLowerCase().includes('complete'))) {
                                            this.pullProgress = 100;
                                        }
                                        if (obj.status === 'complete') {
                                            this.pullInProgress = false;
                                            this.$wire.closePullModal();
                                            this.$wire.loadImages();
                                            if (window.Livewire) window.Livewire.dispatch('images-updated');
                                            return;
                                        }
                                    } catch (_) {}
                                }
                            }
                        }
                        this.pullProgress = 100;
                        this.pullStatus = 'Complete';
                        this.pullInProgress = false;
                        this.$wire.closePullModal();
                        this.$wire.loadImages();
                        if (window.Livewire) window.Livewire.dispatch('images-updated');
                    } catch (e) {
                        if (e.name === 'AbortError') this.pullStatus = 'Cancelled';
                        else this.pullStatus = 'Error: ' + (e.message || 'Unknown');
                        this.pullInProgress = false;
                    }
                },
                cancelPull() {
                    if (this.pullAbort) this.pullAbort.abort();
                }
            };
        }
    </script>
</div>
