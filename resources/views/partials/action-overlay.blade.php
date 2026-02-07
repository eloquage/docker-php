{{-- Global overlay shown during Livewire actions (create, remove, prune, etc.). Blocks main area. After 10s shows progress bar. --}}
<div
    x-data="actionOverlay()"
    x-on:docker-action-loading-start.window="startLoading()"
    x-on:docker-action-loading-stop.window="stopLoading()"
    x-show="visible"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto"
    style="left: 16rem;"
    aria-live="polite"
    x-bind:aria-busy="visible"
    role="status"
>
    <div class="absolute inset-0 bg-zinc-900/50 dark:bg-zinc-950/70 backdrop-blur-sm"></div>
    <div class="relative z-10 flex flex-col items-center gap-6 rounded-xl bg-white dark:bg-zinc-900 px-10 py-8 shadow-xl border border-zinc-200 dark:border-zinc-800 min-w-[18rem]">
        <flux:icon.loading class="size-12 text-sky-500 dark:text-sky-400" />
        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300" x-text="showProgress ? '{{ __('This operation is taking longer than expected...') }}' : '{{ __('Processing...') }}'"></p>
        <div x-show="showProgress" x-transition class="w-64">
            <div class="h-1.5 w-full bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                <div class="action-overlay-progress-bar h-full bg-sky-500 dark:bg-sky-400 rounded-full"></div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes action-overlay-indeterminate {
        0% { transform: translateX(-100%); }
        50% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .action-overlay-progress-bar {
        width: 40%;
        animation: action-overlay-indeterminate 1.5s ease-in-out infinite;
    }
</style>

<script>
document.addEventListener('livewire:init', function () {
    const SKIP_METHODS = [
        'loadData', 'loadContainers', 'loadImages', 'loadVolumes', 'loadNetworks', 'loadServices',
        'loadNodes', 'loadTasks', 'loadSecrets', 'loadConfigs', 'loadPlugins', 'loadLogs',
        'loadSwarm', 'loadUnlockKey', 'inspectNetwork', 'inspectVolume',
        'openCreateModal', 'closeCreateModal', 'openInspectModal', 'closeInspectModal',
        'openSearchModal', 'closeSearchModal', 'openPullModal', 'closePullModal',
        'openTagModal', 'closeTagModal', 'fillPullFromSearch', 'toggleAutoRefresh', 'clearSearch',
        'searchDockerHub', 'updatedSearchHubTerm'
    ];

    window.Livewire.hook('message', ({ component, message, succeed, fail }) => {
        const calls = message?.calls ?? [];
        const methods = calls.map(c => c.method);
        if (methods.length === 0 || methods.every(m => SKIP_METHODS.includes(m))) return;

        window.dispatchEvent(new CustomEvent('docker-action-loading-start'));
        succeed(() => { window.dispatchEvent(new CustomEvent('docker-action-loading-stop')); });
        fail(() => { window.dispatchEvent(new CustomEvent('docker-action-loading-stop')); });
    });

    window.Alpine.data('actionOverlay', actionOverlay);
});

function actionOverlay() {
    const DELAY_MS = 300;
    const PROGRESS_AFTER_MS = 10000;

    return {
        visible: false,
        loading: false,
        elapsed: 0,
        showProgress: false,
        timer: null,
        delayTimer: null,

        startLoading() {
            this.loading = true;
            this.showProgress = false;
            this.elapsed = 0;
            if (this.timer) clearInterval(this.timer);
            if (this.delayTimer) clearTimeout(this.delayTimer);

            this.delayTimer = setTimeout(() => {
                this.visible = true;
                this.delayTimer = null;
                this.timer = setInterval(() => {
                    this.elapsed += 1000;
                    if (this.elapsed >= PROGRESS_AFTER_MS) {
                        this.showProgress = true;
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                }, 1000);
            }, DELAY_MS);
        },

        stopLoading() {
            this.loading = false;
            if (this.delayTimer) {
                clearTimeout(this.delayTimer);
                this.delayTimer = null;
            }
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
            this.visible = false;
            this.showProgress = false;
            this.elapsed = 0;
        }
    };
}
</script>
