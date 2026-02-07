<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type ImageSummaryArray array{Id?: string, ParentId?: string, RepoTags?: array<int, string>, RepoDigests?: array<int, string>, Created?: int, Size?: int, SharedSize?: int, Labels?: array<string, string>, Containers?: int, VirtualSize?: int, SharedSize?: int, Containers?: int}
 */
final readonly class ImageSummary
{
    public function __construct(
        public string $id,
        public array $repoTags,
        public int $created,
        public int $size,
        public array $labels,
        public int $containers,
        public ?string $parentId = null,
        public array $repoDigests = [],
        public ?int $sharedSize = null,
        public ?string $architecture = null,
        public ?string $os = null,
    ) {}

    /**
     * @param  ImageSummaryArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['Id'] ?? '',
            repoTags: $data['RepoTags'] ?? [],
            created: (int) ($data['Created'] ?? 0),
            size: (int) ($data['Size'] ?? 0),
            labels: $data['Labels'] ?? [],
            containers: (int) ($data['Containers'] ?? 0),
            parentId: isset($data['ParentId']) ? (string) $data['ParentId'] : null,
            repoDigests: $data['RepoDigests'] ?? [],
            sharedSize: isset($data['SharedSize']) ? (int) $data['SharedSize'] : null,
            architecture: isset($data['Architecture']) ? (string) $data['Architecture'] : null,
            os: isset($data['Os']) ? (string) $data['Os'] : null,
        );
    }
}
