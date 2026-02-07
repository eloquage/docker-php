<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * System disk usage response.
 *
 * @phpstan-type DiskUsageArray array{LayersSize?: int, Images?: array<int, array<string, mixed>>, Containers?: array<int, array<string, mixed>>, Volumes?: array<int, array<string, mixed>>}
 */
final readonly class DiskUsage
{
    public function __construct(
        public array $data,
    ) {}

    /**
     * @param  DiskUsageArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function layersSize(): int
    {
        return (int) ($this->data['LayersSize'] ?? 0);
    }

    public function images(): array
    {
        return $this->data['Images'] ?? [];
    }

    public function containers(): array
    {
        return $this->data['Containers'] ?? [];
    }

    public function volumes(): array
    {
        return $this->data['Volumes'] ?? [];
    }
}
