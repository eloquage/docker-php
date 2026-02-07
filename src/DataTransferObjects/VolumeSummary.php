<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type VolumeSummaryArray array{Name?: string, Driver?: string, Mountpoint?: string, CreatedAt?: string, Labels?: array<string, string>, Scope?: string, Options?: array<string, string>, UsageData?: array{Size?: int, RefCount?: int}}
 */
final readonly class VolumeSummary
{
    public function __construct(
        public string $name,
        public string $driver,
        public string $mountpoint,
        public array $labels,
        public ?string $createdAt = null,
        public ?string $scope = null,
        public array $options = [],
    ) {}

    /**
     * @param  VolumeSummaryArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['Name'] ?? '',
            driver: $data['Driver'] ?? 'local',
            mountpoint: $data['Mountpoint'] ?? '',
            labels: $data['Labels'] ?? [],
            createdAt: isset($data['CreatedAt']) ? (string) $data['CreatedAt'] : null,
            scope: isset($data['Scope']) ? (string) $data['Scope'] : null,
            options: $data['Options'] ?? [],
        );
    }
}
