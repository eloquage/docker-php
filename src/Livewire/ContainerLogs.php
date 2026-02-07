<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Livewire\Component;

class ContainerLogs extends Component
{
    public string $id;

    public string $stdout = '';

    public string $stderr = '';

    public string $tail = '100';

    public string $activeTab = 'stdout';

    public bool $wordWrap = true;

    public bool $autoRefresh = false;

    public string $search = '';

    public bool $showTimestamps = false;

    public ?string $error = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(string $id): void
    {
        $this->id = $id;
        $this->loadLogs();
    }

    public function loadLogs(): void
    {
        $this->error = null;
        try {
            $decoded = $this->docker->containerLogsDecoded(
                $this->id,
                true,
                true,
                null,
                null,
                $this->showTimestamps,
                $this->tail === 'all' ? null : $this->tail,
            );
            $this->stdout = $decoded['stdout'] ?? '';
            $this->stderr = $decoded['stderr'] ?? '';
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function updatedTail(): void
    {
        $this->loadLogs();
    }

    public function updatedShowTimestamps(): void
    {
        $this->loadLogs();
    }

    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = ! $this->autoRefresh;
    }

    public function poll(): void
    {
        if ($this->autoRefresh) {
            $this->loadLogs();
        }
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }

    /**
     * @return array{lines: string[], lineCount: int, matchCount: int}
     */
    public function getFilteredLines(string $content): array
    {
        if ($content === '') {
            return ['lines' => [], 'lineCount' => 0, 'matchCount' => 0];
        }

        $lines = explode("\n", $content);
        $lineCount = count($lines);
        $matchCount = 0;

        if ($this->search !== '') {
            $term = mb_strtolower($this->search);
            $filtered = [];
            foreach ($lines as $line) {
                if (str_contains(mb_strtolower($line), $term)) {
                    $filtered[] = $line;
                    $matchCount++;
                }
            }
            $lines = $filtered;
        }

        return ['lines' => $lines, 'lineCount' => $lineCount, 'matchCount' => $matchCount];
    }

    public function render()
    {
        return view('docker-php::livewire.container-logs');
    }
}
