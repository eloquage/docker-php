<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Livewire;

use Eloquage\DockerPhp\DockerPhp;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class ImageList extends Component
{
    public array $images = [];

    public string $search = '';

    public ?string $error = null;

    public ?string $message = null;

    public bool $showPullModal = false;

    public string $pullImage = '';

    public string $pullTag = 'latest';

    public string $pullPlatform = '';

    public bool $showSearchModal = false;

    public string $searchHubTerm = '';

    public array $searchHubResults = [];

    /** @var array<string, array<int, string>> */
    public array $searchHubTags = [];

    public bool $showTagModal = false;

    public string $tagImageId = '';

    public string $tagRepo = '';

    public string $tagTag = 'latest';

    public ?int $pruneDeleted = null;

    public ?int $pruneReclaimed = null;

    protected DockerPhp $docker;

    public function boot(): void
    {
        $this->docker = app(DockerPhp::class);
    }

    public function mount(): void
    {
        $this->loadImages();
    }

    #[On('images-updated')]
    public function onImagesUpdated(): void
    {
        $this->loadImages();
    }

    public function loadImages(): void
    {
        $this->error = null;
        try {
            $response = $this->docker->images()->list(null, true, null);
            $body = $response->successful() ? $response->json() : [];
            $this->images = is_array($body) && array_is_list($body) ? $body : [];
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getFilteredImages(): array
    {
        if ($this->search === '') {
            return $this->images;
        }
        $term = strtolower($this->search);

        return array_values(array_filter($this->images, function (array $img) use ($term) {
            $repotags = $img['RepoTags'] ?? [];
            $id = $img['Id'] ?? '';
            foreach ($repotags as $tag) {
                if (str_contains(strtolower($tag), $term)) {
                    return true;
                }
            }

            return str_contains(strtolower($id), $term);
        }));
    }

    public function openPullModal(): void
    {
        $this->showSearchModal = false;
        $this->showTagModal = false;
        $this->showPullModal = true;
        $this->pullImage = '';
        $this->pullTag = 'latest';
        $this->pullPlatform = '';
    }

    public function closePullModal(): void
    {
        $this->showPullModal = false;
    }

    public function openSearchModal(): void
    {
        $this->showPullModal = false;
        $this->showTagModal = false;
        $this->showSearchModal = true;
        $this->searchHubTerm = '';
        $this->searchHubResults = [];
    }

    public function closeSearchModal(): void
    {
        $this->showSearchModal = false;
    }

    public function updatedSearchHubTerm(): void
    {
        if (strlen($this->searchHubTerm) < 3) {
            $this->searchHubResults = [];

            return;
        }
        $this->searchDockerHub();
    }

    public function searchDockerHub(): void
    {
        $this->error = null;
        if ($this->searchHubTerm === '') {
            return;
        }
        try {
            $response = $this->docker->images()->search($this->searchHubTerm, 25, null);
            $body = $response->successful() ? $response->json() : [];
            $this->searchHubResults = is_array($body) && array_is_list($body) ? $body : [];
            $this->fetchAllHubTags();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Fetch tags for all search results in parallel using Http::pool().
     */
    protected function fetchAllHubTags(): void
    {
        $this->searchHubTags = [];

        $names = array_filter(array_map(
            fn (array $r): string => $r['name'] ?? '',
            $this->searchHubResults
        ));

        if (empty($names)) {
            return;
        }

        $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($names): array {
            $requests = [];
            foreach ($names as $name) {
                $namespace = str_contains($name, '/') ? $name : "library/{$name}";
                $requests[$name] = $pool->as($name)->timeout(5)->get(
                    "https://hub.docker.com/v2/repositories/{$namespace}/tags/",
                    ['page_size' => 50, 'ordering' => 'last_updated']
                );
            }

            return $requests;
        });

        foreach ($names as $name) {
            try {
                $res = $responses[$name] ?? null;
                if ($res && $res->successful()) {
                    $results = $res->json('results') ?? [];
                    $this->searchHubTags[$name] = array_map(fn (array $tag): string => $tag['name'], $results);
                } else {
                    $this->searchHubTags[$name] = ['latest'];
                }
            } catch (\Throwable) {
                $this->searchHubTags[$name] = ['latest'];
            }
        }
    }

    public function fillPullFromSearch(string $name, string $tag = 'latest'): void
    {
        $parts = explode(':', $name, 2);
        $this->pullImage = $parts[0];
        $this->pullTag = $parts[1] ?? $tag;
        $this->closeSearchModal();
        $this->showPullModal = true;
    }

    public function openTagModal(string $imageId): void
    {
        $this->showPullModal = false;
        $this->showSearchModal = false;
        $this->showTagModal = true;
        $this->tagImageId = $imageId;
        $this->tagRepo = '';
        $this->tagTag = 'latest';
    }

    public function closeTagModal(): void
    {
        $this->showTagModal = false;
    }

    public function tagImage(): void
    {
        if ($this->tagRepo === '') {
            $this->error = 'Repository name is required for tag.';

            return;
        }
        try {
            $this->docker->images()->tag($this->tagImageId, $this->tagRepo, $this->tagTag);
            $this->message = 'Image tagged.';
            $this->closeTagModal();
            $this->loadImages();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function remove(string $name): void
    {
        try {
            $this->docker->images()->delete($name, true);
            $this->message = 'Image removed.';
            $this->loadImages();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function prune(): void
    {
        $this->pruneDeleted = null;
        $this->pruneReclaimed = null;
        try {
            $response = $this->docker->images()->prune(null);
            $data = $response->successful() ? $response->json() : [];
            $this->pruneDeleted = isset($data['ImagesDeleted']) ? count($data['ImagesDeleted']) : 0;
            $this->pruneReclaimed = $data['SpaceReclaimed'] ?? 0;
            $this->message = $this->pruneDeleted > 0 || $this->pruneReclaimed > 0
                ? "Pruned: {$this->pruneDeleted} image(s) deleted, ".number_format($this->pruneReclaimed / 1024 / 1024, 2).' MB reclaimed.'
                : 'Unused images pruned (nothing to remove).';
            $this->loadImages();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('docker-php::livewire.image-list');
    }
}
