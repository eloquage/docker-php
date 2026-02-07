<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type ImageSearchArray array{name?: string, description?: string, star_count?: int, is_official?: bool, is_automated?: bool}
 */
final readonly class ImageSearchResult
{
    public function __construct(
        public string $name,
        public string $description,
        public int $starCount,
        public bool $isOfficial,
        public bool $isAutomated,
    ) {}

    /**
     * @param  ImageSearchArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            description: $data['description'] ?? '',
            starCount: (int) ($data['star_count'] ?? 0),
            isOfficial: (bool) ($data['is_official'] ?? false),
            isAutomated: (bool) ($data['is_automated'] ?? false),
        );
    }
}
