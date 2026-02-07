<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * System info response - holds full raw data.
 *
 * @phpstan-type SystemInfoArray array<string, mixed>
 */
final readonly class SystemInfo
{
    public function __construct(
        public array $data,
    ) {}

    /**
     * @param  SystemInfoArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function name(): string
    {
        return (string) ($this->data['Name'] ?? '');
    }

    public function ncpu(): int
    {
        return (int) ($this->data['NCPU'] ?? 0);
    }

    public function memTotal(): int
    {
        return (int) ($this->data['MemTotal'] ?? 0);
    }

    public function containers(): int
    {
        return (int) ($this->data['Containers'] ?? 0);
    }

    public function containersRunning(): int
    {
        return (int) ($this->data['ContainersRunning'] ?? 0);
    }

    public function images(): int
    {
        return (int) ($this->data['Images'] ?? 0);
    }
}
