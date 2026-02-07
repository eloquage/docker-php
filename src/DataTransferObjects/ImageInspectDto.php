<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * Image inspect response - holds full raw data for flexibility.
 *
 * @phpstan-type ImageInspectArray array<string, mixed>
 */
final readonly class ImageInspectDto
{
    public function __construct(
        public array $data,
    ) {}

    /**
     * @param  ImageInspectArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function id(): string
    {
        return (string) ($this->data['Id'] ?? '');
    }

    public function repoTags(): array
    {
        return $this->data['RepoTags'] ?? [];
    }

    public function createdAt(): string
    {
        return (string) ($this->data['Created'] ?? '');
    }

    public function size(): int
    {
        return (int) ($this->data['Size'] ?? 0);
    }

    public function architecture(): string
    {
        return (string) ($this->data['Architecture'] ?? '');
    }

    public function os(): string
    {
        return (string) ($this->data['Os'] ?? '');
    }
}
