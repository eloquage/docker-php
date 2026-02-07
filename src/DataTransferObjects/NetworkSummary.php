<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type NetworkSummaryArray array{Name?: string, Id?: string, Created?: string, Scope?: string, Driver?: string, EnableIPv6?: bool, IPAM?: array<string, mixed>, Internal?: bool, Attachable?: bool, Ingress?: bool, Labels?: array<string, string>}
 */
final readonly class NetworkSummary
{
    public function __construct(
        public string $name,
        public string $id,
        public string $driver,
        public string $scope,
        public bool $internal,
        public array $labels,
        public ?string $created = null,
        public bool $enableIPv6 = false,
        public bool $attachable = false,
        public bool $ingress = false,
    ) {}

    /**
     * @param  NetworkSummaryArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['Name'] ?? '',
            id: $data['Id'] ?? '',
            driver: $data['Driver'] ?? 'bridge',
            scope: $data['Scope'] ?? 'local',
            internal: (bool) ($data['Internal'] ?? false),
            labels: $data['Labels'] ?? [],
            created: isset($data['Created']) ? (string) $data['Created'] : null,
            enableIPv6: (bool) ($data['EnableIPv6'] ?? false),
            attachable: (bool) ($data['Attachable'] ?? false),
            ingress: (bool) ($data['Ingress'] ?? false),
        );
    }
}
