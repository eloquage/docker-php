<div wire:poll.5s="loadImages" x-data="imageListPull()">
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

    @if($showPullModal)
        <flux:modal name="pull-image" wire:model="showPullModal">
            <flux:heading size="lg" class="mb-4">Pull image</flux:heading>
            <div x-show="!pullInProgress" class="space-y-4">
                <flux:field>
                    <flux:label>Image name</flux:label>
                    <flux:input wire:model="pullImage" placeholder="e.g. nginx or nginx:alpine" />
                </flux:field>
                <flux:field>
                    <flux:label>Tag</flux:label>
                    <flux:input wire:model="pullTag" placeholder="latest" />
                </flux:field>
                <flux:field>
                    <flux:label>Platform (optional)</flux:label>
                    <flux:input wire:model="pullPlatform" placeholder="e.g. linux/amd64" />
                </flux:field>
                <div class="flex gap-2">
                    <flux:button @click="startPull()" :disabled="!$wire.pullImage">Pull</flux:button>
                    <flux:button variant="outline" wire:click="closePullModal">Cancel</flux:button>
                </div>
            </div>
            <div x-show="pullInProgress" x-cloak class="space-y-4">
                <div class="h-2 w-full rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                    <div class="h-full bg-sky-500 transition-all duration-300" :style="'width: ' + pullProgress + '%'"></div>
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400" x-text="pullStatus || 'Pulling...'"></p>
                <div class="max-h-40 overflow-auto rounded bg-zinc-100 dark:bg-zinc-800 p-2 text-xs font-mono space-y-0.5" x-ref="pullLog"></div>
                <flux:button variant="outline" size="sm" @click="cancelPull()" x-show="pullInProgress">Cancel</flux:button>
            </div>
        </flux:modal>
    @endif

    @if($showSearchModal)
        <flux:modal name="search-hub" wire:model="showSearchModal">
            <flux:heading size="lg" class="mb-4">Search Docker Hub</flux:heading>
            <div class="flex gap-2 mb-4">
                <flux:input wire:model.live="searchHubTerm" placeholder="Search term..." class="flex-1" />
                <flux:button wire:click="searchDockerHub">Search</flux:button>
            </div>
            <div class="max-h-80 overflow-auto space-y-2">
                @foreach($searchHubResults as $result)
                    <div class="flex items-center justify-between gap-2 rounded-lg border border-zinc-200 dark:border-zinc-700 p-2">
                        <div class="min-w-0">
                            <span class="font-medium">{{ $result['name'] ?? '' }}</span>
                            @if(!empty($result['is_official']))
                                <flux:badge size="sm" color="zinc" class="ml-1">official</flux:badge>
                            @endif
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $result['description'] ?? '' }}</p>
                            <span class="text-xs">★ {{ $result['star_count'] ?? 0 }}</span>
                        </div>
                        <flux:button size="sm" wire:click="fillPullFromSearch({{ json_encode($result['name'] ?? '') }}, 'latest')">Pull</flux:button>
                    </div>
                @endforeach
            </div>
            <flux:button variant="outline" wire:click="closeSearchModal" class="mt-4">Close</flux:button>
        </flux:modal>
    @endif

    @if($showTagModal)
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
    @endif

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
