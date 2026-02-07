<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type ImageHistoryArray array{Id?: string, Created?: int, CreatedBy?: string, Tags?: array<int, string>, Size?: int, Comment?: string}
 */
final readonly class ImageHistoryItem
{
    public function __construct(
        public string $id,
        public int $created,
        public string $createdBy,
        public array $tags,
        public int $size,
        public string $comment,
    ) {}

    /**
     * @param  ImageHistoryArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['Id'] ?? '',
            created: (int) ($data['Created'] ?? 0),
            createdBy: $data['CreatedBy'] ?? '',
            tags: $data['Tags'] ?? [],
            size: (int) ($data['Size'] ?? 0),
            comment: $data['Comment'] ?? '',
        );
    }
}
