<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type SystemVersionArray array{Version?: string, ApiVersion?: string, MinAPIVersion?: string, GoVersion?: string, Os?: string, Arch?: string, KernelVersion?: string, BuildTime?: string}
 */
final readonly class SystemVersion
{
    public function __construct(
        public string $version,
        public string $apiVersion,
        public ?string $minApiVersion = null,
        public ?string $goVersion = null,
        public ?string $os = null,
        public ?string $arch = null,
        public ?string $kernelVersion = null,
        public ?string $buildTime = null,
    ) {}

    /**
     * @param  SystemVersionArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            version: $data['Version'] ?? '',
            apiVersion: $data['ApiVersion'] ?? '',
            minApiVersion: isset($data['MinAPIVersion']) ? (string) $data['MinAPIVersion'] : null,
            goVersion: isset($data['GoVersion']) ? (string) $data['GoVersion'] : null,
            os: isset($data['Os']) ? (string) $data['Os'] : null,
            arch: isset($data['Arch']) ? (string) $data['Arch'] : null,
            kernelVersion: isset($data['KernelVersion']) ? (string) $data['KernelVersion'] : null,
            buildTime: isset($data['BuildTime']) ? (string) $data['BuildTime'] : null,
        );
    }
}
